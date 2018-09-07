<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
abstract class Application_Module extends BaseObject
{

	/**
	 * @var string
	 */
	protected static $default_install_directory = '_install';
	/**
	 * @var string
	 */
	protected static $default_install_script = 'install.php';
	/**
	 * @var string
	 */
	protected static $default_uninstall_script = 'uninstall.php';

	/**
	 * @var string
	 */
	protected static $default_views_dir = 'views';
	
	/**
	 *
	 * @var Application_Module_Manifest
	 */
	protected $module_manifest;
	
	
	/**
	 * @return string
	 */
	public static function getDefaultInstallDirectory()
	{
		return static::$default_install_directory;
	}

	/**
	 * @param string $default_install_directory
	 */
	public static function setDefaultInstallDirectory( $default_install_directory )
	{
		static::$default_install_directory = $default_install_directory;
	}

	/**
	 * @return string
	 */
	public static function getDefaultInstallScript()
	{
		return static::$default_install_script;
	}

	/**
	 * @param string $default_install_script
	 */
	public static function setDefaultInstallScript( $default_install_script )
	{
		static::$default_install_script = $default_install_script;
	}

	/**
	 * @return string
	 */
	public static function getDefaultUninstallScript()
	{
		return static::$default_uninstall_script;
	}

	/**
	 * @param string $default_uninstall_script
	 */
	public static function setDefaultUninstallScript( $default_uninstall_script )
	{
		static::$default_uninstall_script = $default_uninstall_script;
	}

	/**
	 * @return string
	 */
	public static function getDefaultViewsDir()
	{
		return static::$default_views_dir;
	}

	/**
	 * @param string $default_views_dir
	 */
	public static function setDefaultViewsDir( $default_views_dir )
	{
		static::$default_views_dir = $default_views_dir;
	}



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
	public function getModuleManifest()
	{
		return $this->module_manifest;
	}

	/**
	 * @throws Application_Modules_Exception
	 */
	public function install()
	{
		$module_dir = $this->module_manifest->getModuleDir();
		$install_script = $module_dir.static::getDefaultInstallDirectory().'/'.static::getDefaultInstallScript();

		if( file_exists( $install_script ) ) {
			try {

				/** @noinspection PhpUnusedLocalVariableInspection */
				$module_instance = $this;

				/** @noinspection PhpIncludeInspection */
				require_once $install_script;

			} catch( \Exception $e ) {

				throw new Application_Modules_Exception(
					'Error while processing installation script: '.get_class( $e ).'::'.$e->getMessage(),
					Application_Modules_Exception::CODE_FAILED_TO_INSTALL_MODULE
				);
			}
		}

	}


	/**
	 * @throws Application_Modules_Exception
	 */
	public function uninstall()
	{
		$module_dir = $this->module_manifest->getModuleDir();

		$uninstall_script = $module_dir.static::getDefaultInstallDirectory().'/'.static::getDefaultUninstallScript();

		if( file_exists( $uninstall_script ) ) {
			try {

				/** @noinspection PhpUnusedLocalVariableInspection */
				$module_instance = $this;

				/** @noinspection PhpIncludeInspection */
				require_once $uninstall_script;

			} catch( \Exception $e ) {
				throw new Application_Modules_Exception(
					'Error while processing uninstall script: '.get_class( $e ).'::'.$e->getMessage(),
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
	public function getViewsDir()
	{
		return $this->module_manifest->getModuleDir().static::getDefaultViewsDir().'/';
	}


	/**
	 * @param string $action
	 *
	 * @throws Application_Modules_Exception
	 *
	 * @return bool
	 */
	public function actionIsAllowed( $action )
	{
		$module_name = $this->module_manifest->getName();

		if( !$this->module_manifest->hasACLAction($action) ) {
			throw new Application_Modules_Exception(
				'Unknown ACL action \''.$action.'\' (Module: '.$module_name.')',
				Application_Modules_Exception::CODE_UNKNOWN_ACL_ACTION
			);
		}

		return Auth::checkModuleActionAccess( $module_name, $action );
	}
	
}