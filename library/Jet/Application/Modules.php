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
 *    |  |- models
 *    |  |  \- [*.js]
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
 * @see Jet\Application/readme.txt
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Application_Modules
 */
namespace Jet;

class Application_Modules extends Object {
	const MODULE_NAMESPACE = "JetApplicationModule";
	const MODULE_CONFIG_FILE_PATH = "config/config.php";
	const MODULE_MANIFEST_FILE_PATH = "manifest.php";
	const MODULE_INSTALL_DIR = "_install/";
	const MODULE_INSTALL_SCRIPT_PATH = "_install/install.php";
	const MODULE_UNINSTALL_SCRIPT_PATH = "_install/uninstall.php";

	const MODULES_LIST_FILE_NAME = "modules_list.php";

	const MODULE_VIEWS_DIR = "views/";
	const MODULE_LAYOUTS_DIR = "layouts/";


	/**
	 *
	 * @var string
	 */
	protected static $custom_modules_list_file_path = NULL;

	/**
	*
	* @var Application_Modules_Module_Info[]
	*/
	protected static $activated_modules_list = NULL;


	/**
	*
	* @var Application_Modules_Module_Info[]
	*/
	protected static $installed_modules_list = NULL;

	/**
	*
	* @var Application_Modules_Module_Info[]
	*/
	protected static $all_modules_list = NULL;

	/**
	*
	* @var Application_Modules_Module_Abstract[]
	*/
	protected static $module_instance = array();

	/**
	 * Internal flag. Used in autoloader
	 *
	 * @var bool
	 */
	protected static $installation_in_progress = false;

	/**
	 * @var string|null
	 */
	protected static $installation_in_progress_module_name = null;


	/**
	 * It is possible to set custom data file file path
	 * For example: you need to set path to NFS shared dir on cluster
	 *
	 * @param string $path 
	 */
	public static function setModulesListFilePath( $path ) {
		static::$custom_modules_list_file_path = $path;
	}

	/**
	 * Returns data file path
	 *
	 * @return string
	 */
	public static function getModulesListFilePath() {
		if(static::$custom_modules_list_file_path) {
			return static::$custom_modules_list_file_path;
		}

		return JET_DATA_PATH . static::MODULES_LIST_FILE_NAME;
	}



	/**
	 * Write installed modules
	 *
	 */
	protected static function saveInstalledModulesList() {
		static::$all_modules_list = NULL;

		$data = new Data_Array(static::$installed_modules_list);

		IO_File::write(static::getModulesListFilePath(), "<?php\n return ".$data->export().";\n");
	}


	/**
	* Returns true if the module name correspond to a valid format
	*
	* @param string $module_name
	* @return bool
	*/
	public static function checkModuleNameFormat( $module_name ) {

		if(!preg_match('/^([a-zA-Z0-9\\\\]{3,50})$/', $module_name)) {
			return false;
		}
		if(strpos($module_name, "\\\\")!==false) {
			return false;
		}

		if($module_name[0]=="\\") {
			return false;
		}

		if( $module_name[strlen($module_name)-1]=="\\" ) {
			return false;
		}

		return true;
	}


	/**
	 * @static
	 * Read installed modules list
	 *
	 * @throws Application_Modules_Exception
	 * @return Application_Modules_Module_Info[]
	 */
	public static function getInstalledModulesList() {
		if(static::$installed_modules_list !== null) {
			return static::$installed_modules_list;
		}

		$path = static::getModulesListFilePath();
		if(!IO_File::exists($path)) {
			static::$installed_modules_list = array();
			return array();
		}

		if(!is_readable($path)){
			throw new Application_Modules_Exception(
				"Modules list data file '{$path}' is not readable.",
				Application_Modules_Exception::CODE_MODULES_LIST_NOT_FOUND
			);
		}

		/** @noinspection PhpIncludeInspection */
		static::$installed_modules_list = require $path;

		return static::$installed_modules_list;
	}

	/**
	 * Returns an array containing information on all modules
	 *
	 * @param bool $ignore_corrupted_modules
	 *
	 * @throws Application_Modules_Exception
	 *
	 * @return Application_Modules_Module_Info[]
	 */
	public static function getAllModulesList( $ignore_corrupted_modules=true ) {
		if(static::$all_modules_list !== NULL) {
			return static::$all_modules_list;
		}

		static::$all_modules_list = array();

		static::getInstalledModulesList();

		static::_readModulesList($ignore_corrupted_modules, JET_APPLICATION_MODULES_PATH, "");

		return static::$all_modules_list;
	}

