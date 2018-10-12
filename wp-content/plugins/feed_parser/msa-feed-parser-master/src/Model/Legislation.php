<?php
namespace MainStreetAdvocates\Model;

class Legislation extends \GinkgoStreetLabs\Model {

  public $id;
  public $external_id;
  public $session;
  public $state;
  public $type;
  public $number;
  public $title;
  public $abstract;
  public $full_text_url;
  public $sponsor_name;
  public $sponsor_url; 
  public $status_date;
  public $status_val; 
  public $status_standardkey;  
  public $status_standard_val; 
  public $status_url; 

  protected $profile_matches = array();

  function __construct() {
    parent::__construct();
    $this->childCollections = array('profile_matches');
  }

  protected function getTableName() {
    return 'legislation';
  }

  public function setMatches($array) {
    $this->profile_matches = $array;
  }
}
