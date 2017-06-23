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
class Mvc_Controller_Router extends BaseObject
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
	 * @param Mvc_Controller $controller
	 */
	public function __construct( Mvc_Controller $controller )
	{
		$this->controller = $controller;
	}

	/**
	 * @param string $controller_action_name
	 * @param string $regexp
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function addAction( $controller_action_name, $regexp='' )
	{
		$module_action = $this->controller->getModuleAction($controller_action_name);

		$action = new Mvc_Controller_Router_Action( $this, $controller_action_name, $regexp, $module_action );

		$this->actions[$controller_action_name] = $action;

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
	 * @param string $controller_action_name
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function getAction( $controller_action_name )
	{
		return $this->actions[$controller_action_name];
	}


	/**
	 * @param string $path
	 *
	 * @return bool
	 */
	public function resolve( $path )
	{
		foreach( $this->actions as $action ) {

			if( !$action->resolve( $path ) ) {
				continue;
			}

			$this->controller->getContent()->setControllerAction( $action->getControllerAction() );

			return true;
		}


		return false;

	}

}