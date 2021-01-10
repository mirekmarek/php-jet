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
use Jet\DataModel_Query;
use Jet\DataModel_IDController_UniqueString;

/**
 *
 */
#[DataModel_Definition(
	name: 'model_c1',
	database_table_name: 'model_c1',
	id_controller_class: DataModel_IDController_UniqueString::class,
	relation: [
	'related_to_class_name'=> Model_A1::class,
	'join_by_properties'=>[ 'id'=>'id' ],
	'join_type'=>DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN
	]
)]
class Model_C1 extends DataModel
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

}