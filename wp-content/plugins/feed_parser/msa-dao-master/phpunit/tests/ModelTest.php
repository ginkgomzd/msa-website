<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use \DaoTests\Model\ProfileKeyword;

class ModelTest extends TestCase {

  public function testCreateEntity() {
    $entity = new ProfileKeyword();

    $entity->keyword = 'entity';
    $entity->profile_match_id = 1;

    $entity->save();

    self::assertTrue(TRUE);
  }
} ?>
