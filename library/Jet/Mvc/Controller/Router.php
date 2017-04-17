<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 */
namespace Jet;

class Mvc_Controller_Router extends BaseObject {

	/**
	 * @var Application_Modules_Module_Abstract
	 */
	protected $module_instance;

	/**
	 * @var Mvc_Router_Abstract
	 */
	protected $mvc_router;


	/**
	 * @var Mvc_Controller_Router_Action[]
	 */
	protected $actions = [];

	/**
	 * @var string
	 */
	protected $default_action_name = '';


	/**
	 * @param Application_Modules_Module_Abstract $module_instance
	 * @param Mvc_Router_Abstract $mvc_router
	 */
	public function __construct( Application_Modules_Module_Abstract $module_instance, Mvc_Router_Abstract $mvc_router=null ) {
		if(!$mvc_router) {
			$mvc_router = Mvc::getCurrentRouter();
		}
		$this->mvc_router = $mvc_router;
		$this->module_instance = $module_instance;
	}

	/**
	 * @param string $action_name
	 * @param string $regexp
	 * @param string $ACL_action
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function addAction( $action_name, $regexp, $ACL_action ) {
		$action = new Mvc_Controller_Router_Action( $action_name, $regexp, $ACL_action );

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
	 * @return Mvc_Controller_Router_Action[]
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
	public function getMvcRouter() {
		return $this->mvc_router;
	}


	/**
	 * @param Mvc_Page_Content_Interface $page_content
	 * @return bool
	 */
	public function resolve( Mvc_Page_Content_Interface $page_content ) {

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
	 * @return string|bool
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

		return $this->module_instance->checkAclCanDoAction($ACL_action_name);
	}

}