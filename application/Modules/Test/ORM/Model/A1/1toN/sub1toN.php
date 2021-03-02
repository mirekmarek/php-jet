<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\ORM;

use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_IDController_UniqueString;
use Jet\DataModel_Related_1toN;

/**
 *
 */
#[DataModel_Definition(
	name: 'model_a1_1toN_sub1toN',
	database_table_name: 'model_a1_1toN_sub1toN',
	id_controller_class: DataModel_IDController_UniqueString::class,
	parent_model_class: Model_A1_1toN::class
)]
class Model_A1_1toN_sub1toN extends DataModel_Related_1toN
{

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		related_to: 'main.id'
	)]
	protected string $main_id = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		related_to: 'parent.id'
	)]
	protected string $parent_id = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_ID,
		is_id: true
	)]
	protected string $id = '';


	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255
	)]
	protected string $text = '';

}