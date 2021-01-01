<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Test\ORM;

use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_IDController_UniqueString;
use Jet\DataModel_Related_1toN;

/**
 *
 */
#[DataModel_Definition(name: 'model_a1_1toN_sub1toN')]
#[DataModel_Definition(database_table_name: 'model_a1_1toN_sub1toN')]
#[DataModel_Definition(id_controller_class: DataModel_IDController_UniqueString::class)]
#[DataModel_Definition(parent_model_class: Model_A1_1toN::class)]
class Model_A1_1toN_sub1toN extends DataModel_Related_1toN
{

	/**
	 * @var string
	 */
	#[DataModel_Definition(related_to: 'main.id')]
	protected string $main_id = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(related_to: 'parent.id')]
	protected string $parent_id = '';

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

}