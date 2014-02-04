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

class DataModel_Definition_Property_IDTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DataModel_Definition_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Property_ID
	 */
	protected $object;

	protected $property_class_name = 'DataModel_Definition_Property_ID';

	protected $property_name = 'IDTest';

	protected $property_options = array(
		'description' => 'Description',
		'default_value' => 'default value',
		'is_required' => true,
		'do_not_serialize' => true,


	);

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$class_name = __NAMESPACE__.'\\'.$this->property_class_name;

		$this->data_model = new DataModel_Definition_DataModelTestMock();

		$this->object = new $class_name( get_class($this->data_model), $this->property_name, $this->property_options );
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers Jet\DataModel_Definition_Property_ID::checkValueType
	 */
	public function testCheckValueType() {
		$value = 'do not change';
		$this->object->checkValueType($value);
		$this->assertEquals($value, $value);
	}
}
