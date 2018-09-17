<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use \MainStreetAdvocates\Feed;
use \MSATests\TestHelper as TestConf;

final class FeedTest extends TestCase {

  public function testFeedProcess() {
    $feed = new Feed();
    $feed->process(file_get_contents(TestConf::LEG_FEED_FILE));

    $feed = new Feed();
    $feed->process(file_get_contents(TestConf::REG_FEED_FILE));

    $feed = new Feed();
    $feed->process(file_get_contents(TestConf::HRG_FEED_FILE));

    self::assertTrue(TRUE);
  }
  public function testFeedConstructor() {
    new Feed(file_get_contents(TestConf::LEG_FEED_FILE));
    new Feed(file_get_contents(TestConf::REG_FEED_FILE));
    new Feed(file_get_contents(TestConf::HRG_FEED_FILE));

    self::assertTrue(TRUE);
  }

  public function testFeedLoadFile() {
    $feed = new Feed();
    $feed->loadFile(TestConf::LEG_FEED_FILE);

    $feed = new Feed();
    $feed->loadFile(TestConf::REG_FEED_FILE);

    $feed = new Feed();
    $feed->loadFile(TestConf::HRG_FEED_FILE);

    self::assertTrue(TRUE);
  }

  /**
   *
   * @expectedException   Exception
   * @expectedExceptionMessage  Could not open feed
   */
  public function testFeedLoadFileInvalidPath() {
    $feed = new Feed();
    $feed->loadFile('/not/a/valid/path');
  }

  /**
   *
   * @expectedException Exception
   * @expectedExceptionMessage  Could not validate feed is loaded.
   */
  public function testFeedCreateModels() {
    $feed = new Feed();
    $feed->loadFile(TestConf::HRG_FEED_FILE);
    self::assertTrue($feed->createModels());

    $feed = new Feed();
    $feed->createModels();
  }
}
