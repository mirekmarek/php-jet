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
class MVC_Controller_Router extends BaseObject implements MVC_Controller_Router_Interface
{

	/**
	 * @var ?MVC_Controller
	 */
	protected ?MVC_Controller $controller = null;


	/**
	 * @var MVC_Controller_Router_Action[]
	 */
	protected array $actions = [];

	/**
	 * @var ?MVC_Controller_Router_Action
	 */
	protected ?MVC_Controller_Router_Action $default_action = null;

	/**
	 * @param MVC_Controller $controller
	 */
	public function __construct( MVC_Controller $controller )
	{
		$this->controller = $controller;
	}

	/**
	 * @param string $controller_action_name
	 * @param string $module_action_name
	 *
	 * @return MVC_Controller_Router_Action
	 */
	public function addAction( string $controller_action_name, string $module_action_name = '' ): MVC_Controller_Router_Action
	{
		$action = new MVC_Controller_Router_Action( $this, $controller_action_name, $module_action_name );

		$this->actions[$controller_action_name] = $action;

		return $action;
	}

	/**
	 * @param string $controller_action_name
	 * @param string $module_action_name
	 *
	 * @return MVC_Controller_Router_Action
	 */
	public function setDefaultAction( string $controller_action_name, string $module_action_name = '' ): MVC_Controller_Router_Action
	{
		$action = $this->addAction( $controller_action_name, $module_action_name );

		$this->default_action = $action;

		return $action;
	}


	/**
	 * @return MVC_Controller
	 */
	public function getController(): MVC_Controller
	{
		return $this->controller;
	}


	/**
	 * @return MVC_Controller_Router_Action[]
	 */
	public function getActions(): array
	{
		return $this->actions;
	}

	/**
	 * @param string $action_name
	 *
	 * @return MVC_Controller_Router_Action
	 */
	public function action( string $action_name ): MVC_Controller_Router_Action
	{
		return $this->actions[$action_name];
	}

	/**
	 * @param string $controller_action_name
	 *
	 * @return MVC_Controller_Router_Action
	 */
	public function getAction( string $controller_action_name ): MVC_Controller_Router_Action
	{
		return $this->actions[$controller_action_name];
	}

	/**
	 * @return MVC_Controller_Router_Action
	 */
	public function getDefaultAction(): MVC_Controller_Router_Action
	{
		return $this->default_action;
	}


	/**
	 *
	 * @return bool|string
	 */
	public function resolve(): bool|string
	{

		foreach( $this->actions as $action ) {

			if( !$action->resolve() ) {
				continue;
			}

			if( $action->authorize() ) {
				return $action->getControllerAction();
			} else {
				$this->controller->handleNotAuthorized();
				return false;
			}
		}

		if(!$this->default_action) {
			return false;
		}

		if( $this->default_action->authorize() ) {
			return $this->default_action->getControllerAction();
		} else {
			$this->controller->handleNotAuthorized();
			return false;
		}

	}

}