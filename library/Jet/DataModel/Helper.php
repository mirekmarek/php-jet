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
class DataModel_Helper
{

	public static function getCreateCommand( string|DataModel $class ) : string
	{
		return $class::getBackendInstance()->helper_getCreateCommand( $class::getDataModelDefinition() );
	}

	public static function create( string|DataModel $class ): void
	{
		$class::getBackendInstance()->helper_create( $class::getDataModelDefinition() );
	}
	
	/**
	 * @param string|DataModel $class
	 * @return array<string>
	 */
	public static function getUpdateCommand( string|DataModel $class ): array
	{
		return $class::getBackendInstance()->helper_getUpdateCommand( $class::getDataModelDefinition() );
	}

	public static function update( string|DataModel $class ): void
	{
		$class::getBackendInstance()->helper_update( $class::getDataModelDefinition() );

	}

	public static function drop( string|DataModel $class ): void
	{
		$class::getBackendInstance()->helper_drop( $class::getDataModelDefinition() );
	}
	
}
