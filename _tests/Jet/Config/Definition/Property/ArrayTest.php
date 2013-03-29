<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package Config
 */
namespace Jet;

require_once "_mock/Jet/Config/ConfigTestMock.php";

class Config_Definition_Property_ArrayTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var Config_Definition_Property_Array
	 */
	protected $object;

	protected $property_name = "ArrayTest";

	protected $property_type = Config::TYPE_BOOL;

	protected $property_class_name = "Config_Definition_Property_Array";

	protected $property_default_form_field_type = "MultiSelect";

	protected $default_value = array("val1","val2");

	/**
	 * @var ConfigTestMock
	 */
	protected $config;


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
	 * @covers Jet\Config_Definition_Property_Array::checkValueType
	 */
	public function testCheckValueType() {

		$value = "notarray";

		$this->object->checkValueType( $value );

		$this->assertSame(array(), $value);
	}


	/**
	 * @covers Jet\Config_Definition_Property_Int::getTechnicalDescription
	 */
	public function testGetTechnicalDescription() {

		$this->assertEquals(
			"Type: Array, required: yes, default value: val1,val2\n\nDescription",
			$this->object->getTechnicalDescription()
		);
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::checkValue
	 *
	 */
	public function testCheckValue() {
		$value = array("testvalue1", "testvalue2");

		$this->assertTrue( $this->object->checkValue( $value ) );
	}

	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::checkValue
	 *
	 * @expectedException \Jet\Config_Exception
	 * @expectedExceptionCode \Jet\Config_Exception::CODE_CONFIG_CHECK_ERROR
	 */
	public function testCheckValueFailedEmpty() {
		$value = array();

		$this->object->checkValue( $value );
	}


	/**
	 * @covers Jet\Config_Definition_Property_Abstract::setUp
	 * @covers Jet\Config_Definition_Property_Abstract::getFormField
	 */
	public function testGetFormField() {
		$field = new Form_Field_MultiSelect("");

		$field->__test_set_state(array(
			'_type' => 'MultiSelect',
			'_name' => 'ArrayTest',
			'_value' => array("val1","val2"),
			'_value_raw' => array("val1","val2"),
			'default_value' => $this->default_value,
			'label' => 'Form field label',
			'is_required' => true,
			'select_options' =>
			array (
			),
		));

		$this->assertEquals($field, $this->object->getFormField());
	}


}
