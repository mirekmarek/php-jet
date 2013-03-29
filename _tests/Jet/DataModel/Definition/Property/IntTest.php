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

require_once "_mock/Jet/DataModel/Definition/DataModelTestMock.php";

class DataModel_Definition_Property_IntTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DataModel_Definition_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Property_Int
	 */
	protected $object;

	protected $property_class_name = "DataModel_Definition_Property_Int";

	protected $property_name = "int_property";

	protected $property_options = array();

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$class_name = __NAMESPACE__."\\".$this->property_class_name;

		$this->data_model = new DataModel_Definition_DataModelTestMock();

		$this->property_options = $this->data_model->_test_get_property_options($this->property_name);

		$this->object = new $class_name( $this->data_model->getDataModelDefinition(), $this->property_name, $this->property_options );
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_Float::checkValueType
	 */
	public function testCheckValueType() {
		$value = "3.14";
		$this->object->checkValueType($value);

		$this->assertSame(3, $value);
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_Float::setUp
	 * @covers Jet\DataModel_Definition_Property_Float::getMinValue
	 */
	public function testGetMinValue() {
		$this->assertSame($this->property_options["min_value"], $this->object->getMinValue());
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_Float::setUp
	 * @covers Jet\DataModel_Definition_Property_Float::getMaxValue
	 */
	public function testGetMaxValue() {
		$this->assertSame($this->property_options["max_value"], $this->object->getMaxValue());
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_Float::setUp
	 * @covers Jet\DataModel_Definition_Property_Float::getFormFieldOptions
	 */
	public function testGetFormFieldOptions() {
		$options = $this->object->getFormFieldOptions();
		$this->assertArrayHasKey("min_value", $options);
		$this->assertArrayHasKey("max_value", $options);

		$this->assertSame($this->property_options["min_value"], $options["min_value"]);
		$this->assertSame($this->property_options["max_value"], $options["max_value"]);
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_Abstract::validateData
	 * @covers Jet\DataModel_Definition_Property_Float::_validateData_test_value
	 */
	public function testValidateDataFailedOutOfRange() {
		$value = "10";
		$errors = array();

		$this->assertFalse( $this->object->validateData($value, $errors) );

		$this->assertArrayHasKey(0, $errors);
		/**
		 * @var DataModel_ValidationError $error
		 */
		$error = $errors[0];

		$this->assertEquals(DataModel_ValidationError::CODE_OUT_OF_RANGE, $error->getCode());
	}


	/**
	 * @covers Jet\DataModel_Definition_Property_Float::validateData
	 */
	public function testValidateData() {
		$value = 2;
		$errors = array();
		$this->assertTrue( $this->object->validateData($value, $errors) );
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_Float::getTechnicalDescription
	 */
	public function testGetTechnicalDescription() {

		$this->assertEquals(
				"Type: Int , required: yes, default value: 2, min. value: 1, max. value: 4\n\nDescription",
				$this->object->getTechnicalDescription()
			);
	}


	/**
	 * @covers Jet\DataModel_Definition_Property_Int::setUp
	 * @covers Jet\DataModel_Definition_Property_Abstract::getFormField
	 */
	public function testGetFormField() {
		$field = new Form_Field_Int("");

		$field->__test_set_state(array(
			'_name' => $this->property_name,
			'_form' => null,
			'_form_name' => '',
			'_value_raw' => $this->property_options["default_value"],
			'_value' => "2",
			'default_value' => $this->property_options["default_value"],
			'is_required' => $this->property_options["is_required"],
			'min_value' => $this->property_options["min_value"],
			'max_value' => $this->property_options["max_value"],
			'catch_data_callback' => null,
			'validate_data_callback' => null,
			'select_options' =>
			array (
			),
		));

		$this->assertEquals($field, $this->object->getFormField());

	}

}
