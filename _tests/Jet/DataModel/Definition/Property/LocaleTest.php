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

require_once "_mock/Jet/DataModel/Definition/DataModelTestMock.php";

class DataModel_Definition_Property_LocaleTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DataModel_Definition_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Property_Locale
	 */
	protected $object;

	protected $property_class_name = "DataModel_Definition_Property_Locale";

	protected $property_name = "locale_property";

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
	 * @covers Jet\DataModel_Definition_Property_Locale::checkValueType
	 */
	public function testCheckValueType() {
		$locale = "cs_CZ";
		$this->object->checkValueType($locale);

		$locale_object = new Locale("cs_CZ");
		$this->assertEquals($locale_object, $locale);
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_Locale::getValueForJsonSerialize
	 */
	public function testGetValueForJsonSerialize() {
		$locale_object = new Locale("cs_CZ");
		$value = $this->object->getValueForJsonSerialize($locale_object);
		$this->assertEquals($locale_object->toString(), $value);
	}
}
