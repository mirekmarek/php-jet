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

require_once '_mock/Jet/DataModel/Definition/RelatedDataModelTestMock.php';
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
	 * @covers Jet\DataModel_Definition_Model_Related_Abstract::getMainModelRelationIDProperties
	 */
	public function testGetMainModelRelationIDProperties() {
		$main_model_ID_properties = $this->object->getMainModelRelationIDProperties();
		$this->assertArrayHasKey('main_ID', $main_model_ID_properties);
		$this->assertArrayHasKey('main_ID_property', $main_model_ID_properties);
		$this->assertEquals('main_ID' , $main_model_ID_properties['main_ID']->getName());
		$this->assertEquals('main_ID_property' , $main_model_ID_properties['main_ID_property']->getName());
	}

	/**
	 * @covers Jet\DataModel_Definition_Model_Related_Abstract::getParentModelRelationIDProperties
	 */
	public function testGetParentModelRelationIDProperties() {
		$parent_model_ID_properties = $this->object->getParentModelRelationIDProperties();

		$this->assertArrayHasKey('parent_ID', $parent_model_ID_properties);
		$this->assertArrayHasKey('parent_ID_property', $parent_model_ID_properties);

		$this->assertEquals('parent_ID' , $parent_model_ID_properties['parent_ID']->getName());
		$this->assertEquals('parent_ID_property' , $parent_model_ID_properties['parent_ID_property']->getName());

	}

	/**
	 * @covers Jet\DataModel_Definition_Model_Related_Abstract::getMainModelDefinition
	 */
	public function testGetMainModelDefinition() {
		$this->assertEquals($this->main_data_model->getDataModelName(), $this->object->getMainModelDefinition()->getModelName());
	}

	/**
	 * @covers Jet\DataModel_Definition_Model_Related_Abstract::getParentRelatedModelDefinition
	 */
	public function testGetParentRelatedModelDefinition() {
		$this->assertEquals($this->parent_data_model->getDataModelName(), $this->object->getParentRelatedModelDefinition()->getModelName());
	}
}
