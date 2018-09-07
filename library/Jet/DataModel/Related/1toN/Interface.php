<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
interface DataModel_Related_1toN_Interface extends DataModel_Related_Interface
{

	/**
	 * @param string $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Related_1toN
	 */
	public static function dataModelDefinitionFactory( $data_model_class_name );

	/**
	 * @return null
	 */
	public function getArrayKeyValue();
}