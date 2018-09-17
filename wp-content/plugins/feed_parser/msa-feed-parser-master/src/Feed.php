<?php
namespace MainStreetAdvocates;

use MainStreetAdvocates\FeedXmlModelMaps as XmlMap;

class Feed {
  /**
   * XML string
   * @var string
   */
  protected $xml;

  /**
   * Array of SimpleXMLElements
   * @var array
   */
  protected $feed = array();

  /**
   * Fully qualified Model class name;
   * @var string
   */
  protected $model_class;

  /**
   * Collection of Models
   * @var array
   */
  protected $models = array();

  /**
   * Convenience for not having to call load().
   * @param string $string the xml feed
   */
  public function __construct($string=null) {
    if (!empty($string)) {
      $this->load($string);
    }
  }

  /**
   * Load, Create-Models, and Save.
   * Load only called if optional xml string is provided.
   * @param  string $xml string containing xml
   * @return array      model array
   */
  public function process($xml=null) {
    // ensure xml is loaded:
    if (!empty($xml)) {
      $this->load($xml);
    }
    $this->createModels();
    $this->save();
    return $this->models;
  }

  /**
   * Parse xml string and set member properties:
   * xml, feed, model_class.
   * @param  string $string   xml string
   * @throws \Exception       For unrecognized feed type.
   */
  public function load($string) {
    $this->xml = $string;
    $simpleXML = simplexml_load_string($this->xml);
    $this->model_class = XmlMap::getFeedType($this->xml);
    $this->feed = XmlMap::getRootElement($this->model_class, $simpleXML);
  }

  protected function validateLoad() {
    return
      !empty($this->xml) &&
      isset($this->model_class) &&
      class_exists($this->model_class) &&
      !empty($this->feed)
      ;
  }

  /**
   * Retrieve a user-submitted file upload and invoke Feed::load()
   * @param  string $pathToUpload upload location
   * @return string               file contents
   * @throws \Exception
   *   For file not found or not readable
   */
  public function loadFile($pathToUpload) {
    $file = @fopen($pathToUpload, 'r');
    if (!$file) {
      throw new \Exception('Could not open feed at ' . $pathToUpload);
    }
    $fileContents = stream_get_contents($file);

    return $this->load($fileContents);
  }

  /**
   * Create a model object for each item in the feed.
   * @param  array $feed array of SimpleXMLElement's
   * @return void
   */
  public function createModels($feed=null) {
    if (! $this->validateLoad()) {
      throw new \Exception('Could not validate feed is loaded.');
    }

    $this->models = XmlMap::createModels($this->feed, $this->model_class);

    return TRUE;
  }

  /**
   * call save() on each item in $items.
   * @return void
   */
  public function save() {
    $this->models = array_map(function($model){
      $model->save();
      }, $this->models);
  }
}
