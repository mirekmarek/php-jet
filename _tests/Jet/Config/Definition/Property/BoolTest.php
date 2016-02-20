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

/** @noinspection PhpIncludeInspection */
require_once '_mock/Jet/Config/ConfigTestMock.php';

class Config_Definition_Property_BoolTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var Config_Definition_Property_Bool
	 */
	protected $object;

	protected $property_name = 'BoolTest';

	protected $property_type = Config::TYPE_BOOL;

	protected $property_class_name = 'Config_Definition_Property_Bool';

	protected $property_default_form_field_type = Form::TYPE_CHECKBOX;

	protected $default_value = true;


	protected $property_options = [
		'description' => 'Description',
		'default_value' => '',
		'is_required' => true,
		'error_message' => 'Error Message',
		'label' => 'Label',
		'form_field_label' => 'Form field label'
	];


	/**
	 * @var ConfigTestMock
	 */
	protected $config;

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
	 * @covers Jet\Config_Definition_Property_Bool::checkValueType
	 */
	public function testCheckValueType() {

		$value = 1;

		$this->object->checkValueType( $value );

		$this->assertSame(true, $value);
	}


	/**
	 * @covers Jet\Config_Definition_Property_Int::getTechnicalDescription
	 */
	public function testGetTechnicalDescription() {

		$this->assertEquals(
			'Type: Bool, required: yes, default value: 1'.JET_EOL.JET_EOL.'Description',
			$this->object->getTechnicalDescription()
		);
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::checkValue
	 *
	 */
	public function testCheckValue() {
		$value = true;

		$this->assertTrue( $this->object->checkValue( $value ) );
	}


	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::createFormField
	 */
	public function testGetFormField() {
		$field = new Form_Field_Checkbox('');

		/**
		 * @var \JetTest\Object $field
		 */
		$field->__test_set_state([
			'_type' => 'Checkbox',
			'_name' => 'BoolTest',
			'_value' => '1',
			'_value_raw' => true,
			'default_value' => $this->default_value,
			'label' => 'Form field label',
			'is_required' => true,
			'select_options' =>
			[
			],
		]);

		$property = &$this->default_value;


		$this->assertEquals($field, $this->object->createFormField( $property ));
	}


}
