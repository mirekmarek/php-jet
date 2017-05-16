<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Interface DataModel_Related_1to1_Interface
 * @package Jet
 */
interface DataModel_Related_1to1_Interface extends DataModel_Related_Item_Interface, DataModel_Interface
{

	/**
	 * @param string $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Related_1to1
	 */
	public static function dataModelDefinitionFactory( $data_model_class_name );


}