<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Test\ORM;

use Jet\DataModel_Related_MtoN;
use Jet\DataModel_Definition;

/**
 *
 */
#[DataModel_Definition(name: 'a1_m2n_b1')]
#[DataModel_Definition(database_table_name: 'a1_m2n_b1')]
#[DataModel_Definition(parent_model_class: Model_A1::class )]
#[DataModel_Definition(N_model_class: Model_B1::class )]
class Model_MtoN extends DataModel_Related_MtoN
{

	/**
	 * @var string
	 */
	#[DataModel_Definition(related_to: 'main.id')]
	protected string $a1_id = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(related_to: 'model_b1.id')]
	protected string $b1_id = '';


}