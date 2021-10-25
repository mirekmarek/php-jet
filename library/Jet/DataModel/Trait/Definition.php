<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	public static function getDataModelDefinition( string $class_name = '' ): DataModel_Definition_Model
	{
		if( !$class_name ) {
			$class_name = static::class;
		}

		return DataModel_Definition::get( $class_name );
	}


	/**
	 * @param string $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Main
	 */
	public static function dataModelDefinitionFactory( string $data_model_class_name ): DataModel_Definition_Model_Main
	{
		$class_name = Factory_DataModel::getModelDefinitionClassNamePrefix() . 'Main';

		return new $class_name( $data_model_class_name );
	}

}