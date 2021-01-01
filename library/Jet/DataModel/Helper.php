<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class DataModel_Helper
{

	/**
	 * @param string $class
	 *
	 * @return string
	 */
	public static function getCreateCommand( string $class )
	{
		/**
		 * @var DataModel_Interface $class
		 */

		return $class::getBackendInstance()->helper_getCreateCommand( $class::getDataModelDefinition() );
	}

	/**
	 *
	 * @param string $class
	 *
	 */
	public static function create( string $class ) : void
	{
		/**
		 * @var DataModel_Interface $class
		 */

		$class::getBackendInstance()->helper_create( $class::getDataModelDefinition() );
	}


	/**
	 * Update (actualize) DB table or tables
	 *
	 * @param string $class
	 *
	 * @return array
	 */
	public static function getUpdateCommand( string $class ) : array
	{
		/**
		 * @var DataModel_Interface $class
		 */

		return $class::getBackendInstance()->helper_getUpdateCommand( $class::getDataModelDefinition() );
	}

	/**
	 * Update (actualize) DB table or tables
	 *
	 * @param string $class
	 */
	public static function update( string $class ) : void
	{
		/**
		 * @var DataModel_Interface $class
		 */

		$class::getBackendInstance()->helper_update( $class::getDataModelDefinition() );

	}

	/**
	 * Drop (only rename by default) DB table or tables
	 *
	 * @param string $class
	 */
	public static function drop( string $class ) : void
	{
		/**
		 * @var DataModel_Interface $class
		 */
		$class::getBackendInstance()->helper_drop( $class::getDataModelDefinition() );
	}


}
