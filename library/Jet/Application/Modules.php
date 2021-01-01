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
class Application_Modules extends BaseObject
{

	/**
	 * @var ?string
	 */
	protected static ?string $base_path = null;

	/**
	 * @var string
	 */
	protected static string $module_root_namespace = 'JetApplicationModule';


	/**
	 * @var ?Application_Modules_Handler
	 */
	protected static ?Application_Modules_Handler $handler = null;


	/**
	 * @return string
	 */
	public static function getModuleRootNamespace() : string
	{
		return static::$module_root_namespace;
	}

	/**
	 * @param string $module_root_namespace
	 */
	public static function setModuleRootNamespace( string $module_root_namespace ) : void
	{
		static::$module_root_namespace = $module_root_namespace;
	}

	/**
	 * @param string $module_name
	 *
	 * @return string
	 */
	public static function getModuleDir( string $module_name ) : string
	{
		return static::getBasePath().str_replace( '.', '/', $module_name).'/';
	}


	/**
	 * @return Application_Modules_Handler
	 */
	public static function getHandler() : Application_Modules_Handler
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
	public static function getBasePath() : string
	{
		if(!static::$base_path) {
			static::$base_path = SysConf_PATH::APPLICATION().'Modules/';
		}
		return static::$base_path;
	}

	/**
	 * @param string $base_path
	 */
	public static function setBasePath( string $base_path ) : void
	{
		static::$base_path = $base_path;
	}


	/**
	 *
	 * @throws Application_Modules_Exception
	 * @return Application_Module_Manifest[]
	 */
	public static function installedModulesList() : array
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
	public static function allModulesList() : array
	{
		return static::getHandler()->allModulesList();
	}

	/**
	 *
	 * @return Application_Module_Manifest[]
	 */
	public static function activatedModulesList() : array
	{
		return static::getHandler()->activatedModulesList();
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public static function moduleExists( string $module_name ) : bool
	{
		return static::getHandler()->moduleExists( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public static function moduleIsInstalled( string $module_name ) : bool
	{
		return static::getHandler()->moduleIsInstalled( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public static function moduleIsActivated( string $module_name ) : bool
	{
		return static::getHandler()->moduleIsActivated( $module_name );
	}


	/**
	 *
	 * @param string $module_name
	 *
	 * @return Application_Module_Manifest
	 */
	public static function moduleManifest( string $module_name ) : Application_Module_Manifest
	{
		return static::getHandler()->moduleManifest( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public static function installModule( string $module_name ) : void
	{
		static::getHandler()->installModule( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public static function uninstallModule( string $module_name ) : void
	{
		static::getHandler()->uninstallModule( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public static function activateModule( string $module_name ) : void
	{
		static::getHandler()->activateModule( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public static function deactivateModule( string $module_name ) : void
	{
		static::getHandler()->deactivateModule( $module_name );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return Application_Module
	 */
	public static function moduleInstance( string $module_name ) : Application_Module
	{
		return static::getHandler()->moduleInstance( $module_name );
	}


}