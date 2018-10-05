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
	protected $installed_modules_list_file_path = JET_PATH_DATA.'installed_modules_list.php';
	/**
	 * @var string
	 */
	protected $activated_modules_list_file_path = JET_PATH_DATA.'activated_modules_list.php';

	/**
	 *
	 * @var array
	 */
	protected $activated_modules_list;


	/**
	 *
	 * @var array
	 */
	protected $installed_modules_list;

	/**
	 *
	 * @var array
	 */
	protected $all_modules_list;

	/**
	 * @var Application_Module_Manifest[]
	 */
	protected $module_manifest = [];

	/**
	 *
	 * @var Application_Module[]
	 */
	protected $module_instance = [];

	/**
	 *
	 */
	public function __construct()
	{

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
	public function getInstalledModulesListFilePath()
	{
		return $this->installed_modules_list_file_path;
	}

	/**
	 * @param string $installed_modules_list_file_path
	 */
	public function setInstalledModulesListFilePath( $installed_modules_list_file_path )
	{
		$this->installed_modules_list_file_path = $installed_modules_list_file_path;
	}

	/**
	 * @return string
	 */
	public function getActivatedModulesListFilePath()
	{
		return $this->activated_modules_list_file_path;
	}

	/**
	 * @param string $activated_modules_list_file_path
	 */
	public function setActivatedModulesListFilePath( $activated_modules_list_file_path )
	{
		$this->activated_modules_list_file_path = $activated_modules_list_file_path;
	}


	/**
	 * Returns true if module exists
	 * Not decide whether the module is installed and active
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public function moduleExists( $module_name )
	{
		if( $this->all_modules_list===null ) {
			$this->allModulesList();
		}

		return in_array($module_name, $this->all_modules_list);
	}

	/**
	 *
	 * @return Application_Module_Manifest[]
	 */
	public function activatedModulesList()
	{
		$this->_readActivatedModulesList();

		$res = [];

		foreach( $this->activated_modules_list as $module_name ) {
			$res[$module_name] = $this->moduleManifest( $module_name );

		}

		return $res;
	}

	/**
	 *
	 *
	 * @return Application_Module_Manifest[]
	 */
	public function installedModulesList()
	{
		$this->_readInstalledModulesList();

		$res = [];

		foreach( $this->installed_modules_list as $module_name ) {
			$res[$module_name] = $this->moduleManifest( $module_name );

		}

		return $this->installed_modules_list;
	}

	/**
	 *
	 *
	 *
	 * @return Application_Module_Manifest[]
	 */
	public function allModulesList()
	{
		if( $this->all_modules_list===null ) {
			$this->all_modules_list = [];


			$this->_readModulesList( Application_Modules::getBasePath(), '' );

			$this->_readActivatedModulesList();
			$this->_readInstalledModulesList();
		}

		$res = [];

		foreach( $this->all_modules_list as $module_name ) {
			$res[$module_name] = $this->moduleManifest( $module_name );
		}

		return $res;
	}

	/**
	 * @param string $base_dir
	 * @param string $module_name_prefix
	 */
	protected function _readModulesList( $base_dir, $module_name_prefix )
	{
		$modules = IO_Dir::getSubdirectoriesList( $base_dir );

		$manifest_class_name = Application_Factory::getModuleManifestClassName();

		foreach( $modules as $module_dir ) {

			if( !IO_File::exists( $base_dir.$module_dir.'/'.$this->getModuleManifestFileName() ) ) {

				$next_module_name_prefix = ( $module_name_prefix ) ?
					$module_name_prefix.$module_dir.'\\'
					:
					$module_dir.'\\';

				$this->_readModulesList(
					$base_dir.$module_dir.'/', $next_module_name_prefix
				);
				continue;
			}

			$module_name = str_replace( '\\', '.', $module_name_prefix.$module_dir );

			$module_manifest = new $manifest_class_name( $module_name );

			if( !$module_manifest ) {
				continue;
			}


			$this->all_modules_list[] = $module_name;

			$this->module_manifest[$module_name] = $module_manifest;
		}

	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public function moduleIsInstalled( $module_name )
	{

		$this->_readInstalledModulesList();

		return in_array($module_name, $this->installed_modules_list );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public function moduleIsActivated( $module_name )
	{

		$this->_readActivatedModulesList();

		return in_array( $module_name, $this->activated_modules_list );
	}




	/**
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	protected function _checkModuleExists( $module_name )
	{

		$this->allModulesList();

		if( !in_array( $module_name, $this->all_modules_list ) ) {
			throw new Application_Modules_Exception(
				'Module \''.$module_name.'\' does not exist ', Application_Modules_Exception::CODE_MODULE_DOES_NOT_EXIST
			);
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

		$this->_checkModuleExists( $module_name );

		$module_manifest = $this->moduleManifest( $module_name );

		if( $module_manifest->isInstalled() ) {
			throw new Application_Modules_Exception(
				'Module \''.$module_name.'\' is already installed',
				Application_Modules_Exception::CODE_MODULE_ALREADY_INSTALLED
			);
		}

		if( !$module_manifest->isCompatible() ) {
			throw new Application_Modules_Exception(
				'Module \''.$module_name.'\' is not compatible with this system.',
				Application_Modules_Exception::CODE_MODULE_IS_NOT_COMPATIBLE
			);
		}


		$this->_readActivatedModulesList();
		$original_activated_modules_list = $this->activated_modules_list;
		$this->activated_modules_list[] = $module_name;

		try {

			$this->moduleInstance( $module_name )->install();

		} catch( \Exception $e ) {

			$this->activated_modules_list = $original_activated_modules_list;
			throw new Application_Modules_Exception(
				$e->getMessage(), Application_Modules_Exception::CODE_FAILED_TO_INSTALL_MODULE
			);

		}

		$this->activated_modules_list = $original_activated_modules_list;
		$this->installed_modules_list[] = $module_name;

		$this->_saveInstalledModulesList();

	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public function uninstallModule( $module_name )
	{

		$this->_checkModuleExists( $module_name );

		$module_manifest = $this->moduleManifest( $module_name );

		if( !$module_manifest->isInstalled() ) {
			throw new Application_Modules_Exception(
				'Module \''.$module_name.'\' is not installed',
				Application_Modules_Exception::CODE_MODULE_IS_NOT_INSTALLED
			);
		}

		/**
		 * @var Application_Modules_Exception $uninstall_exception
		 */
		$uninstall_exception = null;

		$this->activated_modules_list[] = $module_name;

		try {

			$this->moduleInstance( $module_name )->uninstall();

		} catch( \Exception $e ) {
			$uninstall_exception = new Application_Modules_Exception(
				$e->getMessage(), Application_Modules_Exception::CODE_FAILED_TO_UNINSTALL_MODULE
			);
		}

		$activated_modules_list = [];
		foreach( $this->activated_modules_list as $am ) {
			if( $am != $module_name ) {
				$activated_modules_list[] = $am;
			}
		}
		$this->activated_modules_list = $activated_modules_list;

		$installed_modules_list = [];
		foreach( $this->installed_modules_list as $am ) {
			if( $am != $module_name ) {
				$installed_modules_list[] = $am;
			}
		}

		$this->installed_modules_list = $installed_modules_list;


		$this->_saveActivatedModulesList();
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
	public function activateModule( $module_name )
	{

		$this->_checkModuleExists( $module_name );

		$module_manifest = $this->moduleManifest( $module_name );

		if( !$module_manifest->isInstalled() ) {
			throw new Application_Modules_Exception(
				'Module \''.$module_name.'\' is not installed',
				Application_Modules_Exception::CODE_MODULE_IS_NOT_INSTALLED
			);
		}

		if( $module_manifest->isActivated() ) {
			return;
		}

		$this->activated_modules_list[] = $module_name;

		$this->_saveActivatedModulesList();
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public function deactivateModule( $module_name )
	{

		$this->_checkModuleExists( $module_name );

		$module_manifest = $this->moduleManifest( $module_name );

		if( !$module_manifest->isInstalled() ) {
			throw new Application_Modules_Exception(
				'Module \''.$module_name.'\' is not installed',
				Application_Modules_Exception::CODE_MODULE_IS_NOT_INSTALLED
			);
		}

		if( !$module_manifest->isActivated() ) {
			return;
		}


		$activated_modules_list = [];
		foreach( $this->activated_modules_list as $am ) {
			if( $am != $module_name ) {
				$activated_modules_list[] = $am;
			}
		}

		$this->activated_modules_list = $activated_modules_list;


		$this->_saveActivatedModulesList();
	}



	/**
	 *
	 * @param string $module_name
	 *
	 * @return Application_Module_Manifest
	 */
	public function moduleManifest( $module_name )
	{
		if(!isset($this->module_manifest[$module_name])) {
			$manifest_class_name = Application_Factory::getModuleManifestClassName();

			$this->module_manifest[$module_name] = new $manifest_class_name( $module_name );
		}

		return $this->module_manifest[$module_name];
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 *
	 * @return Application_Module
	 */
	public function moduleInstance( $module_name )
	{

		if( isset( $this->module_instance[$module_name] ) ) {
			return $this->module_instance[$module_name];
		}

		if(!$this->moduleIsActivated($module_name)) {
			throw new Application_Modules_Exception(
				'Module \''.$module_name.'\' does not exist, is not installed or is not activated',
				Application_Modules_Exception::CODE_MODULE_DOES_NOT_EXIST
			);
		}

		$module_manifest = $this->moduleManifest( $module_name );

		$module_dir = $module_manifest->getModuleDir();

		/** @noinspection PhpIncludeInspection */
		require_once $module_dir.'Main.php';

		$class_name = $module_manifest->getNamespace().'Main';

		$module = new $class_name( $module_manifest );

		$this->module_instance[$module_name] = $module;

		return $this->module_instance[$module_name];
	}


	/**
	 *
	 */
	protected function _readActivatedModulesList()
	{
		if( $this->activated_modules_list===null ) {
			$this->activated_modules_list = [];

			if(IO_File::exists($this->activated_modules_list_file_path)) {
				$this->activated_modules_list = require $this->activated_modules_list_file_path;
			}
		}

	}

	/**
	 *
	 *
	 */
	protected function _readInstalledModulesList()
	{
		if( $this->installed_modules_list===null ) {
			$this->installed_modules_list = [];

			if(IO_File::exists($this->installed_modules_list_file_path)) {
				$this->installed_modules_list = require $this->installed_modules_list_file_path;
			}
		}
	}


	/**
	 *
	 */
	protected function _saveInstalledModulesList()
	{
		IO_File::write(
			$this->getInstalledModulesListFilePath(),
			'<?php'.JET_EOL.' return '.var_export( $this->installed_modules_list, true ).';'.JET_EOL
		);
	}

	/**
	 *
	 */
	protected function _saveActivatedModulesList()
	{
		IO_File::write(
			$this->getActivatedModulesListFilePath(),
			'<?php'.JET_EOL.' return '.var_export( $this->activated_modules_list, true ).';'.JET_EOL
		);
	}

}