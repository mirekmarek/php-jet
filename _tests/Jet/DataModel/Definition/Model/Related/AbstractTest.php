<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/** @noinspection PhpIncludeInspection */
require_once '_mock/Jet/DataModel/Definition/RelatedDataModelTestMock.php';
/** @noinspection PhpIncludeInspection */
require_once '_mock/Jet/DataModel/Definition/SubRelatedDataModelTestMock.php';

class DataModel_Definition_Model_Related_AbstractTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DataModel_Definition_SubRelatedDataModelTestMock
	 */
	protected $data_model;
	/**
	 * @var DataModel_Definition_DataModelTestMock
	 */
	protected $main_data_model;
	/**
	 * @var DataModel_Definition_RelatedDataModelTestMock
	 */
	protected $parent_data_model;

	/**
	 * @var DataModel_Definition_Model_Related_Abstract
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->data_model = new DataModel_Definition_SubRelatedDataModelTestMock();
		$this->main_data_model = new DataModel_Definition_DataModelTestMock();
		$this->parent_data_model = new DataModel_Definition_RelatedDataModelTestMock();
		$this->object = new DataModel_Definition_Model_Related_1toN( get_class($this->data_model) );
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers \Jet\DataModel_Definition_Model_Related_Abstract::getMainModelRelationIdProperties
	 */
	public function testGetMainModelRelationIdProperties() {
		$main_model_id_properties = $this->object->getMainModelRelationIdProperties();
		$this->assertArrayHasKey('main_id', $main_model_id_properties);
		$this->assertArrayHasKey('main_id_property', $main_model_id_properties);
		$this->assertEquals('main_id' , $main_model_id_properties['main_id']->getName());
		$this->assertEquals('main_id_property' , $main_model_id_properties['main_id_property']->getName());
	}

	/**
	 * @covers \Jet\DataModel_Definition_Model_Related_Abstract::getParentModelRelationIdProperties
	 */
	public function testGetParentModelRelationIdProperties() {
		$parent_model_id_properties = $this->object->getParentModelRelationIdProperties();

		$this->assertArrayHasKey('parent_id', $parent_model_id_properties);
		$this->assertArrayHasKey('parent_id_property', $parent_model_id_properties);

		$this->assertEquals('parent_id' , $parent_model_id_properties['parent_id']->getName());
		$this->assertEquals('parent_id_property' , $parent_model_id_properties['parent_id_property']->getName());

	}

	/**
	 * @covers \Jet\DataModel_Definition_Model_Related_Abstract::getMainModelDefinition
	 */
	public function testGetMainModelDefinition() {
		$this->assertEquals('data_model_test_mock', $this->object->getMainModelDefinition()->getModelName());
	}

	/**
	 * @covers \Jet\DataModel_Definition_Model_Related_Abstract::getParentRelatedModelDefinition
	 */
	public function testGetParentRelatedModelDefinition() {
		$this->assertEquals('related_data_model_test_mock', $this->object->getParentRelatedModelDefinition()->getModelName());
	}
}
