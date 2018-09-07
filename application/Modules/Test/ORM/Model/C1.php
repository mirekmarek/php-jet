<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Test\ORM;

use Jet\DataModel;
use Jet\DataModel_Query;

/**
 *
 * @JetDataModel:name = 'model_c1'
 * @JetDataModel:database_table_name = 'model_c1'
 * @JetDataModel:id_controller_class_name = 'DataModel_IDController_UniqueString'
 *
 * @JetDataModel:relation = [ 'Model_A1', [ 'id'=>'id' ], DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN ]
 *
 */
class Model_C1 extends DataModel
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

}