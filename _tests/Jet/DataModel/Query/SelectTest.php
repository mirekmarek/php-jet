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
require_once '_mock/Jet/DataModel/Query/DataModelTestMock.php';

class DataModel_Query_SelectTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DataModel_Query_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $properties = [];

	/**
	 * @var DataModel_Query_Select
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->data_model = new DataModel_Query_DataModelTestMock();

		$this->properties = $this->data_model->getDataModelDefinition()->getProperties();

		$query = new DataModel_Query( $this->data_model->getDataModelDefinition() );

		$this->object = new DataModel_Query_Select($query, [
			$this->properties['id_property'],
			'my_string_property' => $this->properties['string_property'],
			'my_sum' => [
				[
					$this->properties['int_property'],
					$this->properties['float_property']
				],
				'SUM(%int_property%)+%float_property%'
			],
			'my_count' => [
				'this.int_property',
				'COUNT(%int_property%)'
			]


		]);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}


	/**
	 * @covers \Jet\DataModel_Query_Select::__construct
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
	 */
	public function test__constructFailed1() {
		$query = new DataModel_Query( $this->data_model->getDataModelDefinition() );

		$this->object = new DataModel_Query_Select($query, [
			'some' => 'crap'
		]);

	}


	/**
	 * @covers \Jet\DataModel_Query_Select::__construct
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
	 */
	public function test__constructFailed2() {
		$query = new DataModel_Query( $this->data_model->getDataModelDefinition() );

		$this->object = new DataModel_Query_Select($query, [
			[
				'this.int_property',
				'COUNT(%int_property%)'
			]
		]);

	}

	/**
	 * @covers \Jet\DataModel_Query_Select::__construct
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
	 */
	public function test__constructFailed3() {
		$query = new DataModel_Query( $this->data_model->getDataModelDefinition() );

		$this->object = new DataModel_Query_Select($query, [
			[
				'this.int_property'
			]
		]);

	}


	/**
	 * @covers \Jet\DataModel_Query_Select::__construct
	 * @covers \Jet\DataModel_Query_Select::addItem
	 * @covers \Jet\DataModel_Query_Select::getHasItem
	 */
	public function testGetHasItem() {
		$this->assertTrue( $this->object->getHasItem('id_property') );
		$this->assertTrue( $this->object->getHasItem('my_string_property') );
		$this->assertTrue( $this->object->getHasItem('my_sum') );
		$this->assertTrue( $this->object->getHasItem('my_count') );
		$this->assertFalse( $this->object->getHasItem('imaginary') );
	}

	/**
	 * @covers \Jet\DataModel_Query_Select::__construct
	 * @covers \Jet\DataModel_Query_Select::addItem
	 * @covers \Jet\DataModel_Query_Select::getItem
	 */
	public function testGetItem() {
		$this->assertTrue($this->object->getItem('id_property')->getItem() instanceof DataModel_Definition_Property_Abstract);
		$this->assertTrue($this->object->getItem('my_string_property')->getItem() instanceof DataModel_Definition_Property_Abstract);
		$this->assertTrue($this->object->getItem('my_count')->getItem() instanceof DataModel_Query_Select_Item_BackendFunctionCall);
		$this->assertTrue($this->object->getItem('my_sum')->getItem() instanceof DataModel_Query_Select_Item_BackendFunctionCall);
	}

	/**
	 * @covers \Jet\DataModel_Query_Select::__construct
	 * @covers \Jet\DataModel_Query_Select::addItem
	 * @covers \Jet\DataModel_Query_Select::current
	 * @covers \Jet\DataModel_Query_Select::key
	 * @covers \Jet\DataModel_Query_Select::rewind
	 * @covers \Jet\DataModel_Query_Select::valid
	 * @covers \Jet\DataModel_Query_Select::next
	 */
	public function testIterator() {
		$items = [];

		foreach($this->object as $k=>$v) {
			$items[] = $k;
		}

		$this->assertEquals( ['id_property', 'my_string_property', 'my_sum', 'my_count'], $items );
	}
}
