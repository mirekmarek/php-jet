<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Mvc_Controller_REST_Router extends BaseObject implements Mvc_Controller_Router_Interface
{

	/**
	 * @var ?Mvc_Controller
	 */
	protected ?Mvc_Controller $controller = null;

	/**
	 * @var ?array
	 */
	protected ?array $actions_map = null;

	/**
	 * @var callable|null
	 */
	protected $preparer = null;

	/**
	 * @var callable|null
	 */
	protected $resolver_get = null;

	/**
	 * @var callable|null
	 */
	protected $resolver_post = null;

	/**
	 * @var callable|null
	 */
	protected $resolver_put = null;

	/**
	 * @var callable|null
	 */
	protected $resolver_delete = null;


	/**
	 * @param Mvc_Controller_REST $controller
	 * @param array $actions_map
	 */
	public function __construct( Mvc_Controller_REST $controller, array $actions_map)
	{
		$this->controller = $controller;
		$this->actions_map = $actions_map;
	}


	/**
	 * @return Mvc_Controller
	 */
	public function getController() : Mvc_Controller
	{
		return $this->controller;
	}

	/**
	 * @return callable
	 */
	public function getPreparer() : callable
	{
		return $this->preparer;
	}

	/**
	 * @param callable $preparer
	 *
	 * @return $this
	 */
	public function setPreparer( callable $preparer ) : static
	{
		$this->preparer = $preparer;

		return $this;
	}

	/**
	 * @return callable|null
	 */
	public function getResolverGet() : callable|null
	{
		return $this->resolver_get;
	}

	/**
	 * @param callable $resolver_get
	 *
	 * @return $this
	 */
	public function setResolverGet( callable $resolver_get ) : static
	{
		$this->resolver_get = $resolver_get;

		return $this;
	}

	/**
	 * @return callable|null
	 */
	public function getResolverPost() : callable|null
	{
		return $this->resolver_post;
	}

	/**
	 * @param callable $resolver_post
	 *
	 * @return $this
	 */
	public function setResolverPost( callable $resolver_post ) : static
	{
		$this->resolver_post = $resolver_post;

		return $this;
	}

	/**
	 * @return callable|null
	 */
	public function getResolverPut() : callable|null
	{
		return $this->resolver_put;
	}

	/**
	 * @param callable $resolver_put
	 *
	 * @return $this
	 */
	public function setResolverPut( callable $resolver_put ) : static
	{
		$this->resolver_put = $resolver_put;

		return $this;
	}

	/**
	 * @return callable|null
	 */
	public function getResolverDelete() : callable|null
	{
		return $this->resolver_delete;
	}

	/**
	 * @param callable $resolver_delete
	 *
	 * @return $this
	 */
	public function setResolverDelete( callable $resolver_delete ) : static
	{
		$this->resolver_delete = $resolver_delete;

		return $this;
	}


	/**
	 *
	 * @return bool|string
	 */
	public function resolve() : bool|string
	{
		$path = Mvc::getRouter()->getPath();

		$preparer = $this->getPreparer();
		if(!$preparer( $path )) {
			return false;
		}

		switch( REST::getRequestMethod() ) {
			case REST::REQUEST_METHOD_GET:
				$resolver = $this->getResolverGet();
				break;
			case REST::REQUEST_METHOD_POST:
				$resolver = $this->getResolverPost();
				break;
			case REST::REQUEST_METHOD_PUT:
				$resolver = $this->getResolverPut();
				break;
			case REST::REQUEST_METHOD_DELETE:
				$resolver = $this->getResolverDelete();
				break;
			default:
				return false;
		}

		$controller_action = $resolver();

		if(!$controller_action) {
			return false;
		}

		$module_action = $this->actions_map[$controller_action];

		if($module_action) {
			if(!$this->controller->getModule()->actionIsAllowed( $module_action )) {
				$this->controller->responseAccessDenied();
			}
		}

		Mvc::getRouter()->setUsedPath( $path );

		return $controller_action;
	}

}