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
class DataModel_Query_Where_ExpressionTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DataModel_Query_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $properties = [];

	/**
	 * @var DataModel_Query_Where_Expression
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->data_model = new DataModel_Query_DataModelTestMock();

		$this->properties = $this->data_model->getDataModelDefinition()->getProperties();

		$this->object = new DataModel_Query_Where_Expression(
			$this->properties['string_property'],
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
	 * @covers \Jet\DataModel_Query_Where_Expression::__construct
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
	 */
	public function testConstructFailed() {
		$this->object = new DataModel_Query_Where_Expression(
			$this->properties['string_property'],
			'imaginary_operator',
			'test'
		);

	}

	/**
	 * @covers \Jet\DataModel_Query_Where_Expression::getProperty
	 */
	public function testGetProperty() {
		$this->assertEquals( $this->properties['string_property'], $this->object->getProperty() );
	}

	/**
	 * @covers \Jet\DataModel_Query_Where_Expression::getOperator
	 */
	public function testGetOperator() {
		$this->assertEquals( DataModel_Query::O_NOT_EQUAL, $this->object->getOperator() );
	}

	/**
	 * @covers \Jet\DataModel_Query_Where_Expression::getValue
	 */
	public function testGetValue() {
		$this->assertEquals( 'test', $this->object->getValue() );
	}


	/**
	 * @covers \Jet\DataModel_Query_Where_Expression::toString
	 * @covers \Jet\DataModel_Query_Where_Expression::__toString
	 */
	public function testToString() {
		$this->assertEquals('data_model_test_mock::string_property != \'test\'', $this->object->toString() );
	}
}
