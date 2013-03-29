<?php
namespace Jet;

require_once "_mock/Jet/DataModel/Query/DataModelTestMock.php";

class DataModel_Query_GroupByTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DataModel_Query_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $properties = array();

	/**
	 * @var DataModel_Query
	 */
	protected $query;

	/**
	 * @var DataModel_Query_GroupBy
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->data_model = new DataModel_Query_DataModelTestMock();

		$this->properties = $this->data_model->getDataModelDefinition()->getProperties();

		$this->query = new DataModel_Query($this->data_model);
		$this->query->setSelect(array(
			"this.int_property",
			"this.string_property",
			"this.ID_property"
		));

		$this->object = new DataModel_Query_GroupBy($this->query, array("int_property", "string_property", "ID_property"));
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers Jet\DataModel_Query_GroupBy::__construct
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
	 */
	public function test__constructFailed() {
		$this->object = new DataModel_Query_GroupBy($this->query, array("imaginary") );

	}

	/**
	 * @covers Jet\DataModel_Query_GroupBy::__construct
	 * @covers Jet\DataModel_Query_GroupBy::current
	 * @covers Jet\DataModel_Query_GroupBy::key
	 * @covers Jet\DataModel_Query_GroupBy::next
	 * @covers Jet\DataModel_Query_GroupBy::rewind
	 * @covers Jet\DataModel_Query_GroupBy::valid
	 */
	public function testIterator() {
		$data = array();
		foreach($this->object as $k=>$v) {
			/**
			 * @var DataModel_Query_Select_Item $v
			 */
			$data[$k] = $v->getSelectAs();
		}

		$this->assertEquals( array (
			'int_property',
			'string_property',
			'ID_property',
		), $data);
	}
}
