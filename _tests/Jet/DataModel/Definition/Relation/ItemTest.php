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
class DataModel_Definition_Relation_ItemTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var DataModel_Query_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $properties = [];


	/**
	 * @var DataModel_Definition_Relation_Internal
	 */
	protected $object;

	/**
	 * @covers \Jet\DataModel_Query_Relation_Item::setJoinByProperties
	 * @covers \Jet\DataModel_Query_Relation_Item::getJoinByProperties
	 */
	public function testGetSetJoinByProperties()
	{
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
		//TODO:
		/*
		$this->assertEquals(
			$this->data_model->getIdProperties(),
			$this->object->getJoinByProperties()
		);

		$new_join = array(
			$this->properties['id_property'],
			$this->properties['string_property']
		);

		$this->object->setJoinByProperties( $new_join );
		$this->assertEquals(
			$new_join,
			$this->object->getJoinByProperties()
		);
		*/
	}

	/**
	 * @covers \Jet\DataModel_Query_Relation_Item::setJoinType
	 * @covers \Jet\DataModel_Query_Relation_Item::getJoinType
	 */
	public function testGetSetJoinType()
	{
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
		//TODO:
		/*
		$this->assertEquals(DataModel_Query::JOIN_TYPE_LEFT_JOIN, $this->object->getJoinType());
		$this->object->setJoinType(DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN);
		$this->assertEquals(DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN, $this->object->getJoinType());
		*/
	}

	/**
	 * @covers \Jet\DataModel_Query_Relation_Item::getRelatedDataModelDefinition
	 */
	public function testGetRelatedDataModelDefinition()
	{
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
		//TODO:
		//$this->assertSame($this->data_model->getDataModelDefinition(), $this->object->getRelatedDataModelDefinition());
	}

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
		//TODO:
		/*
		$this->data_model = new DataModel_Query_DataModelTestMock();

		$this->properties = $this->data_model->getDataModelDefinition()->getProperties();

		$this->object = new DataModel_Definition_Relation_Internal(
			$this->data_model->getDataModelDefinition(),
			$this->data_model->getIdProperties()
		);
		*/
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
	}
}
