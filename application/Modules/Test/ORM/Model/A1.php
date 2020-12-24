<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Test\ORM;

use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_IDController_UniqueString;

/**
 *
 */
#[DataModel_Definition(name: 'model_a1')]
#[DataModel_Definition(database_table_name: 'model_a1')]
#[DataModel_Definition(id_controller_class: DataModel_IDController_UniqueString::class)]
class Model_A1 extends DataModel
{

	/**
	 * @var string
	 */
	#[DataModel_Definition(type: DataModel::TYPE_ID)]
	#[DataModel_Definition(is_id: true)]
	protected string $id = '';


	/**
	 * @var string
	 */
	#[DataModel_Definition(type: DataModel::TYPE_STRING)]
	#[DataModel_Definition(max_len: 255)]
	protected string $text = '';

	/**
	 * @var Model_A1_1toN[]
	 */
	#[DataModel_Definition(type: DataModel::TYPE_DATA_MODEL)]
	#[DataModel_Definition(data_model_class: Model_A1_1toN::class)]
	protected $related_1toN;

	/**
	 * @var Model_MtoN[]|Model_B1[]
	 */
	#[DataModel_Definition(type: DataModel::TYPE_DATA_MODEL)]
	#[DataModel_Definition(data_model_class: Model_MtoN::class)]
	protected $related_MtoN;

}