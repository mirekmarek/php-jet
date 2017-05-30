<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\TestModule;

use Jet\DataModel;

/**
 *
 * @JetDataModel:name = 'data_model_test_relations_a'
 * @JetDataModel:database_table_name = 'data_model_test_relations_a'
 * @JetDataModel:id_class_name = 'DataModel_Id_UniqueString'
 */
class DataModelTest_Relations_A extends DataModel
{
	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_id = true
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 *
	 * @var string
	 */
	protected $name = '';

}