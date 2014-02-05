<?php
/**
 *
 *
 *
 * Base class for working with modules.
 *    - Getting an instance of the module
 *    - Getting a list of modules
 *    - Install / Uninstall
 *    - Activation / Deactivation
 *
 * Module name corresponds to a directory name in the directory ~/modules and corresponds to [a-zA-Z0-9]{3,50} format
 *
 * Module directory structure:
 *
 *  ~/application/modules/ModuleName
 *    |
 *    |- _install
 *    |  |- dictionaries
 *    |  |  \- [locale].php
 *    |  |- install.php
 *    |  \- uninstall.php
 *    |
 *    |- _tests
 *    |  \- [*_Test.php]
 *    |
 *    |- doc
 *    |  |- readme.html
 *    |  \- [*.html]
 *    |
 *    |- config
 *    |  \- config.php
 *    |
 *    |- public
 *    |  |- icons
 *    |  |  |- small
 *    |  |  |  |-module.png
 *    |  |  |  \- [*.png]
 *    |  |  |- normal
 *    |  |  |  |-module.png
 *    |  |  |  \- [*.png]
 *    |  |  |- large
 *    |  |  |  |-module.png
 *    |  |  |  \- [*.png]
 *    |  |  \- [*.png]
 *    |  \- [images and so on]
 *    |
 *    |- Controllers
 *    |  |- Standard.php
 *    |  |- AJAX.php
 *    |  |- SYS.php
 *    |  \- REST.php
 *    |
 *    |- JS
 *    |  |- Main.js
 *    |  \- [*.js]
 *    |
 *    |- views
 *    |     \- [*.phtml]
 *    |- layouts
 *    |     \- [*.phtml]
 *    |- Main.php
 *    |- Config.php @see Jet\Config
 *    |- [*.php]
 *    \- manifest.php ......................... @see Jet\Mvc_Modules_ModuleInfo
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Application
 * @subpackage Application_Modules
 */
namespace Jet;

class Application_Modules extends Object {

	/**
	 * @var Application_Modules_Handler_Abstract
	 */
	protected static $handler;

	/**
	 * @return Application_Modules_Handler_Abstract
	 */
	public static function getHandler() {
		if(!static::$handler) {
			$class_name = JET_APPLICATION_MODULES_HANDLER_CLASS_NAME;

			static::$handler = new $class_name(
				JET_MODULES_PATH,
				JET_APPLICATION_MODULES_LIST_PATH,
				JET_APPLICATION_MODULE_NAMESPACE,
				JET_APPLICATION_MODULE_MANIFEST_CLASS_NAME
			);
		}

		return static::$handler;
	}



	/**
	* Returns true if the module name correspond to a valid format
	*
	* @param string $module_name
	* @return bool
	*/
	public static function checkModuleNameFormat( $module_name ) {
		return static::getHandler()->checkModuleNameFormat($module_name);
	}


	/**
	 * @static
	 * Read installed modules list
	 *
	 * @throws Application_Modules_Exception
	 * @return Application_Modules_Module_Manifest[]
	 */
	public static function getInstalledModulesList() {
		return static::getHandler()->getInstalledModulesList();
	}

	/**
	 * Returns an array containing information on all modules
	 *
	 * @param bool $ignore_corrupted_modules
	 *
	 * @throws Application_Modules_Exception
	 *
	 * @return Application_Modules_Module_Manifest[]
	 */
	public static function getAllModulesList( $ignore_corrupted_modules=true ) {
		return static::getHandler()->getAllModulesList($ignore_corrupted_modules);
	}

	/**
	* Returns an array containing information on installed and activated modules
	*
	* @return Application_Modules_Module_Manifest[]
	*/
	public static function getActivatedModulesList() {
		return static::getHandler()->getActivatedModulesList();
	}

	/**
	* Returns true if module exists
	* Not decide whether the module is installed and active
	*
	* @param string $module_name
	*
	* @return bool
	*/
	public static function getModuleExists( $module_name ) {
		return static::getHandler()->getModuleExists( $module_name );
	}

	/**
	* Returns true if module exists and is installed (do not care about activation)
	*
	* @param string $module_name
	*
	* @return bool
	*/
	public static function getModuleIsInstalled( $module_name ) {
		return static::getHandler()->getModuleIsInstalled( $module_name );
	}

	/**
	 * Returns true if module exists and is installed and activated
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public static function getModuleIsActivated( $module_name ) {
		return static::getHandler()->getModuleIsActivated( $module_name );
	}


	/**
	* Returns information about the module
	* Not decide whether the module is installed and active
	*
	* @param string $module_name
	* @param bool $only_activated (optional, default: false)
	*
	* @return Application_Modules_Module_Manifest
	*/
	public static function getModuleManifest( $module_name, $only_activated=false ) {
		return static::getHandler()->getModuleManifest( $module_name, $only_activated );
	}

	/**
	 * Install module
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public static function installModule( $module_name ) {
		static::getHandler()->installModule( $module_name );
	}

	/**
	 * Uninstall module
	 *
	 * @param string $module_name
	 * @throws Application_Modules_Exception
	 */
	public static function uninstallModule( $module_name ) {
		static::getHandler()->uninstallModule( $module_name );
	}

	/**
	 * Activate module
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public static function activateModule( $module_name ) {
		static::getHandler()->activateModule( $module_name );
	}

	/**
	 * Deactivate module
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public static function deactivateModule( $module_name ) {
		static::getHandler()->deactivateModule( $module_name );
	}

	/**
	* Reloads module manifest
	*
	* @param string $module_name
	*/
	public static function reloadModuleManifest( $module_name ) {
		static::getHandler()->reloadModuleManifest( $module_name );
	}


	/**
	 * Returns instance of the module base class
	 *
	 * @param string $module_name
	 * @throws Application_Modules_Exception
	 *
	 * @return Application_Modules_Module_Abstract
	 */
	public static function getModuleInstance( $module_name ) {
		return static::getHandler()->getModuleInstance( $module_name );
	}

	/**
	 * @return bool
	 */
	public static function getInstallationInProgress() {
		return static::getHandler()->getInstallationInProgress();
	}

	/**
	 * @return string
	 */
	public static function getInstallationInProgressModuleName() {
		return static::getHandler()->getInstallationInProgressModuleName();
	}

}