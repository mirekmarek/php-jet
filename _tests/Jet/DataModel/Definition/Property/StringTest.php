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

class DataModel_Definition_Property_StringTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var DataModel_Definition_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Property_String
	 */
	protected $object;

	/**
	 * @var DataModel_Definition_Property_String
	 */
	protected $ID_object;

	/**
	 * @var DataModel_Definition_Property_String
	 */
	protected $ID_model_related;

	protected $property_type = DataModel::TYPE_STRING;

	protected $property_class_name = 'DataModel_Definition_Property_String';

	protected $property_name = 'string_property';

	protected $ID_property_name = 'ID_property';

	protected $property_options = array();

	protected $ID_property_options = array();

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {

		$class_name = __NAMESPACE__.'\\'.$this->property_class_name;

		$this->data_model = new DataModel_Definition_DataModelTestMock();

		$this->property_options = $this->data_model->_test_get_property_options($this->property_name);
		$this->ID_property_options = $this->data_model->_test_get_property_options($this->ID_property_name);

		$this->object = new $class_name( $this->data_model->getDataModelDefinition(), $this->property_name, $this->property_options );
		$this->ID_object = new $class_name( $this->data_model->getDataModelDefinition(), $this->ID_property_name, $this->ID_property_options );
		$this->ID_model_related = new $class_name( $this->data_model->getDataModelDefinition(), 'ID_related', $this->ID_property_options );
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}


	/**
	 * @covers Jet\DataModel_Definition_Property_Abstract::setUpRelation
	 * @expectedException \Jet\DataModel_Exception
	 * @expectedExceptionCode \Jet\DataModel_Exception::CODE_DEFINITION_NONSENSE
	 */
	public function testSetUpRelationFailed() {
		$this->object->setUpRelation($this->ID_object);
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_Abstract::setUpRelation
	 * @covers Jet\DataModel_Definition_Property_Abstract::getRelatedToPropertyName
	 */
	public function testSetUpRelation() {
		$this->assertNull( $this->ID_object->getRelatedToPropertyName() );
		$this->ID_object->setUpRelation($this->ID_model_related);
		$this->assertSame($this->ID_model_related->getName(), $this->ID_object->getRelatedToPropertyName());
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_String::setUp
	 * @covers Jet\DataModel_Definition_Property_Abstract::toString
	 * @covers Jet\DataModel_Definition_Property_Abstract::__toString
	 */
	public function testToString() {
		$this->assertEquals( 'Jet\DataModel_Definition_DataModelTestMock::'.$this->property_name, (string)$this->object );
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_Abstract::getType
	 */
	public function testGetType() {
		$this->assertEquals($this->property_type, $this->object->getType());
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_String::setUp
	 * @covers Jet\DataModel_Definition_Property_Abstract::getName
	 */
	public function testGetName() {
		$this->assertEquals($this->property_name, $this->object->getName());
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_String::setUp
	 * @covers Jet\DataModel_Definition_Property_Abstract::getDescription
	 */
	public function testGetDescription() {
		$this->assertEquals($this->property_options['description'], $this->object->getDescription());
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_String::setUp
	 * @covers Jet\DataModel_Definition_Property_Abstract::getDoNotSerialize
	 */
	public function testGetDoNotSerialize() {
		$this->assertEquals($this->property_options['do_not_serialize'], $this->object->getDoNotSerialize());
		$this->assertEquals(false, $this->ID_object->getDoNotSerialize());
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_Abstract::getDataModelDefinition
	 */
	public function testGetDataModelDefinition() {
		$this->assertSame($this->data_model->getDataModelDefinition(), $this->object->getDataModelDefinition());
		$this->assertSame($this->data_model->getDataModelDefinition(), $this->ID_object->getDataModelDefinition());
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_Abstract::getIsArray
	 */
	public function testGetIsArray() {
		$this->assertFalse($this->object->getIsArray());
		$this->assertFalse($this->ID_object->getIsArray());
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_Abstract::getIsDataModel
	 */
	public function testGetIsDataModel() {
		$this->assertFalse($this->object->getIsDataModel());
		$this->assertFalse($this->ID_object->getIsDataModel());
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_String::setUp
	 * @covers Jet\DataModel_Definition_Property_Abstract::getIsID
	 */
	public function testGetIsID() {
		$this->assertFalse( $this->object->getIsID() );
		$this->assertTrue( $this->ID_object->getIsID() );
	}


	/**
	 * @covers Jet\DataModel_Definition_Property_String::setUp
	 * @covers Jet\DataModel_Definition_Property_Abstract::getIsRequired
	 */
	public function testGetIsRequired() {
		$this->assertEquals($this->property_options['is_required'], $this->object->getIsRequired());
		$this->assertEquals($this->ID_property_options['is_required'], $this->ID_object->getIsRequired());
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_String::setUp
	 * @covers Jet\DataModel_Definition_Property_Abstract::getDefaultValue
	 */
	public function testGetDefaultValue() {
		$this->assertEquals($this->property_options['default_value'], $this->object->getDefaultValue());
		$this->assertEquals($this->ID_property_options['default_value'], $this->ID_object->getDefaultValue());
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_String::setUp
	 * @covers Jet\DataModel_Definition_Property_Abstract::getBackendOptions
	 */
	public function testGetBackendOptions() {
		$this->assertEquals($this->property_options['backend_options']['test'], $this->object->getBackendOptions('test'));
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_String::setUp
	 * @covers Jet\DataModel_Definition_Property_Abstract::getValidationMethodName
	 */
	public function testGetValidationMethodName() {
		$this->assertEquals(
			$this->property_options['validation_method_name'],
			$this->object->getValidationMethodName()
		);
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_String::setUp
	 * @covers Jet\DataModel_Definition_Property_Abstract::getListOfValidOptions
	 */
	public function testGetListOfValidOptions() {
		$this->assertEquals(
			$this->property_options['list_of_valid_options'],
			$this->object->getListOfValidOptions()
		);
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_String::setUp
	 * @covers Jet\DataModel_Definition_Property_Abstract::getErrorMessage
	 */
	public function testGetErrorMessage() {
		$this->assertEquals( $this->property_options['error_messages']['error_1'], $this->object->getErrorMessage('error_1') );
		$this->assertEquals( $this->property_options['error_messages']['error_3'], $this->object->getErrorMessage('error_3') );
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_String::setUp
	 * @covers Jet\DataModel_Definition_Property_Abstract::getFormFieldType
	 */
	public function testGetFormFieldType() {
		$this->assertEquals('Input', $this->object->getFormFieldType() );
		$this->assertEquals('Input', $this->ID_object->getFormFieldType() );
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_String::setUp
	 * @covers Jet\DataModel_Definition_Property_Abstract::getFormFieldOptions
	 */
	public function testGetFormFieldOptions() {

		$this->assertEquals(
			$this->property_options['form_field_options'],
			$this->object->getFormFieldOptions()
		);
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_String::setUp
	 * @covers Jet\DataModel_Definition_Property_Abstract::getFormFieldLabel
	 */
	public function testGetFormFieldLabel() {
		$this->assertEquals(
			$this->property_options['form_field_label'],
			$this->object->getFormFieldLabel()
		);
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_String::setUp
	 * @covers Jet\DataModel_Definition_Property_Abstract::getFormFieldErrorMessages
	 */
	public function testGetFormFieldErrorMessages() {
		$this->assertEquals(
			$this->property_options['form_field_error_messages'],
			$this->object->getFormFieldErrorMessages()
		);
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_String::setUp
	 * @covers Jet\DataModel_Definition_Property_Abstract::getFormField
	 */
	public function testGetFormField() {
		$field = new Form_Field_Input('');

		$field->__test_set_state(array(
			'_name' => $this->property_name,
			'_value_raw' => $this->property_options['default_value'],
			'_value' => $this->property_options['default_value'],
			'_has_value' => false,
			'_is_valid' => false,
			'_last_error' => '',
			'_last_error_message' => '',
			'default_value' => $this->property_options['default_value'],
			'label' => $this->property_options['form_field_label'],
			'is_required' => true,
			'validation_regexp' => $this->property_options['validation_regexp'],
			'catch_data_callback' => null,
			'validate_data_callback' => null,
			'select_options' =>
			array (
			),
		));

		$this->assertEquals($field, $this->object->getFormField());


		$field = new Form_Field_Hidden('');

		$field->__test_set_state(array(
			'_name' => $this->ID_property_name,
			'_value_raw' => $this->ID_property_options['default_value'],
			'_value' => $this->ID_property_options['default_value'],
			'_has_value' => false,
			'_is_valid' => false,
			'_last_error' => '',
			'_last_error_message' => '',
			'default_value' => $this->ID_property_options['default_value'],
			'label' => '',
			'is_required' => false,
			'validation_regexp' => '',
			'catch_data_callback' => null,
			'validate_data_callback' => null,
			'select_options' =>
			array (
			),
		));

		$this->assertEquals($field, $this->ID_object->getFormField());

	}

	/**
	 * @covers Jet\DataModel_Definition_Property_String::setUp
	 * @covers Jet\DataModel_Definition_Property_Abstract::getTechnicalDescription
	 */
	public function testGetTechnicalDescription() {

		$this->assertEquals(
			'Type: String, max length: 123, required: yes, default value: default value, validation regexp: /^([a-z0-9]{1,10})$/'.JET_EOL.JET_EOL.'Description',
			$this->object->getTechnicalDescription()
		);
		$this->assertEquals(
			'Type: String, max length: 50, required: no, is ID, default value: ID default value'.JET_EOL.JET_EOL.'ID Description',
			$this->ID_object->getTechnicalDescription()
		);
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_Abstract::getValueForJsonSerialize
	 */
	public function testGetValueForJsonSerialize() {
		$value = 'value';

		$this->assertEquals($value, $this->object->getValueForJsonSerialize($value));
	}


	/**
	 * @covers Jet\DataModel_Definition_Property_String::setUp
	 * @covers Jet\DataModel_Definition_Property_String::getValidationRegexp
	 */
	public function testGetValidationRegexp() {
		$this->assertEquals($this->property_options['validation_regexp'], $this->object->getValidationRegexp());
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_String::setUp
	 * @covers Jet\DataModel_Definition_Property_String::getMaxLen
	 */
	public function testGetMaxLen() {
		$this->assertEquals($this->property_options['max_len'], $this->object->getMaxLen());
	}


	/**
	 * @covers Jet\DataModel_Definition_Property_String::checkValueType
	 */
	public function testCheckValueType() {
		$value = 123;
		$this->object->checkValueType($value);
		$this->assertSame('123', $value);
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_Abstract::validateProperties
	 * @covers Jet\DataModel_Definition_Property_Abstract::_validateProperties_test_required
	 */
	public function testValidatePropertiesFailedEmpty() {
		$value = '';
		$errors = array();

		$this->assertFalse($this->object->validateProperties($value, $errors));

		$this->assertArrayHasKey(0, $errors);
		/**
		 * @var DataModel_Validation_Error $error
		 */
		$error = $errors[0];

		$this->assertEquals(DataModel_Validation_Error::CODE_REQUIRED, $error->getCode());
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_Abstract::validateProperties
	 * @covers Jet\DataModel_Definition_Property_Abstract::_validateProperties_test_validOptions
	 */
	public function testValidatePropertiesFailedInvalidValue() {
		$value = 'invalid value';
		$errors = array();

		$this->assertFalse($this->object->validateProperties($value, $errors));

		$this->assertArrayHasKey(0, $errors);
		/**
		 * @var DataModel_Validation_Error $error
		 */
		$error = $errors[0];

		$this->assertEquals(DataModel_Validation_Error::CODE_INVALID_VALUE, $error->getCode());
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_Abstract::validateProperties
	 * @covers Jet\DataModel_Definition_Property_Abstract::_validateProperties_test_value
	 */
	public function testValidatePropertiesFailedInvaliudFormat() {
		$value = '_#invalid';
		$errors = array();

		$this->assertFalse( $this->object->validateProperties($value, $errors) );

		$this->assertArrayHasKey(0, $errors);
		/**
		 * @var DataModel_Validation_Error $error
		 */
		$error = $errors[0];

		$this->assertEquals(DataModel_Validation_Error::CODE_INVALID_FORMAT, $error->getCode());
	}


	/**
	 * @covers Jet\DataModel_Definition_Property_Abstract::validateProperties
	 */
	public function testValidateProperties() {
		$value = 'option1';
		$errors = array();
		$this->assertTrue( $this->object->validateProperties($value, $errors) );
	}


	/**
	 * @covers Jet\DataModel_Definition_Property_Abstract::cloneProperty
	 */
	public function testCloneProperty() {
		$class_name = __NAMESPACE__.'\\'.$this->property_class_name;
		/**
		 * @var DataModel_Definition_Property_String $dolly
		 */
		$dolly = new $class_name( $this->data_model->getDataModelDefinition(), 'Dolly', array() );

		DataModel_Definition_Property_Abstract::cloneProperty($this->object, $dolly);

		$this->assertEquals( $this->object->getListOfValidOptions(), $dolly->getListOfValidOptions() );
		$this->assertEquals( $this->object->getDefaultValue(), $dolly->getDefaultValue() );
		$this->assertEquals( $this->object->getDescription(), $dolly->getDescription() );
		$this->assertEquals( $this->object->getBackendOptions( 'test' ), $dolly->getBackendOptions( 'test' ) );
	}

}
