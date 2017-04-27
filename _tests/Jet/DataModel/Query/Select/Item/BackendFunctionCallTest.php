<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/** @noinspection PhpIncludeInspection */
require_once '_mock/Jet/DataModel/Query/DataModelTestMock.php';

/**
 *
 */
class DataModel_Query_Select_Item_BackendFunctionCallTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var DataModel_Query_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $properties = [];

	/**
	 * @var DataModel_Query_Select_Item_BackendFunctionCall
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {

		$this->data_model = new DataModel_Query_DataModelTestMock();

		$this->properties = $this->data_model->getDataModelDefinition()->getProperties();

		$this->object = new DataModel_Query_Select_Item_BackendFunctionCall(
				[
					$this->properties['float_property'],
					$this->properties['int_property']
				],
				'SUM(%float_property%)+%int_property%'
		);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}


	/**
	 * @covers \Jet\DataModel_Query_Select_Item_BackendFunctionCall::__construct
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
	 */
	public function testConstructFailed1() {
		$this->data_model = new DataModel_Query_DataModelTestMock();

		$this->properties = $this->data_model->getDataModelDefinition()->getProperties();

		$this->object = new DataModel_Query_Select_Item_BackendFunctionCall(
			[
				$this->properties['float_property'],
				$this->properties['int_property']
			],
			'SUM(%float_property%)'
		);
	}


	/**
	 * @covers \Jet\DataModel_Query_Select_Item_BackendFunctionCall::__construct
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
	 */
	public function testConstructFailed2() {
		$this->data_model = new DataModel_Query_DataModelTestMock();

		$this->properties = $this->data_model->getDataModelDefinition()->getProperties();

		$this->object = new DataModel_Query_Select_Item_BackendFunctionCall(
			[
				'hoax'
			],
			'SUM(%float_property%)'
		);
	}



	/**
	 * @covers \Jet\DataModel_Query_Select_Item_BackendFunctionCall::getProperties
	 */
	public function testGetProperties() {
		$this->assertEquals(
			[
				$this->properties['float_property'],
				$this->properties['int_property']
			],
			$this->object->getProperties()
		);
	}

	/**
	 * @covers \Jet\DataModel_Query_Select_Item_BackendFunctionCall::getBackendFunction
	 */
	public function testGetBackendFunction() {
		$this->assertEquals( 'SUM(%float_property%)+%int_property%', $this->object->getBackendFunction() );
	}

	/**
	 *
	 * @covers \Jet\DataModel_Query_Select_Item_BackendFunctionCall::toString
	 */
	public function testToString() {

		$fc = $this->object->toString( function( DataModel_Definition_Property_Abstract $property ) {
			return $this->_getColumnName($property);
		} );

		$this->assertEquals('SUM(`data_model_test_mock`.`float_property`)+`data_model_test_mock`.`int_property`', $fc);
	}

	protected function _getColumnName( DataModel_Definition_Property_Abstract $property ) {
		$table_name = $property->getDataModelDefinition()->getModelName();
		$column_name = $property->getName();

		return '`'.$table_name.'`.`'.$column_name.'`';
	}
}
