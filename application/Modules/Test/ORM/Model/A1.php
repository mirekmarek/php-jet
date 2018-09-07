<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Test\ORM;

use Jet\DataModel;

/**
 *
 * @JetDataModel:name = 'model_a1'
 * @JetDataModel:database_table_name = 'model_a1'
 * @JetDataModel:id_controller_class_name = 'DataModel_IDController_UniqueString'

 */
class Model_A1 extends DataModel
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
	protected $text = '';

	/**
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Model_A1_1toN'
	 *
	 * @var Model_A1_1toN[]
	 */
	protected $related_1toN;

	/**
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Model_MtoN'
	 *
	 * @var Model_MtoN[]|Model_B1[]
	 */
	protected $related_MtoN;


}