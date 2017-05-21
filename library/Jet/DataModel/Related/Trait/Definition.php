<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
trait DataModel_Related_Trait_Definition
{

	/**
	 * @param string $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Related
	 */
	public static function dataModelDefinitionFactory( $data_model_class_name )
	{
		return new DataModel_Definition_Model_Related( $data_model_class_name );
	}

}
