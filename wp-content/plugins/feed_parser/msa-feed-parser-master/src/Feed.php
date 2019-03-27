<?php

namespace MainStreetAdvocates;

use MainStreetAdvocates\FeedXmlModelMaps as XmlMap;
use MainStreetAdvocates\Model\Import;
use MainStreetAdvocates\Model\LastUpdated;

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
	public $models = array();

	/**
	 * Convenience for not having to call load().
	 *
	 * @param string $string the xml feed
	 */
	public function __construct( $string = null ) {
		if ( ! empty( $string ) ) {
			$this->load( $string );
		}
	}

	/**
	 * Load, Create-Models, and Save.
	 * Load only called if optional xml string is provided.
	 *
	 * @param  string $xml string containing xml
	 *
	 * @return array      model array
	 */
	public function process( $xml = null ) {
		// ensure xml is loaded:
		if ( ! empty( $xml ) ) {
			$this->load( $xml );
		}
		$this->createModels();
		$this->save();
		$this->createImportData($this->models);
		return $this->models;
	}

	/**
	 * Parse xml string and set member properties:
	 * xml, feed, model_class.
	 *
	 * @param  string $string xml string
	 *
	 * @throws \Exception       For unrecognized feed type.
	 */
	public function load( $string ) {
		$this->xml         = $string;
		$simpleXML         = simplexml_load_string( $this->xml );
		$this->model_class = XmlMap::getFeedType( $this->xml );
		$this->feed        = XmlMap::getRootElement( $this->model_class, $simpleXML );
	}

	protected function validateLoad() {
		return
			! empty( $this->xml ) &&
			isset( $this->model_class ) &&
			class_exists( $this->model_class ) &&
			! empty( $this->feed );
	}

	/**
	 * Retrieve a user-submitted file upload and invoke Feed::load()
	 *
	 * @param  string $pathToUpload upload location
	 *
	 * @return string               file contents
	 * @throws \Exception
	 *   For file not found or not readable
	 */
	public function loadFile( $pathToUpload ) {
		$file = @fopen( $pathToUpload, 'r' );
		if ( ! $file ) {
			throw new \Exception( 'Could not open feed at ' . $pathToUpload );
		}
		$fileContents = stream_get_contents( $file );

		return $this->load( $fileContents );
	}

	/**
	 * Create a model object for each item in the feed.
	 *
	 * @param  array $feed array of SimpleXMLElement's
	 *
	 * @return void
	 */
	public function createModels( $feed = null ) {
		if ( ! $this->validateLoad() ) {
			throw new \Exception( 'Could not validate feed is loaded.' );
		}
		/*
		//TODO if we need to separate in two different tables
		$date = new
		self::$conn = new PdoConnection();
		$query = self::$conn->prepare(
			"INSERT INTO `import_table` (`xml_import_timestamp`, `fetching_date`, `daily_email_digest_sent`,`indexing_complete`) VALUES (, '{$state->abbreviation}',{$this->id},1,1)"
		);
		$this->bindQueryParams( $query );
		*/
		$this->models = XmlMap::createModels( $this->feed, $this->model_class );
		return true;
	}

	/**
	 * call save() on each item in $items.
	 * @return void
	 */
	public function save() {
			$this->models_saved = array_map(
				function ( $model ) {
					if($model !== null){
					$model->save();
					}
				}, $this->models );
	}

	public static function createImportData($models){
		$import_date = date("Y-m-d H:i:s");
		$tmp = [];
		foreach ($models as $model){
			$profile_matches = $model->getMatches();
			foreach ($profile_matches as $profile_match){
				if(key_exists($profile_match->client_id,$tmp) == false){
					$tmp[$profile_match->client_id] = [];
					$tmp[$profile_match->client_id]['entity'] = $profile_match->entity_type;
					$tmp[$profile_match->client_id]['bills'] = [];
				}
				if(!in_array($model->id,$tmp[$profile_match->client_id])){
					array_push($tmp[$profile_match->client_id]['bills'],$model->id);
				}
			}
		}

		foreach ($tmp as $key => $import){
			$model = new Import();
			$model->client_id = $key;
			$model->entity_type = $import['entity'];
			$model->xml_import_timestamp = $import_date;
			$model->fetching_date =  date('Y-m-d');
			$lastmodels = [];
			foreach ($import['bills'] as $last){
				$_lastupdate = new LastUpdated();
				$_lastupdate->document_id = $last;
				$lastmodels[] = $_lastupdate;
			}
			$model->setLastUpdated($lastmodels);
			$model->save();
		}
	}
}
