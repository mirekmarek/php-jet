<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Test\ORM;

use Jet\DataModel_Related_MtoN;

/**
 *
 * @JetDataModel:name = 'a1_m2n_b1'
 *
 * @JetDataModel:database_table_name = 'a1_m2n_b1'
 *
 * @JetDataModel:parent_model_class_name = 'Model_A1'
 * @JetDataModel:N_model_class_name = 'Model_B1'
 */
class Model_MtoN extends DataModel_Related_MtoN
{

	/**
	 *
	 * @JetDataModel:related_to = 'main.id'
	 *
	 * @var string
	 */
	protected $a1_id = '';

	/**
	 *
	 * @JetDataModel:related_to = 'model_b1.id'
	 *
	 * @var string
	 */
	protected $b1_id = '';


}