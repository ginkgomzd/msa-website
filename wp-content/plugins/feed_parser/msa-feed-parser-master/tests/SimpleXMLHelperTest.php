<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use MainStreetAdvocates\SimpleXMLHelper;

final class SimpleXMLHelperTest extends TestCase {

  public function testFindFirstArgRequiresSimplexmlelement() {
    $xml = 'Not a SimpleXMLElement';
    $caught = FALSE;
    try {
      $found = SimpleXMLHelper::find($xml, 'action/@id');
    } catch (TypeError $e) {
      $caught = TRUE;
    }
    $this->assertTrue($caught, 'TypeError not thrown.');

  }
  public function testFindAttribute() {
    $xml = simplexml_load_string(self::XML_ACTION_STATE_AGENCY);
    $found = SimpleXMLHelper::find($xml, 'action/@id');

    $this->assertTrue((boolean) $found);
  }

  public function testFindReturnsASimplexmlelement() {
    $xml = simplexml_load_string(self::XML_ACTION_STATE_AGENCY);
    $found = SimpleXMLHelper::find($xml, 'action');

    $this->assertTrue((boolean) $found);
    $this->assertInstanceOf(SimpleXMLElement::class, $found);
  }

  public function testFindReturnsString() {
    $xml = simplexml_load_string(self::XML_ACTION_STATE_AGENCY);
    $found = SimpleXMLHelper::find($xml, 'action/agency_name');

    $this->assertTrue(is_string($found), var_export($found, TRUE));
  }

  public function testFindReturnsACollection() {
    $xml = simplexml_load_string(self::XML_PROFILES);
    $found = SimpleXMLHelper::find($xml, 'profile');

    $this->assertInternalType('array', $found);
    $this->assertGreaterThan(0, count($found));
  }

  public function testFindReturnsAValueCollection() {
    $xml = simplexml_load_string(self::XML_PROFILES);
    $profiles = SimpleXMLHelper::find($xml, 'profile');

    $profile = array_shift($profiles);
    $keywords = SimpleXMLHelper::find($profile, '*/keyword');

    $this->assertInternalType('array', $keywords);
    $this->assertGreaterThan(0, count($keywords));

    $keyword = array_pop($keywords);

    $this->assertNotInstanceOf(SimpleXMLElement::class, $keyword);
  }

  public function testFindAttributeCollection() {
    $xml = simplexml_load_string(self::XML_PROFILES);
    $found = SimpleXMLHelper::find($xml, 'profile/@id');

    $this->assertTrue((boolean) $found);
  }

  public function testFindEmptyForEmpty() {
    $xml = simplexml_load_string(self::XML_ACTION_STATE_AGENCY);
    $found = SimpleXMLHelper::find($xml, 'action/address');

    $this->assertNull($found, var_export($found, TRUE));
  }

  public function testFindFalseForNotFound() {
    $xml = simplexml_load_string(self::XML_ACTION_STATE_AGENCY);
    $found = SimpleXMLHelper::find($xml, 'nonexistant');

    $this->assertFalse($found, var_export($found, TRUE));
  }

  public function testFindSingleElement() {
    $xml = simplexml_load_string(self::XML_PROFILES);
    $found = SimpleXMLHelper::findSingle($xml, 'profile');

    $this->assertFalse($found, 'Collection did not generate False.');

    $xml = simplexml_load_string(self::XML_PROFILES);
    $found = SimpleXMLHelper::findSingle($xml, 'profiles');
  }

  const XML_ACTION_STATE_AGENCY = "
  <actions>
  <action id='983609' tracking_key='AL-92e2-cb6e-5516-2406'>
    <state>AL</state>
    <agency_name>
      <![CDATA[Environmental Management, Department of]]>
    </agency_name>
    <address></address>
  </action>
  </actions>
  ";

  const XML_PROFILES = "
    <profiles>
      <profile id='7675'>
        <pname>
          <![CDATA[Astellas > Healthcare General  > Specialty Tiers]]>
        </pname>
        <keywords>
          <keyword>
            <![CDATA[health]]>
          </keyword>
          <keyword>
            <![CDATA[pharmaceutical]]>
          </keyword>
          <keyword>
            <![CDATA[tier---3]]>
          </keyword>
        </keywords>
      </profile>
      <profile id='7284'>
        <pname>
          <![CDATA[Poet  > Profile > anti-ethanol and renewable fuel standard]]>
        </pname>
        <keywords>
          <keyword>
            <![CDATA[ethanol]]>
          </keyword>
        </keywords>
      </profile>
    </profiles>
  ";
}
 ?>
