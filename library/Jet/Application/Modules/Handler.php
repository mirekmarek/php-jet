<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
abstract class Application_Modules_Handler extends BaseObject
{
	/**
	 *
	 *
	 * @throws Application_Modules_Exception
	 *
	 * @return Application_Module_Manifest[]
	 */
	abstract public function allModulesList() : array;

	/**
	 *
	 * @throws Application_Modules_Exception
	 * @return Application_Module_Manifest[]
	 */
	abstract public function installedModulesList() : array;

	/**
	 *
	 * @return Application_Module_Manifest[]
	 */
	abstract public function activatedModulesList() : array;

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	abstract public function moduleExists( string $module_name ) : bool;

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	abstract public function moduleIsInstalled( string $module_name ) : bool;

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	abstract public function moduleIsActivated( string $module_name ) : bool;

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	abstract public function installModule( string $module_name ) : void;

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	abstract public function uninstallModule( string $module_name ) : void;

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	abstract public function activateModule( string $module_name ) : void;

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	abstract public function deactivateModule( string $module_name ) : void;


	/**
	 *
	 * @param string $module_name
	 *
	 * @return Application_Module_Manifest
	 */
	abstract public function moduleManifest( string $module_name ) : Application_Module_Manifest;

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 *
	 * @return Application_Module
	 */
	abstract public function moduleInstance( string $module_name ) : Application_Module;

}