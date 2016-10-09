<?php
/**
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Application
 * @subpackage Application_Modules
 */
namespace Jet;

abstract class Application_Modules_Handler_Abstract extends BaseObject {



	/**
	 * @param string $modules_basedir
	 * @param string $modules_list_file_path
	 * @param string $modules_namespace
	 * @param string $manifest_class_name
	 */
	abstract function __construct( $modules_basedir, $modules_list_file_path, $modules_namespace, $manifest_class_name );


	/**
	 * Returns true if the module name correspond to a valid format
	 *
	 * @param string $module_name
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
	 * @return Application_Modules_Module_Manifest[]
	 */
	abstract public function getInstalledModulesList();

	/**
	 * Returns an array containing information on all modules
	 *
	 * @param bool $ignore_corrupted_modules
	 *
	 * @throws Application_Modules_Exception
	 *
	 * @return Application_Modules_Module_Manifest[]
	 */
	abstract public function getAllModulesList( $ignore_corrupted_modules=true );

	/**
	 * Returns an array containing information on installed and activated modules
	 *
	 * @return Application_Modules_Module_Manifest[]
	 */
	abstract public function getActivatedModulesList();

	/**
	 * Returns true if module exists
	 * Not decide whether the module is installed and active
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	abstract public function getModuleExists( $module_name );

	/**
	 * Returns true if module exists and is installed (do not care about activation)
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	abstract public function getModuleIsInstalled( $module_name );

	/**
	 * Returns true if module exists and is installed and activated
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	abstract public function getModuleIsActivated( $module_name );


	/**
	 * Returns information about the module
	 * Not decide whether the module is installed and active
	 *
	 * @param string $module_name
	 * @param bool $only_activated (optional, default: false)
	 *
	 * @return Application_Modules_Module_Manifest
	 */
	abstract public function getModuleManifest( $module_name, $only_activated=false );

	/**
	 * Install module
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	abstract public function installModule( $module_name );

	/**
	 * Uninstall module
	 *
	 * @param string $module_name
	 * @throws Application_Modules_Exception
	 */
	abstract public function uninstallModule( $module_name );

	/**
	 * Activate module
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	abstract public function activateModule( $module_name );

	/**
	 * Deactivate module
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	abstract public function deactivateModule( $module_name );

	/**
	 * Reloads module manifest
	 *
	 * @param string $module_name
	 */
	abstract public function reloadModuleManifest( $module_name );


	/**
	 * Returns instance of the module base class
	 *
	 * @param string $module_name
	 * @throws Application_Modules_Exception
	 *
	 * @return Application_Modules_Module_Abstract
	 */
	abstract public function getModuleInstance( $module_name );

}