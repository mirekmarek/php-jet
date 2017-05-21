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
trait DataModel_Trait_Definition
{
	/**
	 * Returns model definition
	 *
	 * @param string $class_name (optional)
	 *
	 * @return DataModel_Definition_Model
	 */
	public static function getDataModelDefinition( $class_name = '' )
	{
		if( !$class_name ) {
			$class_name = get_called_class();
		}

		return DataModel_Definition::get( $class_name );
	}


	/**
	 * @param string $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Main
	 */
	public static function dataModelDefinitionFactory( $data_model_class_name )
	{
		return new DataModel_Definition_Model_Main( $data_model_class_name );
	}

}