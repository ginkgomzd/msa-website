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
  public $textUploaded;

  protected $profile_matches = array();
  protected $bills_matches = array();
  protected $entity_text = array();

  function __construct() {
    parent::__construct();
    $this->childCollections = array('profile_matches','bills_matches','entity_text');
  }


  public function getSessionState(){
	  $query = self::$conn->prepare(
		  'SELECT is_active FROM legislation as leg LEFT JOIN session_info as si ON leg.session_id = si.id WHERE external_id ="'.$this->external_id.'"'
	  );
	  $this->bindQueryParams($query);
	  $query->execute();
	  return $query->fetchColumn();
  }


  protected function getTableName() {
    return 'legislation';
  }

  public function setMatches($array) {
    $this->profile_matches = $array;
  }

  public function getMatches(){
  	return $this->profile_matches;
  }

  public function setBills($array) {
    $this->bills_matches = $array;
  }

  public function setEntityText($array){
		$this->entity_text = $array;
  }

  public function getEntityText(){
  	    return $this->entity_text;
  }
}