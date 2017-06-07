<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Application_Modules extends BaseObject
{

	/**
	 * @var string
	 */
	protected static $base_path = JET_PATH_APPLICATION.'Modules/';


	/**
	 * @var Application_Modules_Handler
	 */
	protected static $handler;


	/**
	 * @return Application_Modules_Handler
	 */
	public static function getHandler()
	{
		if( !static::$handler ) {



			static::$handler = new Application_Modules_Handler_Default();
		}

		return static::$handler;
	}

	/**
	 * @param Application_Modules_Handler $handler
	 */
	public static function setHandler( Application_Modules_Handler $handler )
	{
		static::$handler = $handler;
	}

	/**
	 * @return string
	 */
	public static function getBasePath()
	{
		return static::$base_path;
	}

	/**
	 * @param string $base_path
	 */
	public static function setBasePath( $base_path )
	{
		static::$base_path = $base_path;
	}


	/**
	 *
	 * @throws Application_Modules_Exception
	 * @return Application_Module_Manifest[]
	 */
	public static function installedModulesList()
	{
		return static::getHandler()->installedModulesList();
	}

	/**
	 *
	 *
	 * @throws Application_Modules_Exception
	 *
	 * @return Application_Module_Manifest[]
	 */
	public static function allModulesList()
	{
		return static::getHandler()->allModulesList();
	}

	/**
	 *
	 * @return Application_Module_Manifest[]
	 */
	public static function activatedModulesList()
	{
		return static::getHandler()->activatedModulesList();
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public static function moduleExists( $module_name )
	{
		return static::getHandler()->moduleExists( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public static function moduleIsInstalled( $module_name )
	{
		return static::getHandler()->moduleIsInstalled( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public static function moduleIsActivated( $module_name )
	{
		return static::getHandler()->moduleIsActivated( $module_name );
	}


	/**
	 *
	 * @param string $module_name
	 *
	 * @return Application_Module_Manifest
	 */
	public static function moduleManifest( $module_name )
	{
		return static::getHandler()->moduleManifest( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public static function installModule( $module_name )
	{
		static::getHandler()->installModule( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public static function uninstallModule( $module_name )
	{
		static::getHandler()->uninstallModule( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public static function activateModule( $module_name )
	{
		static::getHandler()->activateModule( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public static function deactivateModule( $module_name )
	{
		static::getHandler()->deactivateModule( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return Application_Module
	 */
	public static function moduleInstance( $module_name )
	{
		return static::getHandler()->moduleInstance( $module_name );
	}


}