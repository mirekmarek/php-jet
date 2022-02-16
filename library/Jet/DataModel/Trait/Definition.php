<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
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
	 * @return string
	 */
	public static function dataModelDefinitionType(): string
	{
		return DataModel::MODEL_TYPE_MAIN;
	}
}