	/**
	 * @param bool $ignore_corrupted_modules
	 * @param string $base_dir
	 * @param string $module_name_prefix
	 */
	protected static function _readModulesList( $ignore_corrupted_modules, $base_dir, $module_name_prefix ) {
		$modules = IO_Dir::getSubdirectoriesList( $base_dir );

		foreach( $modules as $module_dir ) {
			if( !IO_File::exists( $base_dir.$module_dir."/".static::MODULE_MANIFEST_FILE_PATH ) ) {

				$next_module_name_prefix = ($module_name_prefix) ?
							$module_name_prefix.$module_dir."\\"
							:
							$module_dir."\\";

				static::_readModulesList($ignore_corrupted_modules, $base_dir.$module_dir."/", $next_module_name_prefix);
				continue;
			}

			$module_name = $module_name_prefix.$module_dir;

			if(isset(static::$installed_modules_list[$module_name])) {
				static::$all_modules_list[$module_name] = static::$installed_modules_list[$module_name];
				continue;
			}

			if( $ignore_corrupted_modules ) {
				try {

					$module_info = Application_Factory::getModuleInfoInstance($module_name);

				} catch( Application_Modules_Exception $e ) {
					$module_info = null;
				}

			} else {
				$module_info = Application_Factory::getModuleInfoInstance($module_name);
			}

			if(!$module_info) {
				continue;
			}


			static::$all_modules_list[$module_name] = $module_info;
		}

	}

	/**
	* Returns an array containing information on installed and activated modules
	*
	* @return Application_Modules_Module_Info[]
	*/
	public static function getActivatedModulesList() {
		if( static::$activated_modules_list !== NULL) {
			return static::$activated_modules_list;
		}

		$installed_modules_list = static::getInstalledModulesList();
		static::$activated_modules_list = array();

		foreach($installed_modules_list as $module_name=>$module_info) {
			if($module_info->getIsActivated()) {
				static::$activated_modules_list[$module_name] = $module_info;
			}
		}

		return static::$activated_modules_list;
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

		if( static::$activated_modules_list === NULL) {
			static::getActivatedModulesList();
		}

		if(isset(static::$activated_modules_list[$module_name]) ) {
			return true;
		}

		if( static::$all_modules_list === NULL) {
			static::getAllModulesList();
		}

		if(isset(static::$all_modules_list[$module_name])){
			return true;
		}

		return false;
	}

