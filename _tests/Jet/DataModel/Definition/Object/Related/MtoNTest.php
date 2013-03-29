<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package DataModel
 */
namespace Jet;

require_once "_mock/Jet/DataModel/Definition/DataModelTestMock.php";
require_once "_mock/Jet/DataModel/Definition/M2NDataModelTestMock.php";
require_once "_mock/Jet/DataModel/Definition/NRelatedDataModelTestMock.php";

class DataModel_Definition_Model_Related_MtoNTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var DataModel_Definition_M2NDataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_DataModelTestMock;
	 */
	protected $M_data_model;

	/**
	 * @var DataModel_Definition_NRelatedDataModelTestMock
	 */
	protected $N_data_model;

	/**
	 * @var DataModel_Definition_Model_Related_MtoN
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->data_model = new DataModel_Definition_M2NDataModelTestMock();
		$this->M_data_model = new DataModel_Definition_DataModelTestMock();
		$this->N_data_model = new DataModel_Definition_NRelatedDataModelTestMock();
		$this->object = new DataModel_Definition_Model_Related_MtoN(
			$this->data_model,
			$this->M_data_model->getDataModelDefinition(),
			$this->N_data_model->getDataModelDefinition()
		);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}


	/**
	 * @covers Jet\DataModel_Definition_Model_Related_MtoN::setupRelation
	 * @covers Jet\DataModel_Definition_Model_Related_MtoN::getMRelatedModelDefinition
	 */
	public function testGetMRelatedModelDefinition() {
		$this->assertEquals($this->M_data_model->getDataModelDefinition(), $this->object->getMRelatedModelDefinition());
	}

	/**
	 * @covers Jet\DataModel_Definition_Model_Related_MtoN::setupRelation
	 * @covers Jet\DataModel_Definition_Model_Related_MtoN::getNRelatedModelDefinition
	 */
	public function testGetNRelatedModelDefinition() {
		$this->assertEquals($this->N_data_model->getDataModelDefinition(), $this->object->getNRelatedModelDefinition());
	}

	/**
	 * @covers Jet\DataModel_Definition_Model_Related_MtoN::setupRelation
	 * @covers Jet\DataModel_Definition_Model_Related_MtoN::getMModelRelationIDProperties
	 */
	public function testGetMModelRelationIDProperties() {
		$M_relation_ID_properties = $this->object->getMModelRelationIDProperties();
		$this->assertArrayHasKey("data_model_test_mock_ID", $M_relation_ID_properties);
		$this->assertArrayHasKey("data_model_test_mock_ID_property", $M_relation_ID_properties);

		$this->assertEquals("data_model_test_mock_ID", $M_relation_ID_properties["data_model_test_mock_ID"]->getName());
		$this->assertEquals("data_model_test_mock_ID_property", $M_relation_ID_properties["data_model_test_mock_ID_property"]->getName());

		$this->assertEquals("ID", $M_relation_ID_properties["data_model_test_mock_ID"]->getRelatedToProperty()->getName());
		$this->assertEquals("ID_property", $M_relation_ID_properties["data_model_test_mock_ID_property"]->getRelatedToProperty()->getName());
	}

	/**
	 * @covers Jet\DataModel_Definition_Model_Related_MtoN::setupRelation
	 * @covers Jet\DataModel_Definition_Model_Related_MtoN::getNModelRelationIDProperties
	 */
	public function testGetNModelRelationIDProperties() {
		$N_relation_ID_properties = $this->object->getNModelRelationIDProperties();
		//var_dump(array_keys($N_relation_ID_properties));
		$this->assertArrayHasKey("n_related_data_model_test_mock_ID", $N_relation_ID_properties);
		$this->assertArrayHasKey("n_related_data_model_test_mock_ID_property", $N_relation_ID_properties);

		$this->assertEquals("n_related_data_model_test_mock_ID", $N_relation_ID_properties["n_related_data_model_test_mock_ID"]->getName());
		$this->assertEquals("n_related_data_model_test_mock_ID_property", $N_relation_ID_properties["n_related_data_model_test_mock_ID_property"]->getName());

		$this->assertEquals("ID", $N_relation_ID_properties["n_related_data_model_test_mock_ID"]->getRelatedToProperty()->getName());
		$this->assertEquals("ID_property", $N_relation_ID_properties["n_related_data_model_test_mock_ID_property"]->getRelatedToProperty()->getName());
	}
}
