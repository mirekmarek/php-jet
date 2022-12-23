<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Application_Modules_Handler_Default extends Application_Modules_Handler
{
	/**
	 *
	 * @var ?array
	 */
	protected ?array $activated_modules_list = null;

	/**
	 *
	 * @var ?array
	 */
	protected ?array $installed_modules_list = null;

	/**
	 *
	 * @var ?array
	 */
	protected ?array $all_modules_list = null;

	/**
	 * @var Application_Module_Manifest[]
	 */
	protected array $module_manifest = [];

	/**
	 *
	 * @var Application_Module[]
	 */
	protected array $module_instance = [];

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public function moduleExists( string $module_name ): bool
	{
		if( $this->all_modules_list === null ) {
			$this->allModulesList();
		}

		return in_array( $module_name, $this->all_modules_list );
	}

	/**
	 *
	 * @return Application_Module_Manifest[]
	 */
	public function activatedModulesList(): array
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
	public function installedModulesList(): array
	{
		$this->_readInstalledModulesList();

		return $this->installed_modules_list;
	}

	/**
	 *
	 *
	 *
	 * @return Application_Module_Manifest[]
	 */
	public function allModulesList(): array
	{
		if( $this->all_modules_list === null ) {
			$this->all_modules_list = [];


			$this->_readModulesList( SysConf_Path::getModules(), '' );

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
	protected function _readModulesList( string $base_dir, string $module_name_prefix )
	{
		$modules = IO_Dir::getSubdirectoriesList( $base_dir );

		$manifest_class_name = Factory_Application::getModuleManifestClassName();

		foreach( $modules as $module_dir ) {

			if( !IO_File::exists( $base_dir . $module_dir . '/' .SysConf_Jet_Modules::getManifestFileName() ) ) {

				$next_module_name_prefix = ($module_name_prefix)
					?
					$module_name_prefix . $module_dir . '\\'
					:
					$module_dir . '\\';

				$this->_readModulesList(
					$base_dir . $module_dir . '/', $next_module_name_prefix
				);
				continue;
			}

			$module_name = str_replace( '\\', '.', $module_name_prefix . $module_dir );

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
	public function moduleIsInstalled( string $module_name ): bool
	{

		$this->_readInstalledModulesList();

		return in_array( $module_name, $this->installed_modules_list );
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public function moduleIsActivated( string $module_name ): bool
	{

		$this->_readActivatedModulesList();

		return in_array( $module_name, $this->activated_modules_list );
	}


	/**
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	protected function _checkModuleExists( string $module_name ): void
	{

		$this->allModulesList();

		if( !in_array( $module_name, $this->all_modules_list ) ) {
			throw new Application_Modules_Exception(
				'Module \'' . $module_name . '\' does not exist ', Application_Modules_Exception::CODE_MODULE_DOES_NOT_EXIST
			);
		}
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public function installModule( string $module_name ): void
	{

		$this->_checkModuleExists( $module_name );

		$module_manifest = $this->moduleManifest( $module_name );

		if( $module_manifest->isInstalled() ) {
			throw new Application_Modules_Exception(
				'Module \'' . $module_name . '\' is already installed',
				Application_Modules_Exception::CODE_MODULE_ALREADY_INSTALLED
			);
		}

		if( !$module_manifest->isCompatible() ) {
			throw new Application_Modules_Exception(
				'Module \'' . $module_name . '\' is not compatible with this system.',
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
	public function uninstallModule( string $module_name ): void
	{

		$this->_checkModuleExists( $module_name );

		$module_manifest = $this->moduleManifest( $module_name );

		if( !$module_manifest->isInstalled() ) {
			throw new Application_Modules_Exception(
				'Module \'' . $module_name . '\' is not installed',
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
	public function activateModule( string $module_name ): void
	{

		$this->_checkModuleExists( $module_name );

		$module_manifest = $this->moduleManifest( $module_name );

		if( !$module_manifest->isInstalled() ) {
			throw new Application_Modules_Exception(
				'Module \'' . $module_name . '\' is not installed',
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
	public function deactivateModule( string $module_name ): void
	{

		$this->_checkModuleExists( $module_name );

		$module_manifest = $this->moduleManifest( $module_name );

		if( !$module_manifest->isInstalled() ) {
			throw new Application_Modules_Exception(
				'Module \'' . $module_name . '\' is not installed',
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
	public function moduleManifest( string $module_name ): Application_Module_Manifest
	{
		if( !isset( $this->module_manifest[$module_name] ) ) {
			$manifest_class_name = Factory_Application::getModuleManifestClassName();

			$this->module_manifest[$module_name] = new $manifest_class_name( $module_name );
		}

		return $this->module_manifest[$module_name];
	}

	/**
	 *
	 * @param string $module_name
	 *
	 * @return Application_Module
	 * @throws Application_Modules_Exception
	 *
	 */
	public function moduleInstance( string $module_name ): Application_Module
	{

		if( isset( $this->module_instance[$module_name] ) ) {
			return $this->module_instance[$module_name];
		}

		if( !$this->moduleIsActivated( $module_name ) ) {
			throw new Application_Modules_Exception(
				'Module \'' . $module_name . '\' does not exist, is not installed or is not activated',
				Application_Modules_Exception::CODE_MODULE_DOES_NOT_EXIST
			);
		}

		$module_manifest = $this->moduleManifest( $module_name );

		$module_dir = $this->getModuleDir( $module_name );
		
		require_once $module_dir . Application_Module::MAIN_CLASS_NAME.'.php';

		$class_name = $module_manifest->getNamespace() . Application_Module::MAIN_CLASS_NAME;

		$module = new $class_name( $module_manifest );

		$this->module_instance[$module_name] = $module;

		return $this->module_instance[$module_name];
	}


	/**
	 *
	 */
	protected function _readActivatedModulesList(): void
	{
		if( $this->activated_modules_list === null ) {
			$this->activated_modules_list = [];

			$path = SysConf_Jet_Modules::getActivatedModulesListFilePath();

			if( IO_File::exists( $path ) ) {
				$this->activated_modules_list = require $path;
			}
		}

	}

	/**
	 *
	 *
	 */
	protected function _readInstalledModulesList(): void
	{
		if( $this->installed_modules_list === null ) {
			$this->installed_modules_list = [];

			$path = SysConf_Jet_Modules::getInstalledModulesListFilePath();

			if( IO_File::exists( $path ) ) {
				$this->installed_modules_list = require $path;
			}
		}
	}


	/**
	 *
	 */
	protected function _saveInstalledModulesList(): void
	{
		IO_File::writeDataAsPhp(
			SysConf_Jet_Modules::getInstalledModulesListFilePath(),
			$this->installed_modules_list
		);

		MVC_Cache::reset();
	}

	/**
	 *
	 */
	protected function _saveActivatedModulesList(): void
	{
		IO_File::writeDataAsPhp(
			SysConf_Jet_Modules::getActivatedModulesListFilePath(),
			$this->activated_modules_list
		);

		MVC_Cache::reset();
	}
	
	/**
	 * @param string $module_name
	 *
	 * @return array
	 */
	public function readManifestData( string $module_name ) : array
	{
		$module_dir = $this->getModuleDir( $module_name );
		
		if( !IO_Dir::exists( $module_dir ) ) {
			throw new Application_Modules_Exception(
				'Directory \'' . $module_dir . '\' does not exist',
				Application_Modules_Exception::CODE_MODULE_DOES_NOT_EXIST
			);
		}
		
		
		$manifest_file = $module_dir . SysConf_Jet_Modules::getManifestFileName();
		
		if( !IO_File::isReadable( $manifest_file ) ) {
			throw new Application_Modules_Exception(
				'Module manifest file \'' . $manifest_file . '\' does not exist or is not readable. ',
				Application_Modules_Exception::CODE_MANIFEST_IS_NOT_READABLE
			);
		}
		
		return require $manifest_file;
		
	}
	
	/**
	 * @param Application_Module_Manifest $manifest
	 */
	public function saveManifest( Application_Module_Manifest $manifest ) : void
	{
		IO_File::writeDataAsPhp(
			$manifest->getModuleDir() . SysConf_Jet_Modules::getManifestFileName(),
			$manifest->toArray()
		);
		
	}
	
}