<?php
/**
 * Created by PhpStorm.
 * User: ljubi
 * Date: 12/24/2018
 * Time: 10:35 AM
 */

namespace MainStreetAdvocates\Model;


class LastUpdated extends \GinkgoStreetLabs\Model {
	public $id;
	public $import_table_id;
	public $document_id;

	function __construct() {
		parent::__construct();
	}

	protected function getTableName(){
		return 'last_updated';
	}
}