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
class MVC_Controller_REST_Router extends MVC_Controller_Router
{

	/**
	 * @var ?MVC_Controller
	 */
	protected ?MVC_Controller $controller = null;

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
	 * @param MVC_Controller_REST $controller
	 * @param array $actions_map
	 */
	public function __construct( MVC_Controller_REST $controller, array $actions_map )
	{
		$this->controller = $controller;
		$this->actions_map = $actions_map;
	}


	/**
	 * @return MVC_Controller
	 */
	public function getController(): MVC_Controller
	{
		return $this->controller;
	}

	/**
	 * @return callable
	 */
	public function getPreparer(): callable
	{
		return $this->preparer;
	}

	/**
	 * @param callable $preparer
	 *
	 * @return $this
	 */
	public function setPreparer( callable $preparer ): static
	{
		$this->preparer = $preparer;

		return $this;
	}

	/**
	 * @return callable|null
	 */
	public function getResolverGet(): callable|null
	{
		return $this->resolver_get;
	}

	/**
	 * @param callable $resolver_get
	 *
	 * @return $this
	 */
	public function setResolverGet( callable $resolver_get ): static
	{
		$this->resolver_get = $resolver_get;

		return $this;
	}

	/**
	 * @return callable|null
	 */
	public function getResolverPost(): callable|null
	{
		return $this->resolver_post;
	}

	/**
	 * @param callable $resolver_post
	 *
	 * @return $this
	 */
	public function setResolverPost( callable $resolver_post ): static
	{
		$this->resolver_post = $resolver_post;

		return $this;
	}

	/**
	 * @return callable|null
	 */
	public function getResolverPut(): callable|null
	{
		return $this->resolver_put;
	}

	/**
	 * @param callable $resolver_put
	 *
	 * @return $this
	 */
	public function setResolverPut( callable $resolver_put ): static
	{
		$this->resolver_put = $resolver_put;

		return $this;
	}

	/**
	 * @return callable|null
	 */
	public function getResolverDelete(): callable|null
	{
		return $this->resolver_delete;
	}

	/**
	 * @param callable $resolver_delete
	 *
	 * @return $this
	 */
	public function setResolverDelete( callable $resolver_delete ): static
	{
		$this->resolver_delete = $resolver_delete;

		return $this;
	}


	/**
	 *
	 * @return bool|string
	 */
	public function resolve(): bool|string
	{
		$path = MVC::getRouter()->getUrlPath();

		$preparer = $this->getPreparer();
		if( !$preparer( $path ) ) {
			return false;
		}

		switch( RESTServer::getRequestMethod() ) {
			case RESTServer::REQUEST_METHOD_GET:
				$resolver = $this->getResolverGet();
				break;
			case RESTServer::REQUEST_METHOD_POST:
				$resolver = $this->getResolverPost();
				break;
			case RESTServer::REQUEST_METHOD_PUT:
				$resolver = $this->getResolverPut();
				break;
			case RESTServer::REQUEST_METHOD_DELETE:
				$resolver = $this->getResolverDelete();
				break;
			default:
				return false;
		}

		$controller_action = $resolver();

		if( !$controller_action ) {
			return false;
		}

		$module_action = $this->actions_map[$controller_action];

		if( $module_action ) {
			if( !$this->controller->getModule()->actionIsAllowed( $module_action ) ) {
				$this->controller->handleNotAuthorized();
			}
		}

		MVC::getRouter()->setUsedUrlPath( $path );

		return $controller_action;
	}

}