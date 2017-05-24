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
class Application_Modules_Handler_Default extends Application_Modules_Handler
{

	/**
	 * @var string
	 */
	protected $module_manifest_file_name = 'manifest.php';

	/**
	 * @var string
	 */
	protected $modules_basedir = '';

	/**
	 * @var string
	 */
	protected $modules_list_file_path = JET_PATH_DATA.'modules_list.php';

	/**
	 * @var string
	 */
	protected $modules_namespace = '';

	/**
	 * @var string
	 */
	protected $manifest_class_name = '';

	/**
	 *
	 * @var Application_Module_Manifest[]
	 */
	protected $activated_modules_list = null;


	/**
	 *
	 * @var Application_Module_Manifest[]
	 */
	protected $installed_modules_list = null;

	/**
	 *
	 * @var Application_Module_Manifest[]
	 */
	protected $all_modules_list = null;

	/**
	 *
	 * @var Application_Module[]
	 */
	protected $module_instance = [];

	/**
	 * Internal flag. Used in autoloader
	 *
	 * @var bool
	 */
	protected $installation_in_progress = false;

	/**
	 * @var string|null
	 */
	protected $installation_in_progress_module_name = null;

	/**
	 * @param string $modules_base_path
	 * @param string $modules_namespace
	 * @param string $manifest_class_name
	 */
	public function __construct( $modules_base_path, $modules_namespace, $manifest_class_name )
	{
		$this->modules_basedir = $modules_base_path;
		$this->modules_namespace = $modules_namespace;
		$this->manifest_class_name = $manifest_class_name;
	}

	/**
	 * @return string
	 */
	public function getModuleManifestFileName()
	{
		return $this->module_manifest_file_name;
	}

	/**
	 * @param string $module_manifest_file_name
	 */
	public function setModuleManifestFileName( $module_manifest_file_name )
	{
		$this->module_manifest_file_name = $module_manifest_file_name;
	}

	/**
	 * @return string
	 */
	public function getModulesBasedir()
	{
		return $this->modules_basedir;
	}

	/**
	 * @param string $modules_basedir
	 */
	public function setModulesBasedir( $modules_basedir )
	{
		$this->modules_basedir = $modules_basedir;
	}

	/**
	 * @return string
	 */
	public function getModulesListFilePath()
	{
		return $this->modules_list_file_path;
	}

	/**
	 * @param string $modules_list_file_path
	 */
	public function setModulesListFilePath( $modules_list_file_path )
	{
		$this->modules_list_file_path = $modules_list_file_path;
	}

	/**
	 * @return string
	 */
	public function getModulesNamespace()
	{
		return $this->modules_namespace;
	}

	/**
	 * @param string $modules_namespace
	 */
	public function setModulesNamespace( $modules_namespace )
	{
		$this->modules_namespace = $modules_namespace;
	}

	/**
	 * @return string
	 */
	public function getManifestClassName()
	{
		return $this->manifest_class_name;
	}

	/**
	 * @param string $manifest_class_name
	 */
	public function setManifestClassName( $manifest_class_name )
	{
		$this->manifest_class_name = $manifest_class_name;
	}

	/**
	 * @return bool
	 */
	public function getInstallationInProgress()
	{
		return $this->installation_in_progress;
	}

	/**
	 * @return string
	 */
	public function getInstallationInProgressModuleName()
	{
		return $this->installation_in_progress_module_name;
	}

	/**
	 * Returns true if module exists
	 * Not decide whether the module is installed and active
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public function getModuleExists( $module_name )
	{

		if( $this->activated_modules_list===null ) {
			$this->getActivatedModulesList();
		}

		if( isset( $this->activated_modules_list[$module_name] ) ) {
			return true;
		}

		if( $this->all_modules_list===null ) {
			$this->getAllModulesList();
		}

		if( isset( $this->all_modules_list[$module_name] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns an array containing information on installed and activated modules
	 *
	 * @return Application_Module_Manifest[]
	 */
	public function getActivatedModulesList()
	{
		if( $this->activated_modules_list!==null ) {
			return $this->activated_modules_list;
		}

		$installed_modules_list = $this->getInstalledModulesList();
		$this->activated_modules_list = [];

		foreach( $installed_modules_list as $module_name => $module_manifest ) {
			/**
			 * @var Application_Module_Manifest $module_manifest
			 */
			if( $module_manifest->getIsActivated() ) {
				$this->activated_modules_list[$module_name] = $module_manifest;
			}
		}

		return $this->activated_modules_list;
	}

