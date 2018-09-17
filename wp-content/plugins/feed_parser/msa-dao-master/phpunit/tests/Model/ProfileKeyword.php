<?php
namespace DaoTests\Model;

class ProfileKeyword extends \GinkgoStreetLabs\Model {

  public $id;
  public $profile_match_id;
  public $keyword;

  protected function getTableName() {
    return 'profile_keyword';
  }
}
