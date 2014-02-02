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
	 * @JetDataModel:type = Jet\DataModel::TYPE_ID
	 * @JetDataModel:is_ID = true
	 */
	protected $ID;

	/**
	 * @JetDataModel:related_to = 'data_model_test_mock.ID'
	 */
	protected $data_model_test_mock_ID;


	/**
	 * @JetDataModel:related_to = 'data_model_test_mock.ID_property'
	 */
	protected $data_model_test_mock_ID_property;


	/**
	 * @JetDataModel:related_to = 'data_model_2_test_mock.ID'
	 */
	protected $data_model_2_test_mock_ID;
}