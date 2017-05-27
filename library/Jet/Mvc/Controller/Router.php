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
	 * @var string
	 */
	protected $default_action_name = '';


	/**
	 * @param Mvc_Controller $controller
	 */
	public function __construct( Mvc_Controller $controller )
	{
		$this->controller = $controller;
	}

	/**
	 * @param string $action_name
	 * @param string $regexp
	 * @param string $ACL_action
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function addAction( $action_name, $regexp, $ACL_action )
	{
		$action = new Mvc_Controller_Router_Action( $action_name, $regexp, $ACL_action );

		$this->actions[$action_name] = $action;

		return $action;
	}

	/**
	 * @return string
	 */
	public function getDefaultActionName()
	{
		return $this->default_action_name;
	}

	/**
	 * @param string $default_controller_action_name
	 */
	public function setDefaultActionName( $default_controller_action_name )
	{
		$this->default_action_name = $default_controller_action_name;
	}

	/**
	 * @return Mvc_Controller_Router_Action[]
	 */
	public function getActions()
	{
		return $this->actions;
	}

	/**
	 * @return Mvc_Controller
	 */
	public function getController()
	{
		return $this->controller;
	}


	/**
	 * @param string $path
	 *
	 * @return bool
	 */
	public function resolve( $path )
	{

		if( $this->default_action_name ) {
			$action_name = $this->default_action_name;
		} else {
			$action_name = null;

		}

		foreach( $this->actions as $action ) {
			$action->setRouter($this);

			if( !$action->resolve( $path ) ) {
				continue;
			}

			$action_name = $action->getActionName();

			break;
		}

		if( $action_name ) {
			$this->controller->getContent()->setControllerAction( $action_name );

			return true;
		}

		return false;

	}

	/**
	 *
	 * @param string $action_name
	 * @param ...
	 *
	 * @return string|bool
	 */
	public function getActionURI( $action_name )
	{

		$arguments = func_get_args();
		array_shift( $arguments );

		return $this->actions[$action_name]->getURI( $arguments );
	}

	/**
	 * @param string $action_name
	 *
	 * @return bool
	 */
	public function getActionAllowed( $action_name )
	{
		$action = $this->actions[$action_name];

		$ACL_action_name = $action->getACLAction();

		if( !$ACL_action_name ) {
			return true;
		}

		return $this->controller->getModule()->checkAccess( $ACL_action_name );
	}

}