	/**
	* Returns true if module exists and is installed (do not care about activation)
	*
	* @param string $module_name
	*
	* @return bool
	*/
	public static function getModuleIsInstalled( $module_name ) {
		if( static::$installed_modules_list === NULL) {
			static::getInstalledModulesList();
		}

		if( isset(static::$installed_modules_list[$module_name]) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Returns true if module exists and is installed and activated
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public static function getModuleIsActivated( $module_name ) {
		if( static::$activated_modules_list === NULL) {
			static::getActivatedModulesList();
		}

		if( isset(static::$activated_modules_list[$module_name]) ) {
			return true;
		} else {
			return false;
		}
	}


	/**
	* Returns information about the module
	* Not decide whether the module is installed and active
	*
	* @param string $module_name
	* @param bool $only_activated (optional, default: false)
	*
	* @return Application_Modules_Module_Info
	*/
	public static function getModuleInfo( $module_name, $only_activated=false ) {
		if( static::$activated_modules_list === NULL) {
			static::getActivatedModulesList();
		}

		if( isset(static::$activated_modules_list[$module_name]) ) {
			return static::$activated_modules_list[$module_name];
		}

		if(!$only_activated) {
			if( static::$all_modules_list === NULL) {
				static::getAllModulesList();
			}

			if( isset(static::$all_modules_list[$module_name]) ) {
				return static::$all_modules_list[$module_name];
			}
		}

		return NULL;
	}

	/**
	 * Install module
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public static function installModule( $module_name ) {
		static::_hardCheckModuleExists($module_name);

		$module_info = static::getModuleInfo($module_name);

		if( $module_info->getIsInstalled() ) {
			throw new Application_Modules_Exception(
				"Module '{$module_name}' is already installed",
				Application_Modules_Exception::CODE_MODULE_ALREADY_INSTALLED
			);
		}

		if(!$module_info->getIsCompatible()) {
			throw new Application_Modules_Exception(
				"Module '{$module_name}' (API version".$module_info->getAPIVersion().") is not compatible with this system version (API version".Version::getAPIVersionNumber().")",
				Application_Modules_Exception::CODE_MODULE_IS_NOT_COMPATIBLE
			);
		}

		$all_modules = static::getAllModulesList();

		$required_modules = array();

		foreach( $module_info->getRequire() as $required_module_name ) {

			if(
				!isset($all_modules[$required_module_name]) ||
				!$all_modules[$required_module_name]->getIsInstalled()
			) {
				$required_modules[] = $required_module_name;
			}

		}

		if( $required_modules ) {
			throw new Application_Modules_Exception(
				"The module '{$module_name}' requires these modules: ".implode(", ", $required_modules).". This module must be installed before.",
				Application_Modules_Exception::CODE_DEPENDENCIES_ERROR
			);
		}

		$module_dir = $module_info->getModuleDir();


		$module_info->setIsInstalled(true);

		static::$installed_modules_list[$module_name] = $module_info;
		static::saveInstalledModulesList();

		static::$installation_in_progress = true;
		static::$installation_in_progress_module_name = $module_name;

		$install_script = $module_dir . static::MODULE_INSTALL_SCRIPT_PATH;

		if(file_exists($install_script)) {
			try {
				/** @noinspection PhpIncludeInspection */
				require_once $install_script;
			} catch(Exception $e){
				throw new Application_Modules_Exception(
					"Error while processing installation script: ".$e->getMessage(),
					Application_Modules_Exception::CODE_FAILED_TO_INSTALL_MODULE
				);
			}
		}
		static::$installation_in_progress = false;
		static::$installation_in_progress_module_name = null;


	}

	/**
	 * Uninstall module
	 *
	 * @param string $module_name
	 * @throws Application_Modules_Exception
	 */
	public static function uninstallModule( $module_name ) {
		static::_hardCheckModuleExists($module_name);
		static::_checkModuleDependencies($module_name);

		$module_info = static::getModuleInfo($module_name);

		if( !$module_info->getIsInstalled() ) {
			throw new Application_Modules_Exception(
				"Module '{$module_name}' is not installed",
				Application_Modules_Exception::CODE_MODULE_IS_NOT_INSTALLED
			);
		}

		$module_dir = $module_info->getModuleDir();

		/** @noinspection PhpUnusedLocalVariableInspection */
		$install_dir = $module_dir . static::MODULE_INSTALL_DIR;
		$uninstall_script = $module_dir . static::MODULE_UNINSTALL_SCRIPT_PATH;

		static::$installation_in_progress = true;
		static::$installation_in_progress_module_name = $module_name;

		if(file_exists($uninstall_script)) {
			try {
				/** @noinspection PhpIncludeInspection */
				require_once $uninstall_script;
			} catch(Exception $e){
				throw new Application_Modules_Exception(
					"Error while processing uninstall script: ".$e->getMessage(),
					Application_Modules_Exception::CODE_FAILED_TO_UNINSTALL_MODULE
				);
			}
		}

		$module_info->setIsInstalled(false);
		$module_info->setIsActivated(false);
		if(isset(static::$activated_modules_list[$module_name])) {
			unset(static::$activated_modules_list[$module_name]);
		}
		unset(static::$installed_modules_list[$module_name]);

		static::$installation_in_progress = false;
		static::$installation_in_progress_module_name = null;
		static::saveInstalledModulesList();
	}

	/**
	 * Activate module
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 * @return void
	 */
	public static function activateModule( $module_name ) {
		static::_hardCheckModuleExists($module_name);

		$activated_modules = static::getActivatedModulesList();

		$required_modules = array();

		$module_info = static::getModuleInfo( $module_name );

		foreach( $module_info->getRequire() as $required_module_name ) {

			if( !isset($activated_modules[$required_module_name]) ) {
				$required_modules[] = $required_module_name;
			}

		}

		if( $required_modules ) {
			throw new Application_Modules_Exception(
				"The module requires these modules: ".implode(",", $required_modules).". They must be activated.",
				Application_Modules_Exception::CODE_DEPENDENCIES_ERROR
			);
		}

		$module_info = static::getModuleInfo($module_name);

		if( !$module_info->getIsInstalled() ) {
			throw new Application_Modules_Exception(
				"Module '{$module_name}' is not installed",
				Application_Modules_Exception::CODE_MODULE_IS_NOT_INSTALLED
			);
		}

		if( $module_info->getIsActivated() ) {
			return;
		}

		$module_info->setIsActivated(true);

		static::saveInstalledModulesList(static::$installed_modules_list);
		static::$activated_modules_list[$module_name] = $module_info;
	}

	/**
	 * Deactivate module
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 * @return void
	 */
	public static function deactivateModule( $module_name ) {
		static::_hardCheckModuleExists($module_name);
		static::_checkModuleDependencies($module_name);

		$module_info = static::getModuleInfo($module_name);

		if( !$module_info->getIsInstalled() ) {
			throw new Application_Modules_Exception(
				"Module '{$module_name}' is not installed",
				Application_Modules_Exception::CODE_MODULE_IS_NOT_INSTALLED
			);
		}

		if( !$module_info->getIsActivated() ) {
			return;
		}

		$module_info->setIsActivated(false);

		unset(static::$activated_modules_list[$module_name]);
		static::saveInstalledModulesList(static::$installed_modules_list);
	}

	/**
	* Reloads module manifest
	*
	* @param string $module_name
	*/
	public static function reloadModuleManifest( $module_name ) {
		static::_hardCheckModuleExists($module_name);

		$module_info = Application_Factory::getModuleInfoInstance($module_name);

		static::$all_modules_list[$module_name] = $module_info;

		if(isset(static::$activated_modules_list[$module_name])) {
			$module_info->setIsActivated(true);
			static::$activated_modules_list[$module_name] = $module_info;
		}

		if(isset(static::$installed_modules_list[$module_name])) {
			$module_info->setIsInstalled(true);
			static::$installed_modules_list[$module_name] = $module_info;
			
			static::saveInstalledModulesList();
		}

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
		if(isset(static::$module_instance[$module_name])) {
			return static::$module_instance[$module_name];
		}

		static::getActivatedModulesList();

		if(static::$installation_in_progress_module_name===$module_name) {
			$modules_list = static::getAllModulesList(true);
			$module_info = $modules_list[$module_name];
		} else {
			if( !isset(static::$activated_modules_list[$module_name]) ) {
				throw new Application_Modules_Exception(
					"'{$module_name}' module does not exist, is not installed or is not activated",
					Application_Modules_Exception::CODE_UNKNOWN_MODULE
				);
			}

			$module_info = static::$activated_modules_list[$module_name];
		}

		$module_dir = $module_info->getModuleDir();

		/** @noinspection PhpIncludeInspection */
		require_once $module_dir . "Main.php";

		$class_name = "\\".static::MODULE_NAMESPACE."\\".$module_name."\\Main";

		if(!class_exists($class_name)) {
			throw new Application_Modules_Exception(
				"Class '{$class_name}' does not exist",
				Application_Modules_Exception::CODE_ERROR_CREATING_MODULE_INSTANCE
			);
		}

		$module = new $class_name( $module_info );

		if( !$module instanceof Application_Modules_Module_Abstract ) {
			throw new Application_Modules_Exception(
				"Class '{$module_name}' is not instance of Jet\\Application_Modules_Module_Abstract",
				Application_Modules_Exception::CODE_ERROR_CREATING_MODULE_INSTANCE
			);
		}

		static::$module_instance[$module_name] = $module;
		return static::$module_instance[$module_name];
	}


	/**
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	protected static function _hardCheckModuleExists( $module_name ) {
		if( !static::checkModuleNameFormat($module_name) ) {
			throw new Application_Modules_Exception(
				"Module name '{$module_name}' is not valid ([a-zA-Z0-9] {3,50}) ",
				Application_Modules_Exception::CODE_MODULE_NAME_FORMAT_IS_NOT_VALID
			);
		}

		if( static::$all_modules_list === NULL) {
			static::getAllModulesList();
		}

		if( !isset(static::$all_modules_list[$module_name]) ) {
			throw new Application_Modules_Exception(
				"Module '{$module_name}' doesn't exist ",
				Application_Modules_Exception::CODE_MODULE_DOES_NOT_EXIST
			);
		}
	}


	/**
	 * Checks module dependencies before uninstalling or deactivating
	 *
	 * @param string $module_name
	 * @throws Application_Modules_Exception
	 */
	protected static function _checkModuleDependencies( $module_name ) {
		$activated_modules = static::getActivatedModulesList();

		$dependent_modules = array();

		foreach( $activated_modules as $d_module_name => $module_info ) {
			if( $d_module_name==$module_name ) {
				continue;
			}

			if(in_array( $module_name, $module_info->getRequire() )) {
				$dependent_modules[] = $d_module_name;
			}
		}

		if( $dependent_modules ) {
			throw new Application_Modules_Exception(
				"{$module_name} module is required for ".implode(",", $dependent_modules),
				Application_Modules_Exception::CODE_DEPENDENCIES_ERROR
			);
		}

	}

	/**
	 * @return bool
	 */
	public static function getInstallationInProgress() {
		return static::$installation_in_progress;
	}

	/**
	 * @return string
	 */
	public static function getInstallationInProgressModuleName() {
		return static::$installation_in_progress_module_name;
	}

	/**
	 * For tests only
	 */
	public static function _resetInternalState() {
		static::$custom_modules_list_file_path = NULL;
		static::$activated_modules_list = NULL;
		static::$installed_modules_list = NULL;
		static::$all_modules_list = NULL;
		static::$module_instance = array();
		static::$installation_in_progress = false;

	}
}