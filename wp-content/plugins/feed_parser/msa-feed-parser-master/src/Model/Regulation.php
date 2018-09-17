<?php
namespace MainStreetAdvocates\Model;

class Regulation extends \GinkgoStreetLabs\Model {

  public $id;
  public $external_id;
  public $tracking_key;
  public $state;
  public $agency_name;
  public $type;
  public $state_action_type;
  public $full_text_id;
  public $full_text_url;
  public $full_text_local_url;
  public $full_text_type;
  public $code_citation;
  public $description;
  public $register_date;
  public $register_citation;
  public $register_url;

  protected $action_texts = array();
  protected $profile_matches = array();

  function __construct() {
    parent::__construct();
    $this->childCollections = array('profile_matches', 'action_texts');
  }

  protected function getTableName() {
    return 'regulation';
  }

  public function setMatches($array) {
    $this->profile_matches = $array;
  }

  public function setTexts($array) {
    $this->action_texts = $array;
  }

}
