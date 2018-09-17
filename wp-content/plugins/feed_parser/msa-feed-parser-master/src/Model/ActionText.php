<?php
namespace MainStreetAdvocates\Model;

class ActionText extends \GinkgoStreetLabs\Model {

  public $id;
  public $external_id;
  public $original_url;
  public $statetrack_url;
  public $type;
  public $regulation_id;

  protected $keywords = array();

  protected function getTableName() {
    return 'action_text';
  }
}
