<?php
/**
 *
 *
 *
 * Main controller class
 * @see Mvc/readme.txt
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Controller
 */
namespace Jet;

abstract class Mvc_Controller_Abstract extends Object {
	/**
	 *
	 * @var Application_Modules_Module_Abstract
	 */
	protected $module_instance;

	/**
	 *
	 * @var Application_Modules_Module_Manifest
	 */
	protected $module_manifest;

	/**
	 *
	 * @var Mvc_Router_Abstract
	 */
	protected $router;

	/**
	 *
	 * @var Mvc_Dispatcher_Abstract
	 */
	protected $dispatcher;

	/**
	 * @var Mvc_View
	 */
	protected $view;

	/**
	 * Format:
	 *
	 * controller_action => false|module_action_name
	 *
	 * @see Mvc_Modules_Module::$ACL_actions_check_map
	 *
	 *
	 * Example:
	 *
	 * <code>
	 * protected static $ACL_actions_check_map = array(
	 *      'get_public_data' => false, //do not check this
	 *      'get_data' => 'get_data_module_action',
	 *      'put_data' => 'update_record_module_action',
	 *      'post_data => 'add_record_module_action'
	 *      'delete_data => 'delete_record_module_action'
	 * );
	 * </code>
	 *
	 * @var array
	 */
	protected static $ACL_actions_check_map = array(
	);


	/**
	 *
	 * @param Mvc_Dispatcher_Abstract $dispatcher
	 * @param Application_Modules_Module_Abstract $module_instance
	 */
	public function __construct( Mvc_Dispatcher_Abstract $dispatcher, Application_Modules_Module_Abstract $module_instance ) {
		$this->module_instance = $module_instance;
		$this->module_manifest = $module_instance->getModuleManifest();
		$this->dispatcher = $dispatcher;
		$this->router = $dispatcher->getRouter();
		$this->initializeDefaultView();
	}

	/**
	 * @param string $action
	 * @param array $action_parameters
	 *
	 * @return bool
	 *
	 * @throws Mvc_Controller_Exception
	 */
	public function checkACL( $action, $action_parameters ) {
		if(!isset(static::$ACL_actions_check_map[$action])) {
			throw new Mvc_Controller_Exception(
				'Action \''.$action.'\' is not specified in ACL check map! Please specify the ACL rules. Add '.get_class($this).'::$ACL_actions_check_map['.$action.'] entry.',
				Mvc_Controller_Exception::CODE_UNKNOWN_ACL_ACTION
			);
		}

		if(static::$ACL_actions_check_map[$action]===false) {
			return true;
		}

		$module_action = static::$ACL_actions_check_map[$action];

		if( !$this->module_instance->checkAclCanDoAction( $module_action ) ) {
			$this->responseAclAccessDenied( $module_action, $action, $action_parameters );

			return false;
		}

		Auth::logEvent(
			'action:'.$this->module_manifest->getName().':'.$module_action,
			array('action_params'=>$action_parameters),
			'Allowed action: '.$this->module_manifest->getName().':'.$action
		);

		return true;
	}


	/**
	 * @param string $module_action
	 * @param string $controller_action
	 * @param array $action_parameters
	 *
	 */
	abstract public function responseAclAccessDenied( $module_action, $controller_action, $action_parameters );


	/**
	 * Creates default view instance
	 *
	 * @see Mvc_View
	 */
	protected function initializeDefaultView() {
		$this->view = new Mvc_View( $this->module_instance->getViewsDir() );
	}

	/**
	 * Renders the output and adds it into the default layout.
	 * @see Mvc/readme.txt
	 *
	 * @param string $script
	 * @param string $position (optional, default: by current dispatcher queue item)
	 * @param bool $position_required (optional, default: by current dispatcher queue item)
	 * @param int $position_order (optional, default: by current dispatcher queue item)
	 */
	public function render(
		$script,
		$position = null,
		$position_required = null,
		$position_order = null
	) {
		$this->router->getUIManagerModuleInstance()->renderOutput(
			$this->view,
			$script,
			$position,
			$position_required,
			$position_order
		);

		return;
	}

	/**
	 * Returns current routing data
	 *   equivalent of $this->router
	 *
	 * @return Mvc_Router_Abstract
	 */
	public function getRouter() {
		return $this->router;
	}

	/**
	 * @return Mvc_UIManagerModule_Abstract
	 */
	public function getUIManagerModuleInstance() {
		return $this->router->getUIManagerModuleInstance();
	}

}