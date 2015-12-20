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
require_once '_mock/Jet/DataModel/Query/DataModelTestMock.php';

class DataModel_Query_HavingTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DataModel_Query_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $properties = [];

	/**
	 * @var DataModel_Query_Having
	 */
	protected $object;

	/**
	 * @var DataModel_Query
	 */
	protected $query;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->data_model = new DataModel_Query_DataModelTestMock();

		$this->properties = $this->data_model->getDataModelDefinition()->getProperties();

		$this->query = new DataModel_Query($this->data_model->getDataModelDefinition());
		$this->query->setSelect([
			'int_property' => 'this.int_property',
			'string_property' => 'this.string_property',
			'my_ID' => 'this.ID_property',
			'my_value' => [
				['this.int_property'],
				'MAX(%int_property%)'
			],
		]);

		$this->object = new DataModel_Query_Having($this->query, [
			'my_value <=' => 10,
			'AND',
			'int_property =' => 1234,
			'AND',
			'string_property !=' => 'test',
			'OR',
			[
				'my_ID *' => 'test%',
				'AND',
				'int_property' => 54321
			]
		]);

		$query = new DataModel_Query($this->data_model->getDataModelDefinition());
		$query->setSelect([
			'float_property' => 'this.float_property'
		]);


		$query = new DataModel_Query_Having($query, [
			'float_property =' => 3.14
		]);

		$this->object->attach($query);

	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}


	/**
	 * @covers Jet\DataModel_Query_Where::__construct
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_NONSENSE
	 */
	public function testConstructFailed1() {
		$this->object = new DataModel_Query_Having($this->query, [
			'int_property =' => 1234,
			'string_property !=' => 'test',
			'OR',
			[
				'my_ID *' => 'test%',
				'AND',
				'int_property' => 54321
			]
		]);
	}

	/**
	 * @covers Jet\DataModel_Query_Where::__construct
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_NONSENSE
	 */
	public function testConstructFailed2() {
		$this->object = new DataModel_Query_Having($this->query, [
			'int_property =' => 1234,
			'AND',
			'AND',
			'string_property !=' => 'test',
			'OR',
			[
				'my_ID *' => 'test%',
				'AND',
				'int_property' => 54321
			]
		]);
	}


	/**
	 * @covers Jet\DataModel_Query_Where::__construct
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_NONSENSE
	 */
	public function testConstructFailed3() {
		$this->object = new DataModel_Query_Having($this->query, [
			'int_property =' => 1234,
			'AND',
			'string_property !=' => 'test',
			'OR',
			'OR',
			[
				'my_ID *' => 'test%',
				'AND',
				'int_property' => 54321
			]
		]);
	}


	/**
	 * @covers Jet\DataModel_Query_Where::__construct
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_NONSENSE
	 */
	public function testConstructFailed4() {
		$this->object = new DataModel_Query_Having($this->query, [
			'int_property =' => 1234,
			'AND',
			'string_property !=' => 'test',
			[
				'my_ID *' => 'test%',
				'AND',
				'int_property' => 54321
			]
		]);
	}


	/**
	 * @covers Jet\DataModel_Query_Where::__construct
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
	 */
	public function testConstructFailed5() {
		$this->object = new DataModel_Query_Having($this->query, [
			'int_property =' => 1234,
			'imaginary_AND',
			'string_property !=' => 'test',
			'OR',
			[
				'my_ID *' => 'test%',
				'AND',
				'int_property' => 54321
			]
		]);
	}


	/**
	 * @covers Jet\DataModel_Query_Where::__construct
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_NONSENSE
	 */
	public function testConstructFailed6() {
		$this->object = new DataModel_Query_Having($this->query, [
			'imaginary_property =' => 1234,
			'AND',
			'string_property !=' => 'test',
			'OR',
			[
				'my_ID *' => 'test%',
				'AND',
				'int_property' => 54321
			]
		]);
	}


	/**
	 * @covers Jet\DataModel_Query_Having::addExpression
	 * @covers Jet\DataModel_Query_Having::addAND
	 * @covers Jet\DataModel_Query_Having::addOR
	 * @covers Jet\DataModel_Query_Having::addSubExpressions
	 * @covers Jet\DataModel_Query_Having::attach
	 *
	 * @covers Jet\DataModel_Query_Having::current
	 * @covers Jet\DataModel_Query_Having::key
	 * @covers Jet\DataModel_Query_Having::rewind
	 * @covers Jet\DataModel_Query_Having::valid
	 * @covers Jet\DataModel_Query_Having::next
	 * @covers Jet\DataModel_Query_Having::toString
	 */
	public function testGeneral() {

		$this->assertSame(
			 '( '
			    .'MAX(data_model_test_mock::int_property) <= \'10\' '
				.'AND '
				.'data_model_test_mock::int_property = \'1234\' '
				.'AND '
				.'data_model_test_mock::string_property != \'test\' '
				.'OR '
				.'( '
				        .'data_model_test_mock::ID_property * \'test%\' '
				        .'AND '
				        .'data_model_test_mock::int_property = \'54321\' '
				 .') '
				.'AND '
				.'data_model_test_mock::float_property = \'3.14\''
			.' )',
			$this->object->toString()
		);
	}

}
