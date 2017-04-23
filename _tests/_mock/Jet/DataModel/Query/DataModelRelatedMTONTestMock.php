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

/**
 * Class DataModel_Query_DataModelRelatedMTONTestMock
 *
 * @JetDataModel:name = 'data_model_test_mock_related_MtoN'
 * @JetDataModel:database_table_name = 'data_model_test_mock_related_MtoN'
 *
 * Model name: data_model_test_mock
 * @JetDataModel:M_model_class_name = 'Jet\\DataModel_Query_DataModelTestMock'
 *
 *
 * Model name: data_model_2_test_mock
 * @JetDataModel:N_model_class_name = 'Jet\\DataModel_Query_DataModel2TestMock'
 */
class DataModel_Query_DataModelRelatedMTONTestMock extends DataModel_Related_MtoN {

	/**
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_id = true
	 */
	protected $id;

	/**
	 * @JetDataModel:related_to = 'data_model_test_mock.id'
	 */
	protected $data_model_test_mock_id;


	/**
	 * @JetDataModel:related_to = 'data_model_test_mock.id_property'
	 */
	protected $data_model_test_mock_id_property;


	/**
	 * @JetDataModel:related_to = 'data_model_2_test_mock.id'
	 */
	protected $data_model_2_test_mock_id;
}