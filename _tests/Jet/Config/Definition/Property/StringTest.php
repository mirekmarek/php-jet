<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package Config
 */
namespace Jet;

require_once "_mock/Jet/Config/ConfigTestMock.php";

class Config_Definition_Property_StringTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var ConfigTestMock
	 */
	protected $config;
	/**
	 * @var Config_Definition_Property_String
	 */
	protected $object;


	protected $property_type = Config::TYPE_STRING;

	protected $property_class_name = "Config_Definition_Property_String";

	protected $property_name = "StringTest";

	protected $property_default_form_field_type = "Input";

	protected $validation_regexp = "/^([a-z0-9]{1,10})$/";

	protected $default_value = "default value";

	protected $property_options = array(
		"description" => "Description",
		"default_value" => "",
		"is_required" => true,
		"error_message" => "Error Message",
		"label" => "Label",
		"form_field_label" => "Form field label"
	);

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {

		$class_name = __NAMESPACE__."\\".$this->property_class_name;
		$this->property_options["default_value"] = $this->default_value;

		$this->config = new ConfigTestMock("test");
		$this->object = new $class_name( $this->config, $this->property_name, $this->property_options  );
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::__toString
	 * @covers Jet\Config_Definition_Property_Abstract::toString
	 */
	public function testToString() {
		$this->assertEquals( "Jet\ConfigTestMock::".$this->property_name, (string)$this->object );
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::getType
	 */
	public function testGetType() {
		$this->assertEquals($this->property_type, $this->object->getType() );
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::getName
	 */
	public function testGetName() {
		$this->assertEquals($this->property_name, $this->object->getName() );
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::getConfiguration
	 */
	public function testGetConfiguration() {
		$this->assertEquals($this->config, $this->object->getConfiguration() );
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::getIsArray
	 */
	public function testGetIsArray() {
		$this->assertFalse( $this->object->getIsArray() );
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::setDescription
	 * @covers Jet\Config_Definition_Property_Abstract::getDescription
	 */
	public function testSetGetDescription() {
		$this->assertEquals($this->property_options["description"], $this->object->getDescription());
		$this->object->setDescription( "Description ..." );
		$this->assertEquals("Description ...", $this->object->getDescription());
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::setDefaultValue
	 * @covers Jet\Config_Definition_Property_Abstract::getDefaultValue
	 */
	public function testSetGetDefaultValue() {
		$this->assertEquals($this->property_options["default_value"], $this->object->getDefaultValue());
		$this->object->setDefaultValue( "default value ..." );
		$this->assertEquals("default value ...", $this->object->getDefaultValue());
	}


	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::setIsRequired
	 * @covers Jet\Config_Definition_Property_Abstract::getIsRequired
	 */
	public function testSetGetIsRequired() {
		$this->assertTrue( $this->object->getIsRequired() );
		$this->object->setIsRequired( false );
		$this->assertFalse( $this->object->getIsRequired() );
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::setErrorMessage
	 * @covers Jet\Config_Definition_Property_Abstract::getErrorMessage
	 */
	public function testSetGetErrorMessage() {
		$this->assertEquals($this->property_options["error_message"], $this->object->getErrorMessage());
		$this->object->setErrorMessage( "Error Message ..." );
		$this->assertEquals("Error Message ...", $this->object->getErrorMessage());
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::setLabel
	 * @covers Jet\Config_Definition_Property_Abstract::getLabel
	 */
	public function testSetGetLabel() {
		$this->assertEquals($this->property_options["label"], $this->object->getLabel());
		$this->object->setLabel( "Label ..." );
		$this->assertEquals("Label ...", $this->object->getLabel());
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::setFormFieldType
	 * @covers Jet\Config_Definition_Property_Abstract::getFormFieldType
	 */
	public function testSetGetFormFieldType() {
		$this->assertEquals( $this->property_default_form_field_type, $this->object->getFormFieldType() );
		$this->object->setFormFieldType("Select");
		$this->assertEquals( "Select", $this->object->getFormFieldType() );
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::setFormFieldOptions
	 * @covers Jet\Config_Definition_Property_Abstract::getFormFieldOptions
	 */
	public function testSetGetFormFieldOptions() {
		$options = array(
			"option_1" => "Option 1",
			"option_2" => "Option 2",
		);
		$this->object->setFormFieldOptions($options);
		$this->assertEquals($options, $this->object->getFormFieldOptions());
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::setFormFieldLabel
	 * @covers Jet\Config_Definition_Property_Abstract::getFormFieldLabel
	 */
	public function testSetGetFormFieldLabel() {
		$this->assertEquals($this->property_options["form_field_label"], $this->object->getFormFieldLabel());
		$this->object->setFormFieldLabel("Form field label:");
		$this->assertEquals("Form field label:", $this->object->getFormFieldLabel());
	}


	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::setFormFieldGetSelectOptionsCallback
	 * @covers Jet\Config_Definition_Property_Abstract::getFormFieldGetSelectOptionsCallback
	 */
	public function testSetFormFieldGetSelectOptionsCallback() {
		$callback = function() {
			//test callback
		};

		$this->object->setFormFieldGetSelectOptionsCallback( $callback );
		$this->assertEquals( $callback, $this->object->getFormFieldGetSelectOptionsCallback() );
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::setFormFieldErrorMessages
	 * @covers Jet\Config_Definition_Property_Abstract::getFormFieldErrorMessages
	 */
	public function testSetGetFormFieldErrorMessages() {
		$error_messages = array(
			"input_missing" => "Input is missing",
			"empty" => "Input is empty",
			"invalid_format" => "Invalid format"
		);


		$this->object->setFormFieldErrorMessages($error_messages);
		$this->assertEquals($error_messages, $this->object->getFormFieldErrorMessages());
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::getFormField
	 */
	public function testGetFormField() {
		$this->object->setValidationRegexp($this->validation_regexp);

		$field = new Form_Field_Input("");

		$field->__test_set_state(array(
			'_name' => 'StringTest',
			'_value_raw' => 'default value',
			'_value' => 'default value',
			'_has_value' => false,
			'_is_valid' => false,
			'_last_error' => '',
			'_last_error_message' => '',
			'default_value' => 'default value',
			'label' => 'Form field label',
			'is_required' => true,
			'validation_regexp' => $this->validation_regexp,
			'catch_data_callback' => NULL,
			'validate_data_callback' => NULL,
			'select_options' =>
			array (
			),
		));

		$this->assertEquals($field, $this->object->getFormField());
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::getTechnicalDescription
	 */
	public function testGetTechnicalDescription() {
		$this->object->setValidationRegexp($this->validation_regexp);

		$this->assertEquals(
			"Type: String, required: yes, default value: default value, valid value regular expression: {$this->validation_regexp}\n\nDescription",
			$this->object->getTechnicalDescription()
		);
	}


	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_String::getValidationRegexp
	 * @covers Jet\Config_Definition_Property_String::setValidationRegexp
	 */
	public function testSetGetValidationRegexp() {
		if($this->property_type == Config::TYPE_STRING) {
			$this->object->setValidationRegexp($this->validation_regexp);
			$this->assertEquals($this->validation_regexp, $this->object->getValidationRegexp());
		}
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::checkValue
	 *
	 * @expectedException \Jet\Config_Exception
	 * @expectedExceptionCode \Jet\Config_Exception::CODE_CONFIG_CHECK_ERROR
	 */
	public function testCheckValueFailedEmpty() {
		$value = "";

		$this->object->checkValue( $value );
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::checkValue
	 *
	 * @expectedException \Jet\Config_Exception
	 * @expectedExceptionCode \Jet\Config_Exception::CODE_CONFIG_CHECK_ERROR
	 */
	public function testCheckValueFailedInvalidFormat() {
		if($this->property_type == Config::TYPE_STRING) {
			$value = "A=^------------------";

			$this->object->setValidationRegexp($this->validation_regexp);
			$this->object->checkValue( $value );
		} else {
			throw new Config_Exception(
				"Test",
				Config_Exception::CODE_CONFIG_CHECK_ERROR
			);
		}
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::checkValue
	 *
	 */
	public function testCheckValue() {
		$value = "valid";

		$this->object->setValidationRegexp($this->validation_regexp);
		$this->assertTrue( $this->object->checkValue( $value ) );
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_String::checkValueType
	 */
	public function testCheckValueType() {
		$value = 123.4;

		$this->object->checkValueType( $value );

		$this->assertSame("123.4", $value);
	}

}
