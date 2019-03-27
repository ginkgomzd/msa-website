<?php
namespace MainStreetAdvocates\Model;

class Import extends \GinkgoStreetLabs\Model {

	public $id;
	public $xml_import_timestamp;
	public $fetching_date;
	public $client_id;
	public $entity_type;

	protected $last_updated = array();

	function __construct() {
		parent::__construct();
		$this->childCollections = array('last_updated');
	}

	protected function getTableName() {
		return 'import_table';
	}

	public function setLastUpdated($array){
		$this->last_updated = $array;
	}

}
