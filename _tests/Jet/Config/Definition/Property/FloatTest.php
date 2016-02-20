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

class Config_Definition_Property_FloatTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var Config_Definition_Property_Int
	 */
	protected $object;

	protected $property_name = 'FloatTest';

	protected $property_type = Config::TYPE_FLOAT;

	protected $property_class_name = 'Config_Definition_Property_Float';

	protected $property_default_form_field_type = Form::TYPE_FLOAT;

	protected $default_value = 10.11;

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
	 * @covers Jet\Config_Definition_Property_Int::setMinValue
	 * @covers Jet\Config_Definition_Property_Int::getMinValue
	 */
	public function testSetGetMinValue() {
		$this->object->setMinValue( 10.1 );
		$this->assertEquals(10.1, $this->object->getMinValue());
	}


	/**
	 * @covers Jet\Config_Definition_Property_Int::setMaxValue
	 */
	public function testSetGetMaxValue() {
		$this->object->setMaxValue( 100.1 );
		$this->assertEquals(100.1, $this->object->getMaxValue());
	}


	/**
	 * @covers Jet\Config_Definition_Property_Int::checkValueType
	 */
	public function testCheckValueType() {

		$value = '123.4';

		$this->object->checkValueType( $value );

		$this->assertSame(123.4, $value);
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::checkValue
	 *
	 * @expectedException \Jet\Config_Exception
	 * @expectedExceptionCode \Jet\Config_Exception::CODE_CONFIG_CHECK_ERROR
	 */
	public function testCheckValueFailedUnder() {
		$this->object->setMinValue(10.1);
		$this->object->setMaxValue(100.1);

		$value = 1;
		$this->object->checkValue( $value );
	}


	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::checkValue
	 *
	 * @expectedException \Jet\Config_Exception
	 * @expectedExceptionCode \Jet\Config_Exception::CODE_CONFIG_CHECK_ERROR
	 */
	public function testCheckValueFailedAbove() {
		$this->object->setMinValue(10.1);
		$this->object->setMaxValue(100.1);

		$value = 110;
		$this->object->checkValue( $value );
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::checkValue
	 *
	 * @expectedException \Jet\Config_Exception
	 * @expectedExceptionCode \Jet\Config_Exception::CODE_CONFIG_CHECK_ERROR
	 */
	public function testCheckValueFailedEmpty() {
		$value = null;

		$this->object->checkValue( $value );
	}


	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::checkValue
	 *
	 */
	public function testCheckValue() {
		$this->object->setMinValue(10.1);
		$this->object->setMaxValue(100.1);

		$value = 50;
		$this->object->checkValue( $value );
	}

	/**
	 * @covers Jet\Config_Definition_Property_Int::getTechnicalDescription
	 */
	public function testGetTechnicalDescription() {
		$this->object->setMinValue(10.1);
		$this->object->setMaxValue(100.1);

		$this->assertEquals(
			'Type: Float , required: yes, default value: 10.11, min. value: 10.1, max. value: 100.1'.JET_EOL.JET_EOL.'Description',
			$this->object->getTechnicalDescription()
		);
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::createFormField
	 */
	public function testGetFormField() {
		$this->object->setMinValue(10.1);
		$this->object->setMaxValue(100.1);

		$field = new Form_Field_Float('');

		/**
		 * @var \JetTest\Object $field
		 */
		$field->__test_set_state([
			'_type' => 'Float',
			'_value' => 10.11,
			'_value_raw' => 10.11,
			'_name' => 'FloatTest',
			'min_value' => 10.1,
			'max_value' => 100.1,
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
