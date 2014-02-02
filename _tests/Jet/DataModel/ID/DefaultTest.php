<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package DataModel
 */
namespace Jet;

require_once '_mock/Jet/DataModel/ID/DataModelTestMock.php';


class DataModel_ID_DefaultTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DataModel_ID_Default
	 */
	protected $ID_object;

	/**
	 * @var DataModel_ID_DataModelTestMock
	 */
	protected $data_model_object;

	protected $ID_data = array(
			'ID' => 'myID',
			'ID_property_1' => 'abcdefg',
			'ID_property_2' => 'cs_CZ',
			'ID_property_3' => 1234,

		);

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->data_model_object = new DataModel_ID_DataModelTestMock();
		$this->ID_object = new DataModel_ID_Default( $this->data_model_object->getDataModelDefinition() );

		foreach($this->ID_data as $k=>$v) {
			$this->ID_object[$k] = $v;
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
		$data = serialize($this->ID_object);
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
		$this->assertSame('myID:abcdefg:cs_CZ:1234', $this->ID_object->toString());
	}

	/**
	 * @covers Jet\DataModel_ID_Abstract::unserialize
	 */
	public function testUnserialize() {
		$this->ID_object->unserialize('myID-t:abcdefg-t:sk_SK:12345');

		$ID_data = array();
		foreach($this->ID_object as $k=>$v) {
			$ID_data[$k] = $v;
		}

		$valid_ID_data = array(
			'ID' => 'myID-t',
			'ID_property_1' => 'abcdefg-t',
			'ID_property_2' => 'sk_SK',
			'ID_property_3' => 12345,

		);

		$this->assertEquals($valid_ID_data, $ID_data);
	}


	/**
	 * @covers Jet\DataModel_ID_Abstract::offsetExists
	 */
	public function testOffsetExists() {
		$this->assertTrue( isset($this->ID_object['ID_property_3']) );
		$this->assertFalse( isset($this->ID_object['imaginary']) );
	}

	/**
	 * @covers Jet\DataModel_ID_Abstract::offsetUnset
	 */
	public function testOffsetUnset() {
		//do nothing
		unset($this->ID_object['nothning']);
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
		foreach($this->ID_object as $k=>$v) {
			$ID_data[$k] = $v;
		}

		$this->assertSame($this->ID_data, $ID_data);
	}

	/**
	 * @covers Jet\DataModel_ID_Default::getMaxLength
	 */
	public function testGetMaxLength() {
		$this->assertEquals(DataModel_ID_Default::MAX_LEN, $this->ID_object->getMaxLength());
	}


	/**
	 * @covers Jet\DataModel_ID_Default::generateID
	 */
	public function testGenerateID() {

		$ID = $this->ID_object->generateID( $this->data_model_object, 'Sítě   1  ');

		$this->assertEquals('site_12', $ID);

		$ID = $this->ID_object->generateID($this->data_model_object, 'Long Long Long Long Long Long Long Long Long Long Long Long Long Long Long Long Long Long ' );

		$this->assertEquals('long_long_long_long_long_long_long_long_long_l5', $ID);

	}


	/**
	 * @covers Jet\DataModel_ID_Default::checkFormat
	 */
	public function testCheckFormat() {
		$this->assertTrue($this->ID_object->checkFormat('valid_id_1'));

		$this->assertFalse($this->ID_object->checkFormat('Invalid_ID_1'));
		$this->assertFalse($this->ID_object->checkFormat('sh'));
		$this->assertFalse($this->ID_object->checkFormat('long_long_long_long_long_long_long_long_long_long_long_'));
		$this->assertFalse($this->ID_object->checkFormat('%^&*(%#.') );

	}

}
