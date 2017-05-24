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
	 * @var string
	 */
	protected static $default_layouts_dir = 'layouts';

	/**
	 *
	 * @var Application_Module_Manifest
	 */
	protected $module_manifest;

	/**
	 * action => Human readable action description
	 *
	 * Example:
	 *
	 * <code>
	 * protected static $ACL_actions = array(
	 *      'get_data_module_action' => 'Get data',
	 *      'update_record_module_action' => 'Update data',
	 *      'add_record_module_action' => 'Add new data',
	 *      'delete_record_module_action' => 'Delete data'
	 * );
	 * </code>
	 *
	 * @var array
	 */
	protected $ACL_actions = [];

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
	 * @return string
	 */
	public static function getDefaultLayoutsDir()
	{
		return static::$default_layouts_dir;
	}

	/**
	 * @param string $default_layouts_dir
	 */
	public static function setDefaultLayoutsDir( $default_layouts_dir )
	{
		static::$default_layouts_dir = $default_layouts_dir;
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
	 * Returns module layouts directory
	 *
	 * @return string
	 */
	public function getLayoutsDir()
	{
		return $this->module_manifest->getModuleDir().static::getDefaultLayoutsDir().'/';
	}

	/**
	 *
	 * @param Mvc_Page_Content_Interface $content
	 *
	 * @return Mvc_Controller
	 * @throws Exception
	 */
	public function getControllerInstance( Mvc_Page_Content_Interface $content )
	{

		$controller_class_name = $this->getControllerClassName( $content );

		$controller = new $controller_class_name( $this );

		if( !$controller instanceof Mvc_Controller ) {
			throw new Exception(
				'Controller \''.$controller_class_name.'\' is not an instance of Mvc_Controller_Abstract'
			);
		}

		return $controller;
	}

	/**
	 *
	 * @param Mvc_Page_Content_Interface $content
	 *
	 * @return string
	 */
	protected function getControllerClassName( Mvc_Page_Content_Interface $content )
	{
		$controller_name = 'Main';

		if( $content->getCustomController() ) {
			$controller_name = $content->getCustomController();
		}

		$controller_suffix = 'Controller_'.$controller_name;

		$controller_class_name = $this->module_manifest->getNamespace().$controller_suffix;

		return $controller_class_name;
	}

	/**
	 *
	 * @param Mvc_Controller $controller
	 * @param string         $action
	 * @param array          $action_parameters (optional)  @see Mvc_Dispatcher_QueueItem::$action_parameters
	 *
	 * @throws Exception
	 */
	public function callControllerAction( Mvc_Controller $controller, $action, array $action_parameters = [] )
	{


		if( !$controller->checkACL( $action, $action_parameters ) ) {
			return;
		}

		$controller->callAction( $action, $action_parameters );
	}

	/**
	 * @param string $action
	 *
	 * @throws Application_Modules_Exception
	 *
	 * @return bool
	 */
	public function checkAclCanDoAction( $action )
	{
		$ACL_actions = $this->getAclActions();

		if( !isset( $ACL_actions[$action] ) ) {
			throw new Application_Modules_Exception(
				'Unknown ACL action \''.$action.'\'. Please add record to '.get_class( $this ).'::$ACL_actions ',
				Application_Modules_Exception::CODE_UNKNOWN_ACL_ACTION
			);
		}


		$module_name = $this->module_manifest->getName();

		return Auth::getCurrentUserHasPrivilege(
			Auth_Role::PRIVILEGE_MODULE_ACTION, $module_name.':'.$action
		);
	}

	/**
	 * @see Application_Module::$ACL_actions_check_map
	 *
	 * @return array
	 */
	public function getAclActions()
	{
		return $this->ACL_actions;
	}

}