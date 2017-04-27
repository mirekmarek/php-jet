<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/** @noinspection PhpIncludeInspection */
require_once '_mock/Jet/Config/ConfigTestMock.php';

class Config_Definition_Property_ArrayTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var Config_Definition_Property_Array
	 */
	protected $object;

	protected $property_name = 'ArrayTest';

	protected $property_type = Config::TYPE_BOOL;

	protected $property_class_name = 'Config_Definition_Property_Array';

	protected $property_default_form_field_type = Form::TYPE_MULTI_SELECT;

	protected $default_value = ['val1','val2'];

	/**
	 * @var ConfigTestMock
	 */
	protected $config;


	protected $property_options = [
		'description' => 'Description',
		'default_value' => '',
		'is_required' => true,
		'error_message' => 'Error Message',
		'label' => 'Label',
		'form_field_label' => 'Form field label'
	];


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {

		$class_name = __NAMESPACE__.'\\'.$this->property_class_name;
		$this->property_options['default_value'] = $this->default_value;

		$this->config = new ConfigTestMock('test');
		$this->object = new $class_name( $this->config, $this->property_name, $this->property_options  );
	}

	/**
	 * @covers \Jet\Config_Definition_Property_Array::checkValueType
	 */
	public function testCheckValueType() {

		$value = 'not_array';

		$this->object->checkValueType( $value );

		$this->assertSame([], $value);
	}


	/**
	 * @covers \Jet\Config_Definition_Property_Int::getTechnicalDescription
	 */
	public function testGetTechnicalDescription() {

		$this->assertEquals(
			'Type: Array, required: yes, default value: val1,val2'.JET_EOL.JET_EOL.'Description',
			$this->object->getTechnicalDescription()
		);
	}

	/**
	 * @covers \Jet\Config_Definition_Property_Abstract::setUp
	 * @covers \Jet\Config_Definition_Property_Abstract::checkValue
	 *
	 */
	public function testCheckValue() {
		/** @noinspection SpellCheckingInspection */
		$value = ['testvalue1', 'testvalue2'];

		$this->assertTrue( $this->object->checkValue( $value ) );
	}

	/**
	 * @covers \Jet\Config_Definition_Property_Abstract::setUp
	 * @covers \Jet\Config_Definition_Property_Abstract::checkValue
	 *
	 * @expectedException \Jet\Config_Exception
	 * @expectedExceptionCode \Jet\Config_Exception::CODE_CONFIG_CHECK_ERROR
	 */
	public function testCheckValueFailedEmpty() {
		$value = [];

		$this->object->checkValue( $value );
	}


	/**
	 * @covers \Jet\Config_Definition_Property_Abstract::setUp
	 * @covers \Jet\Config_Definition_Property_Abstract::createFormField
	 */
	public function testGetFormField() {
		$field = new Form_Field_MultiSelect('');

		/**
		 * @var \JetTest\BaseObject $field
		 */
		$field->__test_set_state([
			'_type' => 'MultiSelect',
			'_name' => 'ArrayTest',
			'_value' => ['val1','val2'],
			'_value_raw' => ['val1','val2'],
			'default_value' => $this->default_value,
			'label' => 'Form field label',
			'is_required' => true,
			'select_options' =>
			[
			],
		]);

		$property = &$this->default_value;

		$this->assertEquals($field, $this->object->createFormField($property));
	}


}
