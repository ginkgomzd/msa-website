<?php

namespace GinkgoStreetLabs;

use PDO;
use \SqlFormatter;

Class PdoDebugger {

  public static function debugStatementOnError(\PDOStatement $statement, $params=null) {
    if (!defined('DEBUG') || !DEBUG) return;

    if (is_null($statement->errorCode())) {
      // Statement not executed:
      return;
    }

    if($statement->errorCode() !== '00000' || defined('DEBUG_VERBOSE_SQL') && DEBUG_VERBOSE_SQL) {
      self::debugStatement($statement, $params);
    }
  }

  public static function debugStatement(\PDOStatement $statement, $params=null) {
    if (!defined('DEBUG') || !DEBUG) return;

    if (is_null($statement->errorCode())) {
      echo self::fmtDebugField('Statement not executed');
    }

    echo self::fmtDebugField('PDO::debugDumpParams');

    if (empty($params)) {
      $statement->debugDumpParams();
    } else {
      echo SqlFormatter::format(self::interpolateQuery($statement->queryString, $params));
    }

    if ($statement->errorCode() !== '00000') {
      // Error in query:
      echo self::fmtDebugField('SQLSTATE', $statement->errorCode());
      echo self::fmtDebugField('errorInfo', '<pre>'.var_export($statement->errorInfo(), true).'</pre>');
      echo "<br />";
    } else {
      self::showRowCount($statement);
    }
  }

  public static function debugStatementResult(\PDOStatement $statement) {
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    if (count($results)) {
      echo self::fmtDebugField('Result Returned');
      echo '<table border="1"><tr>';
      foreach(array_keys($results[0]) as $col){
        echo "<th>{$col}</th>";
      }
      echo '</tr>';
      foreach($results as $row) {
        echo '<tr>';
        foreach ($row as $col => $val) {
          echo '<td>'.var_export($val, true).'</td>';
        }
        echo '</tr>';
      }
      echo '</table>';
    }
  }

  public static function showRowCount($statement, $table='') {
    $count = $statement->rowCount();
    if ($count) {
      echo self::fmtDebugField("{$table} Rows Affected", $count);
    }
  }

  /**
   * CREDITT: https://stackoverflow.com/a/1376838/3075409
   *
   * Replaces any parameter placeholders in a query with the value of that
   * parameter. Useful for debugging. Assumes anonymous parameters from
   * $params are are in the same order as specified in $query
   *
   * @param string $query The sql query with parameter placeholders
   * @param array $params The array of substitution parameters
   * @return string The interpolated query
   */
  public static function interpolateQuery($query, $params) {
    // avoid collision by reverse-sort to replacing long-keys first:
    krsort($params);

    $keys = array();
    $values = $params;

    # build a regular expression for each parameter
    foreach ($params as $key => $value) {
      if (is_string($key)) {
        if (strpos($key, ':') === FALSE) {
          $key = ':'.$key;
        }
        $keys[] = '/'.$key.'/';
      } else { // positional params:
        $keys[] = '/[?]/';
      }

      if (is_string($value))
        $values[$key] = "'" . $value . "'";

      if (is_array($value))
        $values[$key] = "'" . implode("','", $value) . "'";

      if (is_null($value))
        $values[$key] = 'NULL';
    }

    $query = preg_replace($keys, $values, $query);

    return $query;
  }

  public static function fmtDebugField($label, $value=NULL) {
    return sprintf('<br /><em><b>%s:</b></em> %s', $label, $value);
  }

}
