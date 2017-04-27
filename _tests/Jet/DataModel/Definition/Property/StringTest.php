<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/** @noinspection PhpIncludeInspection */
require_once '_mock/Jet/DataModel/Definition/DataModelTestMock.php';

/**
 *
 */
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
	protected $id_object;

	/**
	 * @var DataModel_Definition_Property_String
	 */
	protected $id_model_related;

	protected $property_type = DataModel::TYPE_STRING;

	protected $property_class_name = 'DataModel_Definition_Property_String';

	protected $property_name = 'string_property';

	protected $id_property_name = 'id_property';

	protected $property_options = [];

	protected $id_property_options = [];

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {

		$class_name = __NAMESPACE__.'\\'.$this->property_class_name;

		$this->data_model = new DataModel_Definition_DataModelTestMock();

		$this->property_options = $this->data_model->_test_get_property_options($this->property_name);
		$this->id_property_options = $this->data_model->_test_get_property_options($this->id_property_name);

		$this->object = new $class_name( get_class($this->data_model), $this->property_name, $this->property_options );
		$this->id_object = new $class_name( get_class($this->data_model), $this->id_property_name, $this->id_property_options );
		$this->id_model_related = new $class_name( get_class($this->data_model), 'id_related', $this->id_property_options );
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}


	/**
	 * @covers \Jet\DataModel_Definition_Property_Abstract::setUpRelation
	 * @covers \Jet\DataModel_Definition_Property_Abstract::getRelatedToPropertyName
	 */
	public function testSetUpRelation() {
		$this->assertNull( $this->id_object->getRelatedToPropertyName() );
		$this->id_object->setUpRelation(
					$this->id_model_related->getDataModelClassName(),
					$this->id_model_related->getName()
		);
		$this->assertSame($this->id_model_related->getName(), $this->id_object->getRelatedToPropertyName());
	}

	/**
	 * @covers \Jet\DataModel_Definition_Property_String::setUp
	 * @covers \Jet\DataModel_Definition_Property_Abstract::toString
	 * @covers \Jet\DataModel_Definition_Property_Abstract::__toString
	 */
	public function testToString() {
		$this->assertEquals( 'Jet\DataModel_Definition_DataModelTestMock::'.$this->property_name, (string)$this->object );
	}

	/**
	 * @covers \Jet\DataModel_Definition_Property_Abstract::getType
	 */
	public function testGetType() {
		$this->assertEquals($this->property_type, $this->object->getType());
	}

	/**
	 * @covers \Jet\DataModel_Definition_Property_String::setUp
	 * @covers \Jet\DataModel_Definition_Property_Abstract::getName
	 */
	public function testGetName() {
		$this->assertEquals($this->property_name, $this->object->getName());
	}

	/**
	 * @covers \Jet\DataModel_Definition_Property_String::setUp
	 * @covers \Jet\DataModel_Definition_Property_Abstract::getDescription
	 */
	public function testGetDescription() {
		$this->assertEquals($this->property_options['description'], $this->object->getDescription());
	}

	/**
	 * @covers \Jet\DataModel_Definition_Property_String::setUp
	 * @covers \Jet\DataModel_Definition_Property_Abstract::doNotExport
	 */
	public function testGetDoNotExport() {
		$this->assertEquals($this->property_options['do_not_export'], $this->object->doNotExport());
		$this->assertEquals(false, $this->id_object->doNotExport());
	}

	/**
	 * @covers \Jet\DataModel_Definition_Property_Abstract::getDataModelDefinition
	 */
	public function testGetDataModelDefinition() {
		$this->assertSame($this->data_model->getDataModelDefinition(), $this->object->getDataModelDefinition());
		$this->assertSame($this->data_model->getDataModelDefinition(), $this->id_object->getDataModelDefinition());
	}


	/**
	 * @covers \Jet\DataModel_Definition_Property_String::setUp
	 * @covers \Jet\DataModel_Definition_Property_Abstract::getIsId
	 */
	public function testGetIsId() {
		$this->assertFalse( $this->object->getIsId() );
		$this->assertTrue( $this->id_object->getIsId() );
	}


	/**
	 * @covers \Jet\DataModel_Definition_Property_String::setUp
	 * @covers \Jet\DataModel_Definition_Property_String::getFormFieldIsRequired
	 */
	public function testGetIsRequired() {
		$this->assertEquals($this->property_options['form_field_is_required'], $this->object->getFormFieldIsRequired());
		$this->assertEquals($this->id_property_options['form_field_is_required'], $this->id_object->getFormFieldIsRequired());
	}

	/**
	 * @covers \Jet\DataModel_Definition_Property_String::setUp
	 * @covers \Jet\DataModel_Definition_Property_Abstract::getDefaultValue
	 */
	public function testGetDefaultValue() {
		$this->assertEquals($this->property_options['default_value'], $this->object->getDefaultValue() );
		$this->assertEquals($this->id_property_options['default_value'], $this->id_object->getDefaultValue() );
	}

	/**
	 * @covers \Jet\DataModel_Definition_Property_String::setUp
	 * @covers \Jet\DataModel_Definition_Property_Abstract::getBackendOptions
	 */
	public function testGetBackendOptions() {
		$this->assertEquals($this->property_options['backend_options']['test'], $this->object->getBackendOptions('test'));
	}


	/**
	 * @covers \Jet\DataModel_Definition_Property_String::setUp
	 * @covers \Jet\DataModel_Definition_Property_Abstract::getFormFieldType
	 */
	public function testGetFormFieldType() {
		$this->assertEquals(Form::TYPE_INPUT, $this->object->getFormFieldType() );
		$this->assertEquals(Form::TYPE_HIDDEN, $this->id_object->getFormFieldType() );
	}

	/**
	 * @covers \Jet\DataModel_Definition_Property_String::setUp
	 * @covers \Jet\DataModel_Definition_Property_Abstract::getFormFieldOptions
	 */
	public function testGetFormFieldOptions() {

		$this->assertEquals(
			$this->property_options['form_field_options'],
			$this->object->getFormFieldOptions()
		);
	}

	/**
	 * @covers \Jet\DataModel_Definition_Property_String::setUp
	 * @covers \Jet\DataModel_Definition_Property_Abstract::getFormFieldLabel
	 */
	public function testGetFormFieldLabel() {
		$this->assertEquals(
			$this->property_options['form_field_label'],
			$this->object->getFormFieldLabel()
		);
	}

	/**
	 * @covers \Jet\DataModel_Definition_Property_String::setUp
	 * @covers \Jet\DataModel_Definition_Property_Abstract::getFormFieldErrorMessages
	 */
	public function testGetFormFieldErrorMessages() {
		$this->assertEquals(
			$this->property_options['form_field_error_messages'],
			$this->object->getFormFieldErrorMessages()
		);
	}

	/**
	 * @covers \Jet\DataModel_Definition_Property_String::setUp
	 * @covers \Jet\DataModel_Definition_Property_Abstract::createFormField
	 */
	public function testGetFormField() {
		$field = new Form_Field_Input('');

		/**
		 * @var \JetTest\BaseObject $field
		 */
		$field->__test_set_state([
			'_name' => $this->property_name,
			'_value_raw' => $this->property_options['default_value'],
			'_value' => $this->property_options['default_value'],
			'_has_value' => false,
			'is_valid' => false,
			'last_error' => '',
			'last_error_message' => '',
			'default_value' => $this->property_options['default_value'],
			'label' => $this->property_options['form_field_label'],
			'is_required' => true,
			'validation_regexp' => $this->property_options['form_field_validation_regexp'],
			'validate_data_callback' => null,
			'error_messages' => [
				Form_Field_Abstract::ERROR_CODE_EMPTY => 'Is empty',
				Form_Field_Abstract::ERROR_CODE_INVALID_FORMAT => 'Invalid format',
			],
			'select_options' =>
			[
			],
		]);

		$this->assertEquals($field, $this->object->createFormField($this->property_options['default_value']));


		$field = new Form_Field_Hidden('');

		/**
		 * @var \JetTest\BaseObject $field
		 */
		$field->__test_set_state([
			'_name' => $this->id_property_name,
			'_value_raw' => $this->id_property_options['default_value'],
			'_value' => $this->id_property_options['default_value'],
			'_has_value' => false,
			'is_valid' => false,
			'last_error' => '',
			'last_error_message' => '',
			'default_value' => $this->id_property_options['default_value'],
			'label' => '',
			'is_required' => false,
			'validation_regexp' => null,
			'validate_data_callback' => null,
			'select_options' =>
			[
			],
		]);

		$this->assertEquals($field, $this->id_object->createFormField($this->id_property_options['default_value']));

	}

	/**
	 * @covers \Jet\DataModel_Definition_Property_String::setUp
	 * @covers \Jet\DataModel_Definition_Property_Abstract::getTechnicalDescription
	 */
	public function testGetTechnicalDescription() {

		$this->assertEquals(
			'Type: String, max length: 123, required: yes, default value: default value, validation regexp: /^([a-z0-9]{1,10})$/'.JET_EOL.JET_EOL.'Description',
			$this->object->getTechnicalDescription()
		);
		$this->assertEquals(
			'Type: String, max length: 50, required: no, is ID, default value: Id default value'.JET_EOL.JET_EOL.'Id Description',
			$this->id_object->getTechnicalDescription()
		);
	}

	/**
	 * @covers \Jet\DataModel_Definition_Property_Abstract::getValueForJsonSerialize
	 */
	public function testGetValueForJsonSerialize() {
		$value = 'value';

		$this->assertEquals($value, $this->object->getValueForJsonSerialize($this->data_model, $value));
	}


	/**
	 * @covers \Jet\DataModel_Definition_Property_String::setUp
	 * @covers \Jet\DataModel_Definition_Property_String::getFormFieldValidationRegexp
	 */
	public function testGetValidationRegexp() {
		$this->assertEquals($this->property_options['form_field_validation_regexp'], $this->object->getFormFieldValidationRegexp());
	}

	/**
	 * @covers \Jet\DataModel_Definition_Property_String::setUp
	 * @covers \Jet\DataModel_Definition_Property_String::getMaxLen
	 */
	public function testGetMaxLen() {
		$this->assertEquals($this->property_options['max_len'], $this->object->getMaxLen());
	}


	/**
	 * @covers \Jet\DataModel_Definition_Property_String::checkValueType
	 */
	public function testCheckValueType() {
		$value = 123;
		$this->object->checkValueType($value);
		$this->assertSame('123', $value);
	}

}
