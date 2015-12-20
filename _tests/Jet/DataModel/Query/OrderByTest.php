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

class DataModel_Query_OrderByTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DataModel_Query_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $properties = [];

	/**
	 * @var DataModel_Query
	 */
	protected $query;

	/**
	 * @var DataModel_Query_OrderBy
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->data_model = new DataModel_Query_DataModelTestMock();

		$this->properties = $this->data_model->getDataModelDefinition()->getProperties();

		$this->query = new DataModel_Query($this->data_model->getDataModelDefinition());
		$this->query->setSelect([
			'this.string_property',
			'my_value' => [
							['this.int_property'],
							'MAX(%int_property%)'
			],
		]);

		$this->object = new DataModel_Query_OrderBy($this->query, ['+this.int_property', '+my_value','-string_property', 'this.ID_property']);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers Jet\DataModel_Query_OrderBy::__construct
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
	 */
	public function test__constructFailed() {
		$this->object = new DataModel_Query_OrderBy($this->query, ['+imaginary']);

	}

	/**
	 * @covers Jet\DataModel_Query_OrderBy::__construct
	 * @covers Jet\DataModel_Query_OrderBy::current
	 * @covers Jet\DataModel_Query_OrderBy::key
	 * @covers Jet\DataModel_Query_OrderBy::next
	 * @covers Jet\DataModel_Query_OrderBy::rewind
	 * @covers Jet\DataModel_Query_OrderBy::valid
	 */
	public function testIterator() {
		$data = [];
		foreach($this->object as $k=>$v) {
			/**
			 * @var DataModel_Query_OrderBy_Item $v
			 */
			$item = $v->getItem();

			/**
			 * @var DataModel_Query_Select_Item|DataModel_Definition_Property_Abstract $item
			 */
			if($item instanceof DataModel_Definition_Property_Abstract) {
				$data[$k] = ($v->getDesc()?'-':'+').$item->getName();
			} else {
				$data[$k] = ($v->getDesc()?'-':'+').$item->getSelectAs();
			}
		}

		$this->assertEquals( [
			'+int_property',
			'+my_value',
			'-string_property',
			'+ID_property',
		], $data);
	}

}
