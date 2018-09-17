<?php

namespace GinkgoStreetLabs;

use GinkgoStreetLabs\PdoConnection;
use GinkgoStreetLabs\PdoDebugger;

/**
 * Base class for models.
 *
 * The public properties of all model classes should correspond to columns in
 * the table in which the model is stored.
 */
abstract class Model {

  /**
   * Database Accessor
   * @var GinkgoStreetLabs\PdoConnection;
   */
  protected static $conn;

  /**
   * Array of PDOStatement keyed by Model Class
   * @var array
   */
  protected static $statements = array();

  /**
   * Returns the name of the table in which the class stores data.
   *
   * For use in dynamic query building.
   *
   * @return string
   */
  abstract protected function getTableName();

  /**
   * field => value pairs for INSERT ... SET clause
   * Accessed via getSetClause()
   * @var array
   */
  protected $_setClause = array();

  /**
   * Collection of properties to call saveChildEntity() with on save().
   * @var Array
   */
  protected $childCollections = array();

  public function __construct() {
    if (is_null(self::$conn)){
      self::$conn = new PdoConnection();
    }
    $query = $this->generateInsertQuery();
    $this->prepareStatement($query);
  }

  public function __set($property, $value) {
    if (property_exists($this, $property)) {
      throw new \Exception('Cannot access private property ' . get_class($this) . '::$' . $property);
    }
    else {
      // Dynamic properties (e.g., $obj->letsMakeANewProperty = 'fun with loosely-typed languages';)
      // are disabled so that we can rely on object's public properties for programmatic SQL generation
      throw new \Exception('No property ' . get_class($this) . '::$' . $property . ' exists, and dynamic properties are disabled for this class.');
    }
  }

  protected function bindQueryParams($statement=null, $excludeIfNull=array('id')) {
    if (is_null($statement)) {
      $statement = self::$statements[get_class($this)];
    }
    foreach ($this->getPropertiesAsPdoParams($excludeIfNull) as $param => $value) {
      $statement->bindValue($param, $value);
    }
  }

  public function prepareStatement($query, $model=null) {
    if (is_null($model)) {
      $model = get_class($this);
    }

    if (array_key_exists($model, self::$statements)) {
      // throw new \Exception('Attempted to redefine statement for '.$model. ' not permitted.');
      // TODO: add protection against improper usage;
      return self::$statements[$model];
    }

    $statement = self::$conn->prepare($query);

    self::$statements[$model] = $statement;

    return $statement;
  }

  /**
   * // TODO: cache expensive reflection
   * @return array
   *   The names of the object's public properties.
   */
  protected function getOwnPublicProperties() {
    $reflection = new \ReflectionObject($this);

    $properties = array_map(
      function($property) {
        return $property->getName();
      },
      $reflection->getProperties(\ReflectionProperty::IS_PUBLIC)
    );

    return $properties;
  }

  protected function generateInsertQuery() {
    return
      'INSERT INTO `' . $this->getTableName() . '` SET ' . implode(', ', $this->getSetClause()) . ' '
      . 'ON DUPLICATE KEY UPDATE ' . implode(', ', $this->getSetClause()) . ' '
    ;
  }

  protected function getSetClause() {
    if (empty($this->_setClause)) {
      foreach($this->getPropertiesAsPdoParams() as $param => $value) {
        // remove ':' from param
        $this->_setClause[] = '`' . substr($param,1) . '` = ' . $param;
      }
    }

    return $this->_setClause;
  }

  /**
   * Persists an object in the database.
   *
   * Assumes that each public property of the model class corresponds to a field
   * in a table.
   *
   * @return boolean
   */
  public function save() {
    $this->prepareSave();
    $statement = self::$statements[get_class($this)];
    $this->bindQueryParams($statement);
    $exec = $statement->execute();
    $this->debugQuery();
    if (!$exec) {
      if ($statement->errorCode() === '00000') {
        // connection errors
        self::$conn->debugStatement($statement);
      }
    }

    $id = self::$conn->pdo->lastInsertId();

    // UPDATE instead of INSERT? Retrieve ID:
    if ($id === '0' && $statement->rowCount() === 0) {
      $setClause = $this->getSetClause();
      $key = array_search('`id` = :id', $setClause);
      if ($key !== FALSE) {
        unset($setClause[$key]);
      }
      $query = self::$conn->prepare(
        'SELECT ID FROM `'. $this->getTableName() . '` WHERE '. implode(' AND ', $setClause)
      );
      $this->bindQueryParams($query);
      $query->execute();
      $this->id = $query->fetchColumn();

    } else {
      $this->id = $id;
    }

    $this->saveChildEntities();

    $this->saved();
  }

  /**
   * Override to prepare for a save().
   * calls cleanString() on each property.
   * @return [type] [description]
   */
  protected function prepareSave() {
    foreach ($this->getOwnPublicProperties() as $prop) {
      $this->$prop = self::cleanString($this->$prop);
    }
  }

  /**
   * called post-save().
   * @return [type] [description]
   */
  protected function saved() {}

  /**
   * Invokes saveChildEntity on any properties in $childCollections.
   * Called before saved().
   * @var [type]
   */
  protected function saveChildEntities() {
    if (empty($this->childCollections)) return;
    foreach($this->childCollections as $collection) {
      array_map(array($this, 'saveChildEntity'), $this->$collection);
    }
  }

  /**
   * Wrapper for saving a Model's child entity, updating it with parent data as
   * appropriate.
   *
   * @param \GinkgoStreetLabs\Model $child
   */
  protected function saveChildEntity(Model $child) {
    $propParentId = $this->getTableName() . '_id';
    if (property_exists($child, 'entity_type')) {
      $propParentId = 'entity_id';
      $child->entity_type = $this->getTableName();
    }
    $child->$propParentId = $this->id;
    $child->save();
  }

  protected function getPropertiesAsPdoParams($excludeIfNull=array('id')) {
    $properties = $this->getOwnPublicProperties();
    $queryParams = array();
    foreach ($properties as $field) {
      if (in_array($field, $excludeIfNull) && is_null($this->$field) ) {
        continue;
      }
      $queryParams[":$field"] = $this->$field;
    }
    return $queryParams;
  }

  protected function debugQuery() {
    $query = self::$statements[get_class($this)];;
    $queryParams = $this->getPropertiesAsPdoParams();

    PdoDebugger::debugStatementOnError($query, $queryParams);

    if (defined('DEBUG') && DEBUG) {
      PdoDebugger::showRowCount($query, $this->getTableName());
    }
  }

  public static function cleanString($value) {
    return (is_string($value))? stripslashes(trim($value)): $value;
  }

}
