<?php
/**
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Controller_MicroRouter
 */
namespace Jet;

class Mvc_MicroRouter extends Object {

	/**
	 * @var Application_Modules_Module_Abstract
	 */
	protected $module_instance;

	/**
	 * @var Mvc_Router_Abstract
	 */
	protected $router_instance;


	/**
	 * @var Mvc_MicroRouter_Action[]
	 */
	protected $actions = [];

	/**
	 * @var string
	 */
	protected $default_action_name = '';


	/**
	 * @param Mvc_Router_Abstract $router_instance
	 * @param Application_Modules_Module_Abstract $module_instance
	 */
	public function __construct( Mvc_Router_Abstract $router_instance, Application_Modules_Module_Abstract $module_instance ) {
		$this->router_instance = $router_instance;
		$this->module_instance = $module_instance;
	}

	/**
	 * @param string $action_name
	 * @param string $regexp
	 * @param string $ACL_action
	 * @param bool $disable_routing_cache (optional, default: false)
	 *
	 * @return Mvc_MicroRouter_Action
	 */
	public function addAction( $action_name, $regexp, $ACL_action, $disable_routing_cache=false ) {
		$action = new Mvc_MicroRouter_Action( $action_name, $regexp, $ACL_action, $disable_routing_cache );

		$this->actions[$action_name] = $action;

		return $action;
	}

	/**
	 * @param string $default_controller_action_name
	 */
	public function setDefaultActionName($default_controller_action_name) {
		$this->default_action_name = $default_controller_action_name;
	}

	/**
	 * @return string
	 */
	public function getDefaultActionName() {
		return $this->default_action_name;
	}

	/**
	 * @return Mvc_MicroRouter[]
	 */
	public function getActions() {
		return $this->actions;
	}

	/**
	 * @return Application_Modules_Module_Abstract
	 */
	public function getModuleInstance() {
		return $this->module_instance;
	}

	/**
	 * @return Mvc_Router_Abstract
	 */
	public function getRouterInstance() {
		return $this->router_instance;
	}


	/**
	 * @param Mvc_Page_Content_Abstract $page_content
	 * @return bool
	 */
	public function resolve( Mvc_Page_Content_Abstract $page_content ) {

		if($this->default_action_name) {
			$action_name = $this->default_action_name;
			$action_parameters = [];
		} else {
			$action_name = null;
			$action_parameters = [];

		}

		foreach( $this->actions as $action ) {
			if(!$action->resolve( $this )) {
				continue;
			}

			$action_name = $action->getActionName();
			$action_parameters = $action->getActionParameters();

			if($action->getDisableRoutingCache()) {
				$this->router_instance->disableCache();
			}

			break;
		}

		if($action_name) {
			$page_content->setControllerAction( $action_name );
			$page_content->setControllerActionParameters( $action_parameters );

			return true;
		}

		return false;

	}

	/**
	 *
	 * @param $action_name
	 * @param ...
	 *
	 * @return string
	 */
	public function getActionURI( $action_name ) {

		$arguments = func_get_args();
		array_shift( $arguments );

		return $this->actions[$action_name]->getURI( $arguments );
	}

	/**
	 * @param string $action_name
	 * @return bool
	 */
	public function getActionAllowed( $action_name ) {
		$action = $this->actions[$action_name];

		$ACL_action_name = $action->getACLAction();

		if(!$ACL_action_name) {
			return true;
		}

		return $this->module_instance->checkAclCanDoAction($ACL_action_name , null, false );
	}

}