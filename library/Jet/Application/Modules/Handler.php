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
abstract class Application_Modules_Handler extends BaseObject
{


	/**
	 * @param string $modules_base_path
	 * @param string $modules_namespace
	 * @param string $manifest_class_name
	 */
	abstract function __construct( $modules_base_path, $modules_namespace, $manifest_class_name );


	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	abstract public function checkModuleNameFormat( $module_name );

	/**
	 * @return bool
	 */
	abstract public function getInstallationInProgress();

	/**
	 * @return string
	 */
	abstract public function getInstallationInProgressModuleName();


	/**
	 * Read installed modules list
	 *
	 * @throws Application_Modules_Exception
	 * @return Application_Module_Manifest[]
	 */
	abstract public function getInstalledModulesList();

	/**
	 *
	 * @param bool $ignore_corrupted_modules
	 *
	 * @throws Application_Modules_Exception
	 *
	 * @return Application_Module_Manifest[]
	 */
	abstract public function getAllModulesList( $ignore_corrupted_modules = true );

	/**
	 *
	 * @return Application_Module_Manifest[]
	 */
	abstract public function getActivatedModulesList();

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	abstract public function getModuleExists( $module_name );

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	abstract public function getModuleIsInstalled( $module_name );

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	abstract public function getModuleIsActivated( $module_name );


	/**
	 *
	 * @param string $module_name
	 * @param bool   $only_activated (optional, default: false)
	 *
	 * @return Application_Module_Manifest
	 */
	abstract public function getModuleManifest( $module_name, $only_activated = false );

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	abstract public function installModule( $module_name );

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	abstract public function uninstallModule( $module_name );

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	abstract public function activateModule( $module_name );

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	abstract public function deactivateModule( $module_name );

	/**
	 *
	 * @param string $module_name
	 */
	abstract public function reloadModuleManifest( $module_name );


	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 *
	 * @return Application_Module
	 */
	abstract public function getModuleInstance( $module_name );

}