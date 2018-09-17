<?php
namespace DaoTests\Model;

class ProfileMatch extends \GinkgoStreetLabs\Model {

  public $id;
  public $external_id;
  public $pname;
  public $entity_type;
  public $entity_id;
  public $client;

  protected $keywords = array();

  function __construct() {
    parent::__construct();
    $this->childCollections = array('keywords');
  }

  protected function getTableName() {
    return 'profile_match';
  }

  public function setKeywords($array) {
    $this->keywords = $array;
  }

}
