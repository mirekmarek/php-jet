<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/** @noinspection PhpIncludeInspection */
require_once '_mock/Jet/DataModel/Definition/DataModelTestMock.php';
/** @noinspection PhpIncludeInspection */
require_once '_mock/Jet/DataModel/Definition/M2NDataModelTestMock.php';
/** @noinspection PhpIncludeInspection */
require_once '_mock/Jet/DataModel/Definition/NRelatedDataModelTestMock.php';

class DataModel_Definition_Model_Related_MtoNTest extends \PHPUnit_Framework_TestCase
{

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
	 */
	public function testGetNModelRelationIdProperties()
	{
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
		//TODO:
		/*
		$N_relation_id_properties = $this->object->getNModelRelationIdProperties();
		//var_dump(array_keys($N_relation_id_properties));
		$this->assertArrayHasKey('n_related_data_model_test_mock_id', $N_relation_id_properties);
		$this->assertArrayHasKey('n_related_data_model_test_mock_id_property', $N_relation_id_properties);

		$this->assertEquals('n_related_data_model_test_mock_id', $N_relation_id_properties['n_related_data_model_test_mock_id']->getName());
		$this->assertEquals('n_related_data_model_test_mock_id_property', $N_relation_id_properties['n_related_data_model_test_mock_id_property']->getName());

		$this->assertEquals('id', $N_relation_id_properties['n_related_data_model_test_mock_id']->getRelatedToProperty()->getName());
		$this->assertEquals('id_property', $N_relation_id_properties['n_related_data_model_test_mock_id_property']->getRelatedToProperty()->getName());
		*/
	}

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->data_model = new DataModel_Definition_M2NDataModelTestMock();
		$this->M_data_model = new DataModel_Definition_DataModelTestMock();
		$this->N_data_model = new DataModel_Definition_NRelatedDataModelTestMock();

		$this->object = new DataModel_Definition_Model_Related_MtoN( get_class( $this->data_model ) );
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
	}
}
