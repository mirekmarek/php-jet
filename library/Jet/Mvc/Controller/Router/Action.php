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
	protected $resolve_callback;

	/**
	 * @var callable
	 */
	protected $get_path_fragment_callback;

	/**
	 * @var callable
	 */
	protected $parameters_validator_callback;

	/**
	 * @var callable
	 */
	protected $create_URI_callback;

	/**
	 * @param string $controller_action_name
	 * @param string $regexp
	 * @param string $ACL_action
	 */
	public function __construct( $controller_action_name, $regexp, $ACL_action )
	{
		$this->setActionName( $controller_action_name );
		$this->setRegexp( $regexp );
		$this->ACL_action = $ACL_action;
	}

	/**
	 * @return Mvc_Controller_Router
	 */
	public function getRouter()
	{
		return $this->router;
	}

	/**
	 * @param Mvc_Controller_Router $router
	 */
	public function setRouter( $router )
	{
		$this->router = $router;
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
	 * @param callable $resolve_callback
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function setResolveCallback( callable $resolve_callback )
	{
		$this->resolve_callback = $resolve_callback;

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

		if( $this->resolve_callback ) {
			$callback = $this->resolve_callback;

			return $callback( $path, $this );
		}


		$matches = [];
		if( !preg_match( $this->regexp, $path, $matches ) ) {
			return false;
		}

		array_shift( $matches );

		if( $this->parameters_validator_callback ) {
			$callback = $this->parameters_validator_callback;

			if( !$callback( $matches ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @param callable $create_URI_callback
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function setCreateURICallback( callable $create_URI_callback )
	{
		$this->create_URI_callback = $create_URI_callback;

		return $this;
	}

	/**
	 * @param callable $parameters_validator_callback
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function setParametersValidatorCallback( callable $parameters_validator_callback )
	{
		$this->parameters_validator_callback = $parameters_validator_callback;

		return $this;
	}


	/**
	 * @param array $arguments
	 *
	 * @return string
	 */
	public function getURI( array $arguments )
	{
		return call_user_func_array( $this->create_URI_callback, $arguments );
	}


}