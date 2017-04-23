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

class DataModel_Definition_Property_DateTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DataModel_Definition_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Property_Date
	 */
	protected $object;

	protected $property_class_name = 'DataModel_Definition_Property_Date';

	protected $property_name = 'date_property';

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
	 * @covers DataModel_Definition_Property_Date::checkValueType
	 */
	public function testCheckValueType() {
		$date = '1980-08-13';
		$this->object->checkValueType($date);

		$date_object = new Data_DateTime('1980-08-13');
		$this->assertEquals($date_object, $date);
	}

	/**
	 * @covers DataModel_Definition_Property_Date::getValueForJsonSerialize
	 */
	public function testGetValueForJsonSerialize() {
		$date_object = new Data_DateTime('1980-08-13');
		$value = $this->object->getValueForJsonSerialize($this->data_model, $date_object);
		$this->assertEquals($date_object->toString(), $value);
	}
}
