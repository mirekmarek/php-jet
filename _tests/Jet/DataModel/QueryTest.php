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
require_once '_mock/Jet/DataModel/Query/DataModelRelated1TONTestMock.php';
require_once '_mock/Jet/DataModel/Query/DataModelRelated1TO1TestMock.php';
require_once '_mock/Jet/DataModel/Query/DataModelRelatedMTONTestMock.php';
require_once '_mock/Jet/DataModel/Query/DataModel2TestMock.php';
require_once '_mock/Jet/DataModel/Query/DataModel2Related1TONTestMock.php';

class DataModel_QueryTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DataModel_Query_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $properties = array();

	protected $select_data = array();

	protected $where_data = array();

	protected $having_data = array();

	/**
	 * @var DataModel_Query
	 */
	protected $object;


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->data_model = new DataModel_Query_DataModelTestMock();
		$this->properties = $this->data_model->getDataModelDefinition()->getProperties();

		$this->object = new DataModel_Query($this->data_model->getDataModelDefinition());

		$this->select_data = array(
			$this->properties['ID_property'],
			'my_string_property' => $this->properties['string_property'],
			'my_sum' => array(
				array(
					$this->properties['int_property'],
					$this->properties['float_property']
				),
				'SUM(%int_property%)+%float_property%'
			),
			'my_count' => array(
				'this.int_property',
				'COUNT(%int_property%)'
			)
		);

		$this->where_data = array(
			'this.int_property =' => 1234,
			'AND',
			'this.string_property !=' => 'test',
			'OR',
			array(
				'this.ID_property *' => 'test%',
				'AND',
				'this.int_property' => 54321
			)
		);

		$this->having_data = array(
			'int_property =' => 1234,
			'AND',
			'string_property !=' => 'test',
			'OR',
			array(
				'my_ID *' => 'test%',
				'AND',
				'int_property' => 54321
			)
		);

	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers Jet\DataModel_Query::createQuery
	 */
	public function testCreateQuery() {
		$object = DataModel_Query::createQuery($this->data_model->getDataModelDefinition(), $this->where_data);
		$this->assertSame($this->data_model->getDataModelDefinition(), $object->getMainDataModelDefinition());

		$where = new DataModel_Query_Where($this->object, $this->where_data);
		$this->assertEquals($where->toString(), $object->getWhere()->toString());
	}

	/**
	 * @covers Jet\DataModel_Query::getMainDataModelDefinition
	 */
	public function testGetSetMainDataModelDefinition() {
		$this->assertSame($this->data_model->getDataModelDefinition(), $this->object->getMainDataModelDefinition());
		$new_data_model = new DataModel_Query_DataModelTestMock();
		$this->object->setMainDataModel( $new_data_model->getDataModelDefinition() );

		$this->assertSame($new_data_model->getDataModelDefinition(), $this->object->getMainDataModelDefinition());
	}

	/**
	 * @covers Jet\DataModel_Query::getSelect
	 * @covers Jet\DataModel_Query::setSelect
	 */
	public function testGetSetSelect() {
		$this->assertNull($this->object->getSelect());

		$select = new DataModel_Query_Select($this->object, $this->select_data);
		$this->assertSame($this->object, $this->object->setSelect($this->select_data));
		$this->assertEquals($select, $this->object->getSelect());
	}


	/**
	 * @covers Jet\DataModel_Query::setWhere
	 * @covers Jet\DataModel_Query::getWhere
	 */
	public function testGetSetWhere() {
		$this->assertNull( $this->object->getWhere() );
		$where = new DataModel_Query_Where($this->object, $this->where_data);
		$this->assertSame($this->object, $this->object->setWhere($this->where_data));
		$this->assertEquals($where, $this->object->getWhere());

	}


	/**
	 * @covers Jet\DataModel_Query::setHaving
	 * @covers Jet\DataModel_Query::getHaving
	 */
	public function testGetSetHaving() {
		$this->object->setSelect(array(
			'int_property' => 'this.int_property',
			'string_property' => 'this.string_property',
			'my_ID' => 'this.ID_property'
		));

		$this->assertNull($this->object->getHaving());
		$this->assertSame($this->object, $this->object->setHaving($this->having_data));

		$having = new DataModel_Query_Having($this->object, $this->having_data);
		$this->assertEquals($having, $this->object->getHaving());
	}


	/**
	 * @covers Jet\DataModel_Query::setGroupBy
	 * @covers Jet\DataModel_Query::getGroupBy
	 */
	public function testGetSetGroupBy() {
		$this->object->setSelect(array(
			'this.int_property',
			'this.string_property',
			'this.ID_property'
		));

		$gorup_by_data = array('int_property', 'string_property', 'ID_property');

		$this->assertNull($this->object->getGroupBy());
		$this->assertSame($this->object, $this->object->setGroupBy($gorup_by_data));

		$group_by = new DataModel_Query_GroupBy($this->object, $gorup_by_data );

		$this->assertEquals($group_by, $this->object->getGroupBy());
	}


	/**
	 * @covers Jet\DataModel_Query::setOrderBy
	 * @covers Jet\DataModel_Query::getOrderBy
	 */
	public function testGetSetOrderBy() {
		$this->object->setSelect(array(
			'this.int_property',
			'this.string_property',
			'this.ID_property'
		));
		$this->assertNull($this->object->getOrderBy());

		$order_by_data = array('+int_property', '-string_property', 'ID_property');
		$this->assertSame($this->object, $this->object->setOrderBy($order_by_data));

		$order_by = new DataModel_Query_OrderBy($this->object, $order_by_data );

		$this->assertEquals($order_by, $this->object->getOrderBy());
	}


	/**
	 * @covers Jet\DataModel_Query::setLimit
	 * @covers Jet\DataModel_Query::getLimit
	 * @covers Jet\DataModel_Query::getOffset
	 */
	public function testGetSetLimit() {
		$this->assertNull($this->object->getLimit());
		$this->assertNull($this->object->getOffset());

		$limit = 100;
		$offset = 200;
		$this->assertSame($this->object, $this->object->setLimit($limit, $offset));

		$this->assertEquals($limit, $this->object->getLimit());
		$this->assertEquals($offset, $this->object->getOffset());
	}


	/**
	 * @covers Jet\DataModel_Query::_getPropertyAndSetRelation
	 * @covers Jet\DataModel_Query::getRelations
	 * @covers Jet\DataModel_Query::getRelation
	 */
	public function testRelationsM2N() {
		$property = $this->object->_getPropertyAndSetRelation('data_model_2_test_mock.string_property');
		$this->assertEquals('data_model_2_test_mock', $property->getDataModelDefinition()->getModelName());
		$this->assertEquals('string_property', $property->getName());

		$this->assertEquals(
				array(
					'data_model_test_mock_related_MtoN',
					'data_model_2_test_mock'
				),
				array_keys($this->object->getRelations())
			);


		$this->assertEquals(
			DataModel_Query::JOIN_TYPE_LEFT_JOIN,
			$this->object->getRelation('data_model_2_test_mock')->getJoinType()
		);

		$this->object->getRelation('data_model_2_test_mock')->setJoinType(DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN);

		$this->assertEquals(
			DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN,
			$this->object->getRelation('data_model_2_test_mock')->getJoinType()
		);

	}


	/**
	 * @covers Jet\DataModel_Query::_getPropertyAndSetRelation
	 * @covers Jet\DataModel_Query::getRelations
	 * @covers Jet\DataModel_Query::getRelation
	 */
	public function testRelations12N() {
		$property = $this->object->_getPropertyAndSetRelation('data_model_test_mock_related_1toN.string_property');

		$this->assertEquals('data_model_test_mock_related_1toN', $property->getDataModelDefinition()->getModelName());

		$this->assertEquals(
			array(
				'data_model_test_mock_related_1toN'
			),
			array_keys($this->object->getRelations())
		);


		$this->assertEquals(
			DataModel_Query::JOIN_TYPE_LEFT_JOIN,
			$this->object->getRelation('data_model_test_mock_related_1toN')->getJoinType()
		);

		$this->object->getRelation('data_model_test_mock_related_1toN')->setJoinType(DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN);

		$this->assertEquals(
			DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN,
			$this->object->getRelation('data_model_test_mock_related_1toN')->getJoinType()
		);

	}

	/**
	 * @covers Jet\DataModel_Query::_getPropertyAndSetRelation
	 * @covers Jet\DataModel_Query::getRelations
	 * @covers Jet\DataModel_Query::getRelation
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
	 */
	public function testRelationsFailed1() {
		$this->object->_getPropertyAndSetRelation('hoax');
	}

	/**
	 * @covers Jet\DataModel_Query::_getPropertyAndSetRelation
	 * @covers Jet\DataModel_Query::getRelations
	 * @covers Jet\DataModel_Query::getRelation
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
	 */
	public function testRelationsFailed2() {
		$this->object->_getPropertyAndSetRelation('this.imaginary_property');
	}


	/**
	 * @covers Jet\DataModel_Query::_getPropertyAndSetRelation
	 * @covers Jet\DataModel_Query::getRelations
	 * @covers Jet\DataModel_Query::getRelation
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
	 */
	public function testRelationsFailed3() {
		$this->object->_getPropertyAndSetRelation('string_property');
	}


	/**
	 * @covers Jet\DataModel_Query::_getPropertyAndSetRelation
	 * @covers Jet\DataModel_Query::getRelations
	 * @covers Jet\DataModel_Query::getRelation
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
	 */
	public function testRelationsFailed4() {
		$this->object->_getPropertyAndSetRelation('data_model_property_1toN');
	}


	/**
	 * @covers Jet\DataModel_Query::_getPropertyAndSetRelation
	 * @covers Jet\DataModel_Query::getRelations
	 * @covers Jet\DataModel_Query::getRelation
	 *
	 * @expectedException \Jet\DataModel_Query_Exception
	 * @expectedExceptionCode \Jet\DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
	 */
	public function testRelationsFailed5() {
		$this->object->getRelation('imaginary_relation');
	}

}
