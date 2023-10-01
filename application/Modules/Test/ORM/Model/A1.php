<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\ORM;

use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_IDController_UniqueString;

/**
 *
 */
#[DataModel_Definition(
	name: 'model_a1',
	database_table_name: 'model_a1',
	id_controller_class: DataModel_IDController_UniqueString::class
)]
class Model_A1 extends DataModel
{

	#[DataModel_Definition(
		type: DataModel::TYPE_ID,
		is_id: true
	)]
	protected string $id = '';

	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255
	)]
	protected string $text = '';

	/**
	 * @var Model_A1_1toN[]
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_DATA_MODEL,
		data_model_class: Model_A1_1toN::class
	)]
	protected array $related_1toN = [];



}