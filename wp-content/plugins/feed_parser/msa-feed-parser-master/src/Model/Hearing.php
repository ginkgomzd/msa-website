<?php
namespace MainStreetAdvocates\Model;

class Hearing extends \GinkgoStreetLabs\Model {

  public $id;
  public $external_id;
  public $legislation_external_id;
  public $date;
  public $time;
  public $house;
  public $committee;
  public $place;
  public $state;

  protected $profile_matches = array();

  function __construct() {
    parent::__construct();
    $this->childCollections = array('profile_matches');
  }

  protected function getTableName() {
    return 'hearing';
  }

  public function setMatches($array) {
    $this->profile_matches = $array;
  }

  public function getMatches(){
  	return $this->profile_matches;
  }
}
