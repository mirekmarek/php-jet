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

class DataModel_Query_WhereTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DataModel_Query_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $properties = array();

	/**
	 * @var DataModel_Query_Where
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
		$this->query->setSelect(array(
			'this.int_property',
			'this.string_property',
			'this.ID_property'
		));

		$this->object = new DataModel_Query_Where($this->query, array(
			'this.int_property =' => 1234,
			'AND',
			'this.string_property !=' => 'test',
			'OR',
			array(
				'this.ID_property *' => 'test%',
				'AND',
				'this.int_property' => 54321
			)
		));

		$query = new DataModel_Query($this->data_model->getDataModelDefinition());
		$query->setSelect(array(
			'this.float_property'
		));

		$query = new DataModel_Query_Where($this->query, array(
			'this.float_property =' => 3.14
		));

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
		$this->object = new DataModel_Query_Where($this->query, array(
			'this.int_property =' => 1234,
			'this.string_property !=' => 'test',
			'OR',
			array(
				'this.ID_property *' => 'test%',
				'AND',
				'this.int_property' => 54321
			)
		));
	}

	/**
	 * @covers Jet\DataModel_Query_Where::__construct
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_NONSENSE
	 */
	public function testConstructFailed2() {
		$this->object = new DataModel_Query_Where($this->query, array(
			'this.int_property =' => 1234,
			'AND',
			'AND',
			'this.string_property !=' => 'test',
			'OR',
			array(
				'this.ID_property *' => 'test%',
				'AND',
				'this.int_property' => 54321
			)
		));
	}


	/**
	 * @covers Jet\DataModel_Query_Where::__construct
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_NONSENSE
	 */
	public function testConstructFailed3() {
		$this->object = new DataModel_Query_Where($this->query, array(
			'this.int_property =' => 1234,
			'AND',
			'this.string_property !=' => 'test',
			'OR',
			'OR',
			array(
				'this.ID_property *' => 'test%',
				'AND',
				'this.int_property' => 54321
			)
		));
	}


	/**
	 * @covers Jet\DataModel_Query_Where::__construct
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_NONSENSE
	 */
	public function testConstructFailed4() {
		$this->object = new DataModel_Query_Where($this->query, array(
			'this.int_property =' => 1234,
			'AND',
			'this.string_property !=' => 'test',
			array(
				'this.ID_property *' => 'test%',
				'AND',
				'this.int_property' => 54321
			)
		));
	}


	/**
	 * @covers Jet\DataModel_Query_Where::__construct
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
	 */
	public function testConstructFailed5() {
		$this->object = new DataModel_Query_Where($this->query, array(
			'this.int_property =' => 1234,
			'imaginary_AND',
			'this.string_property !=' => 'test',
			array(
				'this.ID_property *' => 'test%',
				'AND',
				'this.int_property' => 54321
			)
		));
	}


	/**
	 * @covers Jet\DataModel_Query_Where::__construct
	 * @covers Jet\DataModel_Query_Where::addExpression
	 * @covers Jet\DataModel_Query_Where::addAND
	 * @covers Jet\DataModel_Query_Where::addOR
	 * @covers Jet\DataModel_Query_Where::addSubExpressions
	 * @covers Jet\DataModel_Query_Where::attach
	 *
	 * @covers Jet\DataModel_Query_Where::current
	 * @covers Jet\DataModel_Query_Where::key
	 * @covers Jet\DataModel_Query_Where::rewind
	 * @covers Jet\DataModel_Query_Where::valid
	 * @covers Jet\DataModel_Query_Where::next
	 * @covers Jet\DataModel_Query_Where::toString
	 */
	public function testGeneral() {
		$this->assertSame(
			 '( '
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
