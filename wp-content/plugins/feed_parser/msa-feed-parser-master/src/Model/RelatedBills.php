<?php
namespace MainStreetAdvocates\Model;

class RelatedBills extends \GinkgoStreetLabs\Model {

  public $id;
  public $url;
  public $type;
  public $number;
  public $legislation_id;

  protected function getTableName() {
    return 'related_bill';
  }
    

}