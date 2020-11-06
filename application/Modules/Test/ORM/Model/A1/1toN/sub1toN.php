<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Test\ORM;

use Jet\DataModel;
use Jet\DataModel_IDController_UniqueString;
use Jet\DataModel_Related_1toN;

/**
 *
 * @JetDataModel:name = 'model_a1_1toN_sub1toN'
 * @JetDataModel:database_table_name = 'model_a1_1toN_sub1toN'
 * @JetDataModel:id_controller_class_name = 'DataModel_IDController_UniqueString'
 * @JetDataModel:parent_model_class_name = 'Model_A1_1toN';
 */
class Model_A1_1toN_sub1toN extends DataModel_Related_1toN
{

	/**
	 *
	 * @JetDataModel:related_to = 'main.id'
	 *
	 * @var
	 */
	protected $main_id;

	/**
	 *
	 * @JetDataModel:related_to = 'parent.id'
	 *
	 * @var
	 */
	protected $parent_id;

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

}