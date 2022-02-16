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
abstract class Application_Module extends BaseObject
{
	const MAIN_CLASS_NAME = 'Main';

	/**
	 *
	 * @var Application_Module_Manifest
	 */
	protected Application_Module_Manifest $module_manifest;


	/**
	 * @param Application_Module_Manifest $manifest
	 */
	public function __construct( Application_Module_Manifest $manifest )
	{
		$this->module_manifest = $manifest;
	}

	/**
	 * @return Application_Module_Manifest
	 */
	public function getModuleManifest(): Application_Module_Manifest
	{
		return $this->module_manifest;
	}

	/**
	 * @throws Application_Modules_Exception
	 */
	public function install(): void
	{
		$module_dir = $this->module_manifest->getModuleDir();
		$install_script = $module_dir . SysConf_Jet_Modules::getInstallDirectory() . '/' . SysConf_Jet_Modules::getInstallScript();

		if( file_exists( $install_script ) ) {
			try {

				$module_instance = $this;

				require_once $install_script;

			} catch( \Exception $e ) {

				throw new Application_Modules_Exception(
					'Error while processing installation script: ' . get_class( $e ) . '::' . $e->getMessage(),
					Application_Modules_Exception::CODE_FAILED_TO_INSTALL_MODULE
				);
			}
		}

	}


	/**
	 * @throws Application_Modules_Exception
	 */
	public function uninstall(): void
	{
		$module_dir = $this->module_manifest->getModuleDir();

		$uninstall_script = $module_dir . SysConf_Jet_Modules::getInstallDirectory() . '/' . SysConf_Jet_Modules::getUninstallScript();

		if( file_exists( $uninstall_script ) ) {
			try {

				$module_instance = $this;

				require_once $uninstall_script;

			} catch( \Exception $e ) {
				throw new Application_Modules_Exception(
					'Error while processing uninstall script: ' . get_class( $e ) . '::' . $e->getMessage(),
					Application_Modules_Exception::CODE_FAILED_TO_UNINSTALL_MODULE
				);
			}
		}
	}


	/**
	 * Returns module views directory
	 *
	 * @return string
	 */
	public function getViewsDir(): string
	{
		return $this->module_manifest->getModuleDir() . SysConf_Jet_Modules::getViewsDir() . '/';
	}


	/**
	 * @param string $action
	 *
	 * @return bool
	 * @throws Application_Modules_Exception
	 *
	 */
	public function actionIsAllowed( string $action ): bool
	{
		$module_name = $this->module_manifest->getName();

		if( !$this->module_manifest->hasACLAction( $action ) ) {
			throw new Application_Modules_Exception(
				'Unknown ACL action \'' . $action . '\' (Module: ' . $module_name . ')',
				Application_Modules_Exception::CODE_UNKNOWN_ACL_ACTION
			);
		}

		return Auth::checkModuleActionAccess( $module_name, $action );
	}

}