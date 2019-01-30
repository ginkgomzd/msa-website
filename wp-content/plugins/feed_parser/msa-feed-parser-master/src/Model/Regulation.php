<?php
namespace MainStreetAdvocates\Model;
use GinkgoStreetLabs\Model;

class Regulation extends Model {

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
  protected $entity_text = array();

  function __construct() {
    parent::__construct();
    $this->childCollections = array('profile_matches', 'action_texts','entity_text');
  }

  protected function getTableName() {
    return 'regulation';
  }

  public function setMatches($array) {
    $this->profile_matches = $array;
  }

  public function getMatches(){
  	return $this->profile_matches;
  }

  public function setTexts($array) {
    $this->action_texts = $array;
  }

  public function setEntityText($array){
  	$this->entity_text = $array;
  }

}
