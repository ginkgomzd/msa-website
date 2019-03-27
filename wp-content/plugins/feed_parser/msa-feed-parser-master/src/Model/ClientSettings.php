<?php
/**
 * Created by PhpStorm.
 * User: ljubi
 * Date: 12/6/2018
 * Time: 4:18 PM
 */
namespace MainStreetAdvocates\Model;

class ClientSettings extends \GinkgoStreetLabs\Model{
	public $id;
	public $client_id;
	public $type;
	public $category;

	function __construct() {
		parent::__construct();

	}

	/**
	 * Returns the name of the table in which the class stores data.
	 *
	 * For use in dynamic query building.
	 *
	 * @return string
	 */
	protected function getTableName() {
		return 'client_settings';
	}
}