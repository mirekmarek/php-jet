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

			$manifest_class_name = Application_Factory::getModuleManifestClassName();
			$module_namespace = $manifest_class_name::getDefaultModuleNamespace();


			static::$handler = new Application_Modules_Handler_Default(
				static::getBasePath(),
				$module_namespace,
				$manifest_class_name
			);
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
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public static function checkModuleNameFormat( $module_name )
	{
		return static::getHandler()->checkModuleNameFormat( $module_name );
	}

	/**
	 *
	 * @throws Application_Modules_Exception
	 * @return Application_Module_Manifest[]
	 */
	public static function getInstalledModulesList()
	{
		return static::getHandler()->getInstalledModulesList();
	}

	/**
	 *
	 * @param bool $ignore_corrupted_modules
	 *
	 * @throws Application_Modules_Exception
	 *
	 * @return Application_Module_Manifest[]
	 */
	public static function getAllModulesList( $ignore_corrupted_modules = true )
	{
		return static::getHandler()->getAllModulesList( $ignore_corrupted_modules );
	}

	/**
	 *
	 * @return Application_Module_Manifest[]
	 */
	public static function getActivatedModulesList()
	{
		return static::getHandler()->getActivatedModulesList();
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public static function getModuleExists( $module_name )
	{
		return static::getHandler()->getModuleExists( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public static function getModuleIsInstalled( $module_name )
	{
		return static::getHandler()->getModuleIsInstalled( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public static function getModuleIsActivated( $module_name )
	{
		return static::getHandler()->getModuleIsActivated( $module_name );
	}


	/**
	 *
	 * @param string $module_name
	 * @param bool   $only_activated (optional, default: false)
	 *
	 * @return Application_Module_Manifest
	 */
	public static function getModuleManifest( $module_name, $only_activated = false )
	{
		return static::getHandler()->getModuleManifest( $module_name, $only_activated );
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
	 * Reloads module manifest
	 *
	 * @param string $module_name
	 */
	public static function reloadModuleManifest( $module_name )
	{
		static::getHandler()->reloadModuleManifest( $module_name );
	}


	/**
	 * Returns instance of the module base class
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 *
	 * @return Application_Module
	 */
	public static function getModuleInstance( $module_name )
	{
		return static::getHandler()->getModuleInstance( $module_name );
	}

	/**
	 * @return bool
	 */
	public static function getInstallationInProgress()
	{
		return static::getHandler()->getInstallationInProgress();
	}

	/**
	 * @return string
	 */
	public static function getInstallationInProgressModuleName()
	{
		return static::getHandler()->getInstallationInProgressModuleName();
	}

}