	/**
	 * Read installed modules list
	 *
	 * @throws Application_Modules_Exception
	 * @return Application_Module_Manifest[]
	 */
	public function getInstalledModulesList()
	{
		if( $this->installed_modules_list!==null ) {
			return $this->installed_modules_list;
		}

		$path = $this->modules_list_file_path;

		if( !IO_File::exists( $path ) ) {
			$this->installed_modules_list = [];

			return [];
		}

		if( !is_readable( $path ) ) {
			throw new Application_Modules_Exception(
				'Modules list data file \''.$path.'\' is not readable.',
				Application_Modules_Exception::CODE_MODULES_LIST_NOT_FOUND
			);
		}

		/** @noinspection PhpIncludeInspection */
		$this->installed_modules_list = require $path;

		return $this->installed_modules_list;
	}

	/**
	 *
	 * @param bool $ignore_corrupted_modules
	 *
	 * @throws Application_Modules_Exception
	 *
	 * @return Application_Module_Manifest[]
	 */
	public function getAllModulesList( $ignore_corrupted_modules = true )
	{
		if( $this->all_modules_list!==null ) {
			return $this->all_modules_list;
		}

		$this->all_modules_list = [];

		$this->getInstalledModulesList();

		$this->_readModulesList( $ignore_corrupted_modules, $this->modules_basedir, '' );

		return $this->all_modules_list;
	}

