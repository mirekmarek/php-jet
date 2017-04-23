<?php
/**
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package DataModel
 */
namespace Jet;

/** @noinspection PhpIncludeInspection */
require_once '_mock/Jet/DataModel/Definition/DataModelTestMock.php';

class DataModel_Definition_Property_FloatTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DataModel_Definition_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Property_Float
	 */
	protected $object;

	protected $property_class_name = 'DataModel_Definition_Property_Float';

	protected $property_name = 'float_property';

	protected $property_options = [];

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$class_name = __NAMESPACE__.'\\'.$this->property_class_name;

		$this->data_model = new DataModel_Definition_DataModelTestMock();

		$this->property_options = $this->data_model->_test_get_property_options($this->property_name);

		$this->object = new $class_name( get_class($this->data_model), $this->property_name, $this->property_options );
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers DataModel_Definition_Property_Float::checkValueType
	 */
	public function testCheckValueType() {
		$value = '3.14';
		$this->object->checkValueType($value);

		$this->assertSame(3.14, $value);
	}

	/**
	 * @covers DataModel_Definition_Property_Float::setUp
	 * @covers getFormFieldMinValue::getFormfieldMinValue
	 */
	public function testGetMinValue() {
		$this->assertSame($this->property_options['form_field_min_value'], $this->object->getFormFieldMinValue());
	}

	/**
	 * @covers DataModel_Definition_Property_Float::setUp
	 * @covers getFormFieldMaxValue::getFormfieldMaxValue
	 */
	public function testGetMaxValue() {
		$this->assertSame($this->property_options['form_field_max_value'], $this->object->getFormFieldMaxValue());
	}

	/**
	 * @covers DataModel_Definition_Property_Float::setUp
	 * @covers DataModel_Definition_Property_Float::getFormFieldOptions
	 */
	public function testGetFormFieldOptions() {
		$options = $this->object->getFormFieldOptions();
		$this->assertArrayHasKey('min_value', $options);
		$this->assertArrayHasKey('max_value', $options);

		$this->assertSame($this->property_options['form_field_min_value'], $options['min_value']);
		$this->assertSame($this->property_options['form_field_max_value'], $options['max_value']);
	}

	/**
	 * @covers DataModel_Definition_Property_Float::getTechnicalDescription
	 */
	public function testGetTechnicalDescription() {

		$this->assertEquals(
				'Type: Float , required: yes, default value: 2, min. value: 1.23, max. value: 4.56'.JET_EOL.JET_EOL.'Description',
				$this->object->getTechnicalDescription()
			);
	}

	/**
	 * @covers DataModel_Definition_Property_Float::setUp
	 * @covers DataModel_Definition_Property_Abstract::createFormField
	 */
	public function testGetFormField() {
		$field = new Form_Field_Float('');

		/**
		 * @var \JetTest\BaseObject $field
		 */
		$field->__test_set_state([
			'_name' => $this->property_name,
			'_value_raw' => $this->property_options['default_value'],
			'_value' => '2',
			'default_value' => $this->property_options['default_value'],
			'is_required' => $this->property_options['form_field_is_required'],
			'min_value' => $this->property_options['form_field_min_value'],
			'max_value' => $this->property_options['form_field_max_value'],
			'validate_data_callback' => null,
			'select_options' =>
			[
			],
		]);

		$this->assertEquals($field, $this->object->createFormField( $this->property_options['default_value'] ));

	}

}
