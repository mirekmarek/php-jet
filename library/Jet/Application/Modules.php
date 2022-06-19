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
class Application_Modules extends BaseObject
{
	/**
	 * @var ?Application_Modules_Handler
	 */
	protected static ?Application_Modules_Handler $handler = null;


	/**
	 * @return Application_Modules_Handler
	 */
	public static function getHandler(): Application_Modules_Handler
	{
		if( !static::$handler ) {
			static::$handler = Factory_Application::getDefaultModuleHandlerInstance();
		}

		return static::$handler;
	}

	/**
	 * @param Application_Modules_Handler $handler
	 */
	public static function setHandler( Application_Modules_Handler $handler ) : void
	{
		static::$handler = $handler;
	}

	/**
	 * @param string $module_name
	 *
	 * @return string
	 */
	public static function getModuleDir( string $module_name ): string
	{
		return static::getHandler()->getModuleDir( $module_name );
	}

	/**
	 *
	 * @return Application_Module_Manifest[]
	 * @throws Application_Modules_Exception
	 */
	public static function installedModulesList(): array
	{
		return static::getHandler()->installedModulesList();
	}

	/**
	 *
	 *
	 * @return Application_Module_Manifest[]
	 * @throws Application_Modules_Exception
	 *
	 */
	public static function allModulesList(): array
	{
		return static::getHandler()->allModulesList();
	}

	/**
	 *
	 * @return Application_Module_Manifest[]
	 */
	public static function activatedModulesList(): array
	{
		return static::getHandler()->activatedModulesList();
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public static function moduleExists( string $module_name ): bool
	{
		return static::getHandler()->moduleExists( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public static function moduleIsInstalled( string $module_name ): bool
	{
		return static::getHandler()->moduleIsInstalled( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public static function moduleIsActivated( string $module_name ): bool
	{
		return static::getHandler()->moduleIsActivated( $module_name );
	}


	/**
	 *
	 * @param string $module_name
	 *
	 * @return Application_Module_Manifest
	 */
	public static function moduleManifest( string $module_name ): Application_Module_Manifest
	{
		return static::getHandler()->moduleManifest( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public static function installModule( string $module_name ): void
	{
		static::getHandler()->installModule( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public static function uninstallModule( string $module_name ): void
	{
		static::getHandler()->uninstallModule( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public static function activateModule( string $module_name ): void
	{
		static::getHandler()->activateModule( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public static function deactivateModule( string $module_name ): void
	{
		static::getHandler()->deactivateModule( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return Application_Module
	 */
	public static function moduleInstance( string $module_name ): Application_Module
	{
		return static::getHandler()->moduleInstance( $module_name );
	}
	
	/**
	 * @param string $module_name
	 *
	 * @return array
	 */
	public static function readManifestData( string $module_name ) : array
	{
		return static::getHandler()->readManifestData( $module_name );
	}
	
	/**
	 * @param Application_Module_Manifest $manifest
	 */
	public static function saveManifest( Application_Module_Manifest $manifest ) : void
	{
		static::getHandler()->saveManifest( $manifest );
	}

}