	/**
	 * @param bool   $ignore_corrupted_modules
	 * @param string $base_dir
	 * @param string $module_name_prefix
	 */
	protected function _readModulesList( $ignore_corrupted_modules, $base_dir, $module_name_prefix )
	{
		$modules = IO_Dir::getSubdirectoriesList( $base_dir );


		foreach( $modules as $module_dir ) {
			if( !IO_File::exists( $base_dir.$module_dir.'/'.$this->getModuleManifestFileName() ) ) {

				$next_module_name_prefix = ( $module_name_prefix ) ? $module_name_prefix.$module_dir.'\\' :
					$module_dir.'\\';

				$this->_readModulesList(
					$ignore_corrupted_modules, $base_dir.$module_dir.'/', $next_module_name_prefix
				);
				continue;
			}

			$module_name = str_replace( '\\', '.', $module_name_prefix.$module_dir );

			if( isset( $this->installed_modules_list[$module_name] ) ) {
				$this->all_modules_list[$module_name] = $this->installed_modules_list[$module_name];
				continue;
			}


			if( $ignore_corrupted_modules ) {
				try {

					$module_manifest = new $this->manifest_class_name( $module_name );

				} catch( Application_Modules_Exception $e ) {
					$module_manifest = null;
				}

			} else {
				$module_manifest = new $this->manifest_class_name( $module_name );
			}

			if( !$module_manifest ) {
				continue;
			}


			$this->all_modules_list[$module_name] = $module_manifest;
		}

	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public function getModuleIsInstalled( $module_name )
	{

		if( $this->installed_modules_list===null ) {
			$this->getInstalledModulesList();
		}

		if( isset( $this->installed_modules_list[$module_name] ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public function getModuleIsActivated( $module_name )
	{

		if( $this->activated_modules_list===null ) {
			$this->getActivatedModulesList();
		}

		if( isset( $this->activated_modules_list[$module_name] ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public function installModule( $module_name )
	{

		$this->_hardCheckModuleExists( $module_name );

		$module_manifest = $this->getModuleManifest( $module_name );

		if( $module_manifest->getIsInstalled() ) {
			throw new Application_Modules_Exception(
				'Module \''.$module_name.'\' is already installed',
				Application_Modules_Exception::CODE_MODULE_ALREADY_INSTALLED
			);
		}

		if( !$module_manifest->getIsCompatible() ) {
			throw new Application_Modules_Exception(
				'Module \''.$module_name.'\' (API version '.$module_manifest->getAPIVersion(
				).') is not compatible with this system version (API version'.Version::getAPIVersionNumber().')',
				Application_Modules_Exception::CODE_MODULE_IS_NOT_COMPATIBLE
			);
		}

		$all_modules = $this->getAllModulesList();

		$required_modules = [];

		foreach( $module_manifest->getRequire() as $required_module_name ) {

			if(
				!isset( $all_modules[$required_module_name] ) ||
				!$all_modules[$required_module_name]->getIsInstalled()
			) {
				$required_modules[] = $required_module_name;
			}

		}

		if( $required_modules ) {
			throw new Application_Modules_Exception(
				'The module \''.$module_name.'\' requires these modules: '.implode(
					', ', $required_modules
				).'. This module must be installed before.', Application_Modules_Exception::CODE_DEPENDENCIES_ERROR
			);
		}

		$this->installation_in_progress = true;
		$this->installation_in_progress_module_name = $module_name;

		try {

			$this->getModuleInstance( $module_name )->install();

		} catch( \Exception $e ) {
			$this->installation_in_progress = false;
			$this->installation_in_progress_module_name = null;

			throw new Application_Modules_Exception(
				$e->getMessage(), Application_Modules_Exception::CODE_FAILED_TO_INSTALL_MODULE
			);

		}

		$this->installation_in_progress = false;
		$this->installation_in_progress_module_name = null;

		$module_manifest->setIsInstalled( true );

		$this->installed_modules_list[$module_name] = $module_manifest;
		$this->_saveInstalledModulesList();

	}

	/**
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	protected function _hardCheckModuleExists( $module_name )
	{
		if( !$this->checkModuleNameFormat( $module_name ) ) {
			throw new Application_Modules_Exception(
				'Module name \''.$module_name.'\' is not valid ( ^([a-zA-Z0-9\.]{3,50})$ ) ',
				Application_Modules_Exception::CODE_MODULE_NAME_FORMAT_IS_NOT_VALID
			);
		}

		if( $this->all_modules_list===null ) {
			$this->getAllModulesList();
		}

		if( !isset( $this->all_modules_list[$module_name] ) ) {
			throw new Application_Modules_Exception(
				'Module \''.$module_name.'\' does not exist ', Application_Modules_Exception::CODE_MODULE_DOES_NOT_EXIST
			);
		}
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public function checkModuleNameFormat( $module_name )
	{

		if( !preg_match( '/^([a-zA-Z0-9\.]{3,50})$/', $module_name ) ) {
			return false;
		}
		if( strpos( $module_name, '..' )!==false ) {
			return false;
		}

		if( $module_name[0]=='.' ) {
			return false;
		}

		if( $module_name[strlen( $module_name )-1]=='.' ) {
			return false;
		}

		return true;
	}

	/**
	 *
	 * @param string $module_name
	 * @param bool   $only_activated (optional, default: false)
	 *
	 * @return Application_Module_Manifest
	 */
	public function getModuleManifest( $module_name, $only_activated = false )
	{

		if( $this->activated_modules_list===null ) {
			$this->getActivatedModulesList();
		}

		if( isset( $this->activated_modules_list[$module_name] ) ) {
			return $this->activated_modules_list[$module_name];
		}

		if( !$only_activated ) {
			if( $this->all_modules_list===null ) {
				$this->getAllModulesList();
			}

			if( isset( $this->all_modules_list[$module_name] ) ) {
				return $this->all_modules_list[$module_name];
			}
		}

		return null;
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 *
	 * @return Application_Module
	 */
	public function getModuleInstance( $module_name )
	{

		if( isset( $this->module_instance[$module_name] ) ) {
			return $this->module_instance[$module_name];
		}

		$this->getActivatedModulesList();

		if( $this->installation_in_progress_module_name===$module_name ) {
			$modules_list = $this->getAllModulesList( true );
			$module_manifest = $modules_list[$module_name];
		} else {
			if( !isset( $this->activated_modules_list[$module_name] ) ) {
				throw new Application_Modules_Exception(
					'Module \''.$module_name.'\' does not exist, is not installed or is not activated',
					Application_Modules_Exception::CODE_UNKNOWN_MODULE
				);
			}

			$module_manifest = $this->activated_modules_list[$module_name];
		}

		$module_dir = $module_manifest->getModuleDir();

		/** @noinspection PhpIncludeInspection */
		require_once $module_dir.'Main.php';

		$class_name = $module_manifest->getNamespace().'Main';

		if( !class_exists( $class_name ) ) {
			throw new Application_Modules_Exception(
				'Class \''.$class_name.'\' does not exist',
				Application_Modules_Exception::CODE_ERROR_CREATING_MODULE_INSTANCE
			);
		}

		$module = new $class_name( $module_manifest );

		if( !$module instanceof Application_Module ) {
			throw new Application_Modules_Exception(
				'Class \''.$module_name.'\' is not instance of '.__NAMESPACE__.'\Application_Modules_Module_Abstract',
				Application_Modules_Exception::CODE_ERROR_CREATING_MODULE_INSTANCE
			);
		}

		$this->module_instance[$module_name] = $module;

		return $this->module_instance[$module_name];
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public function uninstallModule( $module_name )
	{

		$this->_hardCheckModuleExists( $module_name );
		$this->_checkModuleDependencies( $module_name );

		$module_manifest = $this->getModuleManifest( $module_name );

		if( !$module_manifest->getIsInstalled() ) {
			throw new Application_Modules_Exception(
				'Module \''.$module_name.'\' is not installed',
				Application_Modules_Exception::CODE_MODULE_IS_NOT_INSTALLED
			);
		}


		$this->installation_in_progress = true;
		$this->installation_in_progress_module_name = $module_name;

		/**
		 * @var Application_Modules_Exception $uninstall_exception
		 */
		$uninstall_exception = null;

		try {

			$this->getModuleInstance( $module_name )->uninstall();

		} catch( \Exception $e ) {
			$uninstall_exception = new Application_Modules_Exception(
				$e->getMessage(), Application_Modules_Exception::CODE_FAILED_TO_UNINSTALL_MODULE
			);
		}

		$module_manifest->setIsInstalled( false );
		$module_manifest->setIsActivated( false );

		if( isset( $this->activated_modules_list[$module_name] ) ) {
			unset( $this->activated_modules_list[$module_name] );
		}

		unset( $this->installed_modules_list[$module_name] );

		$this->installation_in_progress = false;
		$this->installation_in_progress_module_name = null;
		$this->_saveInstalledModulesList();

		if( $uninstall_exception ) {
			throw $uninstall_exception;
		}
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	protected function _checkModuleDependencies( $module_name )
	{
		$activated_modules = $this->getActivatedModulesList();

		$dependent_modules = [];

		foreach( $activated_modules as $d_module_name => $module_manifest ) {
			/**
			 * @var Application_Module_Manifest $module_manifest
			 */
			if( $d_module_name==$module_name ) {
				continue;
			}

			if( in_array( $module_name, $module_manifest->getRequire() ) ) {
				$dependent_modules[] = $d_module_name;
			}
		}

		if( $dependent_modules ) {
			throw new Application_Modules_Exception(
				'Module \''.$module_name.'\' is required for '.implode( ',', $dependent_modules ),
				Application_Modules_Exception::CODE_DEPENDENCIES_ERROR
			);
		}

	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public function activateModule( $module_name )
	{

		$this->_hardCheckModuleExists( $module_name );

		$activated_modules = $this->getActivatedModulesList();

		$required_modules = [];

		$module_manifest = $this->getModuleManifest( $module_name );

		foreach( $module_manifest->getRequire() as $required_module_name ) {

			if( !isset( $activated_modules[$required_module_name] ) ) {
				$required_modules[] = $required_module_name;
			}

		}

		if( $required_modules ) {
			throw new Application_Modules_Exception(
				'The module requires these modules: '.implode( ',', $required_modules ).'. They must be activated.',
				Application_Modules_Exception::CODE_DEPENDENCIES_ERROR
			);
		}

		$module_manifest = $this->getModuleManifest( $module_name );

		if( !$module_manifest->getIsInstalled() ) {
			throw new Application_Modules_Exception(
				'Module \''.$module_name.'\' is not installed',
				Application_Modules_Exception::CODE_MODULE_IS_NOT_INSTALLED
			);
		}

		if( $module_manifest->getIsActivated() ) {
			return;
		}

		$module_manifest->setIsActivated( true );

		$this->_saveInstalledModulesList();
		$this->activated_modules_list[$module_name] = $module_manifest;
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public function deactivateModule( $module_name )
	{

		$this->_hardCheckModuleExists( $module_name );
		$this->_checkModuleDependencies( $module_name );

		$module_manifest = $this->getModuleManifest( $module_name );

		if( !$module_manifest->getIsInstalled() ) {
			throw new Application_Modules_Exception(
				'Module \''.$module_name.'\' is not installed',
				Application_Modules_Exception::CODE_MODULE_IS_NOT_INSTALLED
			);
		}

		if( !$module_manifest->getIsActivated() ) {
			return;
		}

		$module_manifest->setIsActivated( false );

		unset( $this->activated_modules_list[$module_name] );
		$this->_saveInstalledModulesList();
	}

	/**
	 *
	 * @param string $module_name
	 */
	public function reloadModuleManifest( $module_name )
	{

		$this->_hardCheckModuleExists( $module_name );

		/**
		 * @var Application_Module_Manifest $module_manifest
		 */
		$module_manifest = new $this->manifest_class_name( $module_name );

		$this->all_modules_list[$module_name] = $module_manifest;

		if( isset( $this->activated_modules_list[$module_name] ) ) {
			$module_manifest->setIsActivated( true );
			$this->activated_modules_list[$module_name] = $module_manifest;
		}

		if( isset( $this->installed_modules_list[$module_name] ) ) {
			$module_manifest->setIsInstalled( true );
			$this->installed_modules_list[$module_name] = $module_manifest;

			$this->_saveInstalledModulesList();
		}

	}

	/**
	 *
	 */
	protected function _saveInstalledModulesList()
	{
		$this->all_modules_list = null;

		IO_File::write(
			$this->modules_list_file_path,
			'<?php'.JET_EOL.' return '.var_export( $this->installed_modules_list, true ).';'.JET_EOL
		);
	}


}