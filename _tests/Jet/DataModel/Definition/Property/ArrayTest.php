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

/** @noinspection PhpIncludeInspection */
require_once '_mock/Jet/DataModel/Definition/DataModelTestMock.php';

class DataModel_Definition_Property_ArrayTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DataModel_Definition_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Property_Array
	 */
	protected $object;

	protected $property_class_name = 'DataModel_Definition_Property_Array';

	protected $property_name = 'array_property';

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
	 * @covers Jet\DataModel_Definition_Property_Array::setUp
	 * @expectedException \Jet\DataModel_Exception
	 * @expectedExceptionCode \Jet\DataModel_Exception::CODE_DEFINITION_NONSENSE
	 */
	public function testSetUpFailedIsID() {
		$class_name = __NAMESPACE__.'\\'.$this->property_class_name;

		$this->data_model = new DataModel_Definition_DataModelTestMock();

		$this->property_options['is_ID'] = true;

		$this->object = new $class_name( get_class($this->data_model), $this->property_name, $this->property_options );
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_Array::setUp
	 * @expectedException \Jet\DataModel_Exception
	 * @expectedExceptionCode \Jet\DataModel_Exception::CODE_DEFINITION_NONSENSE
	 */
	public function testSetUpFailedItemTypeIsDo() {
		$class_name = __NAMESPACE__.'\\'.$this->property_class_name;

		$this->data_model = new DataModel_Definition_DataModelTestMock();

		$this->property_options['item_type'] = DataModel::TYPE_DATA_MODEL;

		$this->object = new $class_name( get_class($this->data_model), $this->property_name, $this->property_options );
	}

}
