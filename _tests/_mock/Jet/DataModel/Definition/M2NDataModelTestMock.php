<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Definition_M2NDataModelTestMock
 *
 * @JetDataModel:name = 'm2n_data_model_test_mock'
 * @JetDataModel:database_table_name = 'm2n_data_model_test_mock'
 *
 * Model name: data_model_test_mock
 * @JetDataModel:M_model_class_name = 'DataModel_Definition_DataModelTestMock'
 *
 * Model name: n_related_data_model_test_mock
 * @JetDataModel:N_model_class_name = 'DataModel_Definition_NRelatedDataModelTestMock'
 */
class DataModel_Definition_M2NDataModelTestMock extends DataModel_Related_MtoN
{

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
	 * @JetDataModel:related_to = 'n_related_data_model_test_mock.id'
	 */
	protected $data_model_2_test_mock_id;

	/**
	 * @JetDataModel:related_to = 'n_related_data_model_test_mock.id_property'
	 */
	protected $data_model_2_test_mock_id_property;

}