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

require_once '_mock/Jet/DataModel/Query/DataModelTestMock.php';

class DataModel_Query_Having_ExpressionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var DataModel_Query_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $properties = array();

	/**
	 * @var DataModel_Query_Having_Expression
	 */
	protected $object;

	/**
	 * @var DataModel_Query_Select_Item_BackendFunctionCall
	 */
	protected $backend_function_call;

	/**
	 * @var DataModel_Query_Select_Item
	 */
	protected $select_item;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->data_model = new DataModel_Query_DataModelTestMock();

		$this->properties = $this->data_model->getDataModelDefinition()->getProperties();

		$this->backend_function_call = new DataModel_Query_Select_Item_BackendFunctionCall(
			array(
				$this->properties['float_property'],
				$this->properties['int_property']
			),
			'SUM(%float_property%)+%int_property%'
		);

		$this->select_item = new DataModel_Query_Select_Item( $this->backend_function_call, 'my_function_call' );

		$this->object = new DataModel_Query_Having_Expression(
			$this->select_item,
			DataModel_Query::O_NOT_EQUAL,
			'test'
		);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers Jet\DataModel_Query_Having_Expression::getProperty
	 */
	public function testGetProperty() {
		$this->assertEquals($this->select_item, $this->object->getProperty());
	}

	/**
	 * @covers Jet\DataModel_Query_Having_Expression::toString
	 */
	public function testToString() {
		$this->assertEquals('SUM(data_model_test_mock::float_property)+data_model_test_mock::int_property != \'test\'', $this->object->toString());
	}
}
