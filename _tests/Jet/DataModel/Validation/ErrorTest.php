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

require_once '_mock/Jet/DataModel/Query/DataModelTestMock.php';

class DataModel_Validation_ErrorTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DataModel_Query_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $properties = array();

	/**
	 * @var DataModel_Validation_Error
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->data_model = new DataModel_Query_DataModelTestMock();

		$this->properties = $this->data_model->getDataModelDefinition()->getProperties();

		$this->object = new DataModel_Validation_Error(
			DataModel_Validation_Error::CODE_REQUIRED,
			$this->properties['string_property'],
			'invalid value'
		);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers Jet\DataModel_ValidationError::getCode
	 */
	public function testGetCode() {
		$this->assertEquals(DataModel_Validation_Error::CODE_REQUIRED, $this->object->getCode());
	}

	/**
	 * @covers Jet\DataModel_ValidationError::getMessage
	 */
	public function testGetMessage() {
		$this->assertEquals('Item is required', $this->object->getMessage());
	}

	/**
	 * @covers Jet\DataModel_ValidationError::getProperty
	 */
	public function testGetProperty() {
		$this->assertEquals($this->properties['string_property'],$this->object->getProperty());
	}

	/**
	 * @covers Jet\DataModel_ValidationError::getPropertyValue
	 */
	public function testGetPropertyValue() {
		$this->assertEquals('invalid value',$this->object->getPropertyValue());
	}

	/**
	 * @covers Jet\DataModel_ValidationError::toString
	 * @covers Jet\DataModel_ValidationError::__toString
	 */
	public function testToString() {
		$this->assertEquals('Jet\\DataModel_Query_DataModelTestMock::string_property : (required) Item is required', (string)$this->object);
	}

}
