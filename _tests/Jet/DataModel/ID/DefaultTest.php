<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package DataModel
 */
namespace Jet;

require_once "_mock/Jet/DataModel/ID/DataModelTestMock.php";


class DataModel_ID_DefaultTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DataModel_ID_Default
	 */
	protected $object;

	protected $ID_data = array(
			"ID" => "myID",
			"ID_property_1" => "abcdefg",
			"ID_property_2" => "cs_CZ",
			"ID_property_3" => 1234,

		);

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$data_model = new DataModel_ID_DataModelTestMock();
		$this->object = new DataModel_ID_Default( $data_model );

		foreach($this->ID_data as $k=>$v) {
			$this->object[$k] = $v;
		}
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers Jet\DataModel_ID_Abstract::__sleep
	 * @covers Jet\DataModel_ID_Abstract::__wakeup
	 */
	public function test__sleepAndWakeup() {
		$data = serialize($this->object);
		$new_object = unserialize($data);

		$ID_data = array();
		foreach($new_object as $k=>$v) {
			$ID_data[$k] = $v;
		}

		$this->assertSame($this->ID_data, $ID_data);
	}


	/**
	 * @covers Jet\DataModel_ID_Abstract::__toString
	 * @covers Jet\DataModel_ID_Abstract::toString
	 */
	public function testToString() {
		$this->assertSame("myID:abcdefg:cs_CZ:1234", $this->object->toString());
	}

	/**
	 * @covers Jet\DataModel_ID_Abstract::unserialize
	 */
	public function testUnserialize() {
		$this->object->unserialize("myID-t:abcdefg-t:sk_SK:12345");

		$ID_data = array();
		foreach($this->object as $k=>$v) {
			$ID_data[$k] = $v;
		}

		$valid_ID_data = array(
			"ID" => "myID-t",
			"ID_property_1" => "abcdefg-t",
			"ID_property_2" => "sk_SK",
			"ID_property_3" => 12345,

		);

		$this->assertEquals($valid_ID_data, $ID_data);
	}


	/**
	 * @covers Jet\DataModel_ID_Abstract::offsetExists
	 */
	public function testOffsetExists() {
		$this->assertTrue( isset($this->object["ID_property_3"]) );
		$this->assertFalse( isset($this->object["imaginary"]) );
	}

	/**
	 * @covers Jet\DataModel_ID_Abstract::offsetUnset
	 */
	public function testOffsetUnset() {
		//do nothing
		unset($this->object["nothning"]);
	}

	/**
	 * @covers Jet\DataModel_ID_Abstract::offsetSet
	 * @covers Jet\DataModel_ID_Abstract::offsetGet
	 * @covers Jet\DataModel_ID_Abstract::key
	 * @covers Jet\DataModel_ID_Abstract::next
	 * @covers Jet\DataModel_ID_Abstract::current
	 * @covers Jet\DataModel_ID_Abstract::rewind
	 * @covers Jet\DataModel_ID_Abstract::valid
	 */
	public function testIterator() {

		$ID_data = array();
		foreach($this->object as $k=>$v) {
			$ID_data[$k] = $v;
		}

		$this->assertSame($this->ID_data, $ID_data);
	}

	/**
	 * @covers Jet\DataModel_ID_Default::getMaxLength
	 */
	public function testGetMaxLength() {
		$this->assertEquals(DataModel_ID_Default::MAX_LEN, $this->object->getMaxLength());
	}


	/**
	 * @covers Jet\DataModel_ID_Default::generateID
	 */
	public function testGenerateID() {
		$ID = $this->object->generateID("Sítě   1  ", function( $input ) {
			return ($input=="site_1" || $input=="site_11");

		});
		$this->assertEquals("site_12", $ID);

		$ID = $this->object->generateID("Long Long Long Long Long Long Long Long Long Long Long Long Long Long Long Long Long Long ", function( $input ) {
			return (
				$input=="long_long_long_long_long_long_long_long_long_long_" ||
				$input=="long_long_long_long_long_long_long_long_long_l1" ||
				$input=="long_long_long_long_long_long_long_long_long_l2" ||
				$input=="long_long_long_long_long_long_long_long_long_l3" ||
				$input=="long_long_long_long_long_long_long_long_long_l4"
			);

		});

		$this->assertEquals("long_long_long_long_long_long_long_long_long_l5", $ID);
	}


	/**
	 * @covers Jet\DataModel_ID_Default::checkFormat
	 */
	public function testCheckFormat() {
		$this->assertTrue($this->object->checkFormat("valid_id_1"));

		$this->assertFalse($this->object->checkFormat("Invalid_ID_1"));
		$this->assertFalse($this->object->checkFormat("sh"));
		$this->assertFalse($this->object->checkFormat("long_long_long_long_long_long_long_long_long_long_long_"));
		$this->assertFalse($this->object->checkFormat("%^&*(%#.") );

	}

}
