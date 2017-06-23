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
class Mvc_Controller_Router_Action extends BaseObject
{

	/**
	 * @var Mvc_Controller_Router
	 */
	protected $router;

	/**
	 * @var string
	 */
	protected $regexp = '';

	/**
	 * @var string
	 */
	protected $controller_action = '';

	/**
	 * @var string
	 */
	protected $module_action = '';

	/**
	 * @var callable
	 */
	protected $resolver;

	/**
	 * @var callable
	 */
	protected $validator;

	/**
	 * @var callable
	 */
	protected $URI_creator;

	/**
	 * @param Mvc_Controller_Router $router
	 * @param string                $controller_action
	 * @param string                $regexp
	 * @param string                $module_action
	 */
	public function __construct( Mvc_Controller_Router $router, $controller_action, $regexp, $module_action )
	{
		$this->router = $router;
		$this->controller_action = $controller_action;
		$this->regexp = $regexp;
		$this->module_action = $module_action;
	}

	/**
	 * @return Mvc_Controller_Router
	 */
	public function router()
	{
		return $this->router;
	}

	/**
	 * @return Mvc_Controller
	 */
	public function controller()
	{
		return $this->router()->getController();
	}


	/**
	 * @return string
	 */
	public function getControllerAction()
	{
		return $this->controller_action;
	}

	/**
	 * @param string $controller_action
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function setControllerAction( $controller_action )
	{
		$this->controller_action = $controller_action;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getModuleAction()
	{
		return $this->module_action;
	}

	/**
	 * @param string $module_action
	 */
	public function setModuleAction( $module_action )
	{
		$this->module_action = $module_action;
	}

	/**
	 * @return string
	 */
	public function getRegexp()
	{
		return $this->regexp;
	}

	/**
	 * @param string $regexp
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function setRegexp( $regexp )
	{
		$this->regexp = $regexp;

		return $this;
	}

	/**
	 * Callback prototype:
	 *
	 * someCallback( $path, Mvc_Controller_Router_Action $action )
	 *
	 * Callback return value: bool, true if resolved
	 *
	 * @param callable $resolver
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function setResolver( callable $resolver )
	{
		$this->resolver = $resolver;

		return $this;
	}

	/**
	 * @param callable $URI_creator
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function setURICreator( callable $URI_creator )
	{
		$this->URI_creator = $URI_creator;

		return $this;
	}

	/**
	 * @param callable $validator
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function setValidator( callable $validator )
	{
		$this->validator = $validator;

		return $this;
	}

	/**
	 * Returns true if resolved
	 *
	 * @param string $path
	 *
	 * @return bool
	 */
	public function resolve( $path )
	{
		if( !$path ) {
			return false;
		}

		if( $this->resolver ) {
			$resolver = $this->resolver;

			return $resolver( $path, $this );
		}


		$matches = [];
		if( !preg_match( $this->regexp, $path, $matches ) ) {
			return false;
		}

		array_shift( $matches );

		if( $this->validator ) {
			$validator = $this->validator;

			if( !$validator( $matches, $this ) ) {
				return false;
			}
		}

		return true;
	}


	/**
	 *
	 * @param array $arguments
	 *
	 * @return string|bool
	 */
	public function URI( ...$arguments )
	{
		if(!$this->accessAllowed()) {
			return false;
		}

		return call_user_func_array( $this->URI_creator, $arguments );
	}

	/**
	 *
	 * @return bool
	 */
	public function accessAllowed()
	{

		$module_action = $this->getModuleAction();

		if( !$module_action ) {
			return true;
		}

		return $this->router->getController()->getModule()->accessAllowed( $module_action );
	}



}