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

class Mvc_Controller_MicroRouter_Action extends Object {

	/**
	 * @var string
	 */
	protected $regexp = '';

	/**
	 * @var string
	 */
	protected $action_name = '';

	/**
	 * @var array
	 */
	protected $action_parameters = array();


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
	protected $create_URI_callback;

	/**
	 * @param string $controller_action_name
	 * @param string $regexp
	 */
	public function __construct( $controller_action_name, $regexp ) {
		$this->setActionName( $controller_action_name );
		$this->setRegexp( $regexp );
	}


	/**
	 * @param string $action_name
	 *
	 * @return Mvc_Controller_MicroRouter_Action
	 */
	public function setActionName($action_name) {
		$this->action_name = $action_name;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getActionName() {
		return $this->action_name;
	}

	/**
	 * @param array $action_parameters
	 *
	 * @return Mvc_Controller_MicroRouter_Action
	 */
	public function setActionParameters($action_parameters) {
		$this->action_parameters = $action_parameters;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getActionParameters() {
		return $this->action_parameters;
	}

	/**
	 * @param string $regexp
	 *
	 * @return Mvc_Controller_MicroRouter_Action
	 */
	public function setRegexp($regexp) {
		$this->regexp = $regexp;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getRegexp() {
		return $this->regexp;
	}

	/**
	 * Callback prototype:
	 *
	 * someCallback( Mvc_Controller_MicroRouter $micro_router, Mvc_Controller_MicroRouter_Action $action )
	 *
	 * Callback return value: bool, true if resolved
	 *
	 * @param callable $resolve_callback
	 *
	 * @return Mvc_Controller_MicroRouter_Action
	 */
	public function setResolveCallback( callable $resolve_callback) {
		$this->resolve_callback = $resolve_callback;

		return $this;
	}

	/**
	 * Callback prototype:
	 *
	 * someCallback( Mvc_Controller_MicroRouter $micro_router, Mvc_Controller_MicroRouter_Action $action )
	 *
	 * Callback return value: string or false
	 *
	 * @param callable $get_path_fragment_callback
	 *
	 * @return Mvc_Controller_MicroRouter_Action
	 */
	public function setGetPathFragmentCallback($get_path_fragment_callback) {
		$this->get_path_fragment_callback = $get_path_fragment_callback;

		return $this;
	}

	/**
	 * @param Mvc_Controller_MicroRouter $micro_router
	 * @return string|bool
	 */
	protected function getPathFragment( Mvc_Controller_MicroRouter $micro_router  ) {
		if($this->get_path_fragment_callback) {
			$callback = $this->get_path_fragment_callback;

			return $callback( $micro_router, $this );
		}


		$router = $micro_router->getController()->getRouter();

		$path_fragments = $router->getPathFragments();
		if(!$path_fragments) {
			return false;
		}

		return $path_fragments[0];

	}

	/**
	 * Returns true if resolved
	 *
	 * @param Mvc_Controller_MicroRouter $micro_router
	 *
	 * @return bool
	 */
	public function resolve( Mvc_Controller_MicroRouter $micro_router ) {
		if($this->resolve_callback) {
			$callback = $this->resolve_callback;

			return $callback( $micro_router, $this );
		}


		$path_fragment = $this->getPathFragment( $micro_router );

		if(!$path_fragment) {
			return false;
		}

		$matches = array();
		if( !preg_match( $this->regexp, $path_fragment, $matches ) ) {
			return false;
		}

		array_shift( $matches );

		$this->action_parameters = $matches;

		$micro_router->getController()->getRouter()->putUsedPathFragment( $path_fragment );

		return true;
	}

	/**
	 * @param callable $create_URI_callback
	 *
	 * @return Mvc_Controller_MicroRouter_Action
	 */
	public function setCreateURICallback(callable $create_URI_callback){
		$this->create_URI_callback = $create_URI_callback;

		return $this;
	}

	/**
	 * @param array $arguments
	 * @return string
	 */
	public function getURI( array $arguments ) {
		return call_user_func_array( $this->create_URI_callback, $arguments );
	}


}