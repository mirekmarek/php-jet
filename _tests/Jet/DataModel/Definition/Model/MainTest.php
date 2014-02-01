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

require_once '_mock/Jet/DataModel/Definition/DataModelTestMock.php';


class DataModel_Definition_Model_MainTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DataModel_Definition_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Model_Main
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->data_model = new DataModel_Definition_DataModelTestMock();


		$this->object = new DataModel_Definition_Model_Main( get_class( $this->data_model ) );
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers Jet\DataModel_Definition_Model_Abstract::getModelName
	 */
	public function testGetModelName() {
		$this->assertEquals('data_model_test_mock', $this->object->getModelName());
	}

	/**
	 * @covers Jet\DataModel_Definition_Model_Abstract::getClassName
	 */
	public function testGetClassName() {

		$this->assertEquals('Jet\\DataModel_Definition_DataModelTestMock', $this->object->getClassName());
	}

	/**
	 * @covers Jet\DataModel_Definition_Model_Abstract::getIDProperties
	 */
	public function testGetIDProperties() {
		$ID_properties = $this->object->getIDProperties();
		$this->assertArrayHasKey('ID', $ID_properties);
		$this->assertArrayHasKey('ID_property', $ID_properties);
	}

	/**
	 * @covers Jet\DataModel_Definition_Model_Abstract::getProperties
	 */
	public function testGetProperties() {
		$properties = $this->object->getProperties();

		$property_names = array(
			'ID',
			'ID_property',
			'string_property',
			'locale_property',
			'int_property',
			'float_property',
			'bool_property',
			'array_property',
			'date_time_property',
			'date_property',
			'data_model_property'
		);

		foreach($property_names as $property_name) {
			$this->assertArrayHasKey($property_name, $properties);
		}
	}
}
