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
	protected $action_name = '';

	/**
	 * @var string
	 */
	protected $ACL_action = '';

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
	 * @param string                $controller_action_name
	 * @param string                $regexp
	 * @param string                $ACL_action
	 */
	public function __construct( Mvc_Controller_Router $router, $controller_action_name, $regexp, $ACL_action )
	{
		$this->router = $router;
		$this->action_name = $controller_action_name;
		$this->regexp = $regexp;
		$this->ACL_action = $ACL_action;
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
	public function getActionName()
	{
		return $this->action_name;
	}

	/**
	 * @param string $action_name
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function setActionName( $action_name )
	{
		$this->action_name = $action_name;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getACLAction()
	{
		return $this->ACL_action;
	}

	/**
	 * @param string $ACL_action
	 */
	public function setACLAction( $ACL_action )
	{
		$this->ACL_action = $ACL_action;
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

		$ACL_action_name = $this->getACLAction();

		if( !$ACL_action_name ) {
			return true;
		}

		return $this->router->getController()->getModule()->accessAllowed( $ACL_action_name );
	}



}