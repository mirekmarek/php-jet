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

class Mvc_Controller_MicroRouter extends Object {

	/**
	 * @var Mvc_Controller_Abstract
	 */
	protected $controller;

	/**
	 * @var Mvc_Controller_MicroRouter_Action[]
	 */
	protected $actions = array();

	/**
	 * @var string
	 */
	protected $default_action_name = '';

	/**
	 * @var string
	 */
	protected $not_authorized_action_name = '';


	public function __construct( Mvc_Controller_Abstract $controller ) {
		$this->controller = $controller;
	}

	/**
	 * @param string $action_name
	 * @param string $regexp
	 *
	 * @return Mvc_Controller_MicroRouter_Action
	 */
	public function addAction( $action_name, $regexp ) {
		$action = new Mvc_Controller_MicroRouter_Action( $action_name, $regexp );

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
	 * @param string $not_authorized_controller_action_name
	 */
	public function setNotAuthorizedActionName($not_authorized_controller_action_name) {
		$this->not_authorized_action_name = $not_authorized_controller_action_name;
	}

	/**
	 * @return string
	 */
	public function getNotAuthorizedActionName() {
		return $this->not_authorized_action_name;
	}

	/**
	 * @return Mvc_Controller_Abstract
	 */
	public function getController() {
		return $this->controller;
	}

	/**
	 * @return Mvc_Controller_MicroRouter[]
	 */
	public function getActions() {
		return $this->actions;
	}


	/**
	 *
	 */
	public function dispatch() {
		if(!$this->default_action_name) {
			throw new Exception('Default action name is not set.' );
		}

		if(!$this->not_authorized_action_name) {
			throw new Exception('Not authorized action name is not set.' );
		}

		$call_default_action = true;

		foreach( $this->actions as $action ) {
			if(!$action->resolve( $this )) {
				continue;
			}

			$call_default_action = false;

			if(!$this->checkACL($action)) {
				return;
			}

			$this->callAction($action);
		}

		if($call_default_action) {
			$this->callDefaultAction();
		}
	}

	/**
	 * @param Mvc_Controller_MicroRouter_Action $action
	 * @return bool
	 */
	public function checkACL( Mvc_Controller_MicroRouter_Action $action ) {
		if(!$this->controller->checkACL(
			$action->getActionName(),
			$action->getActionParameters()
		)) {
			$this->callNotAuthorizedAction();

			return false;
		}

		return true;
	}

	/**
	 * @param Mvc_Controller_MicroRouter_Action $action
	 */
	public function callAction( Mvc_Controller_MicroRouter_Action $action ) {
		$this->callControllerAction(
			$action->getActionName(),
			$action->getActionParameters()
		);
	}

	/**
	 *
	 */
	public function callDefaultAction() {
		$this->callControllerAction( $this->default_action_name );
	}

	/**
	 *
	 */
	public function callNotAuthorizedAction() {
		$this->callControllerAction( $this->not_authorized_action_name );
	}

	/**
	 * Calls the action
	 *
	 * @param string $action
	 * @param array $action_parameters (optional)  @see Mvc_Dispatcher_QueueItem::$action_parameters
	 *
	 * @throws Mvc_Dispatcher_Exception
	 */
	protected function callControllerAction( $action, array $action_parameters=array() ) {
		$controller = $this->controller;

		$method = $action.'_Action';

		if( !method_exists($controller, $method) ) {
			throw new Mvc_Dispatcher_Exception(
				'Controller method '. get_class($controller).'::'.$method.'() does not exist',
				Mvc_Dispatcher_Exception::CODE_ACTION_DOES_NOT_EXIST
			);
		}

		call_user_func_array(array( $controller, $method ), $action_parameters);
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

		return $this->controller->checkACL( $action->getActionName(), $action->getActionParameters(), false );
	}

}