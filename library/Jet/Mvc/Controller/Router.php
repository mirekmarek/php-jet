<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Mvc_Controller_Router extends BaseObject implements Mvc_Controller_Router_Interface
{

	/**
	 * @var Mvc_Controller
	 */
	protected $controller;


	/**
	 * @var Mvc_Controller_Router_Action[]
	 */
	protected $actions = [];

	/**
	 * @var Mvc_Controller_Router_Action
	 */
	protected $default_action;

	/**
	 * @param Mvc_Controller $controller
	 */
	public function __construct( Mvc_Controller $controller )
	{
		$this->controller = $controller;
	}

	/**
	 * @param string $controller_action_name
	 * @param string $module_action_name
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function addAction( $controller_action_name, $module_action_name='' )
	{
		$action = new Mvc_Controller_Router_Action( $this, $controller_action_name, $module_action_name );

		$this->actions[$controller_action_name] = $action;

		return $action;
	}

	/**
	 * @param string $controller_action_name
	 * @param string $module_action_name
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function setDefaultAction( $controller_action_name, $module_action_name='' )
	{
		$action = $this->addAction($controller_action_name, $module_action_name);

		$this->default_action = $action;

		return $action;
	}


	/**
	 * @return Mvc_Controller
	 */
	public function getController()
	{
		return $this->controller;
	}


	/**
	 * @return Mvc_Controller_Router_Action[]
	 */
	public function getActions()
	{
		return $this->actions;
	}

	/**
	 * @param string $action_name
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function action( $action_name )
	{
		return $this->actions[$action_name];
	}

	/**
	 * @param string $controller_action_name
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function getAction( $controller_action_name )
	{
		return $this->actions[$controller_action_name];
	}

	/**
	 * @return Mvc_Controller_Router_Action
	 */
	public function getDefaultAction()
	{
		return $this->default_action;
	}


	/**
	 *
	 * @return bool|string
	 */
	public function resolve()
	{
		$access_denied = false;
		foreach( $this->actions as $action ) {

			if( !$action->resolve() ) {
				continue;
			}

			if(!$action->isAccessAllowed()) {
				$access_denied = true;
				continue;
			}

			return $action->getControllerAction();
		}

		if(
			$this->default_action
		) {
			$action = $this->default_action;

			if(!$action->isAccessAllowed()) {
				$access_denied = true;
			} else {
				return $action->getControllerAction();

			}
		}

		if($access_denied) {
			$this->controller->responseAccessDenied();
		}

		return false;
	}

}