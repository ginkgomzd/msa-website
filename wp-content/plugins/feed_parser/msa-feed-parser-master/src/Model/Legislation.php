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
