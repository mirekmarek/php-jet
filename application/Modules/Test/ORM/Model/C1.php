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
#[DataModel_Definition(name: 'model_c1')]
#[DataModel_Definition(database_table_name: 'model_c1')]
#[DataModel_Definition(id_controller_class: DataModel_IDController_UniqueString::class)]
#[DataModel_Definition(relation: [
	'related_to_class_name'=> Model_A1::class,
	'join_by_properties'=>[ 'id'=>'id' ],
	'join_type'=>DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN
])]
class Model_C1 extends DataModel
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

}