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
class MVC_Controller_Router_Action extends BaseObject
{

	/**
	 * @var ?MVC_Controller_Router
	 */
	protected ?MVC_Controller_Router $router = null;

	/**
	 * @var string
	 */
	protected string $controller_action = '';

	/**
	 * @var string
	 */
	protected string $module_action = '';

	/**
	 * @var callable|null
	 */
	protected $resolver = null;

	/**
	 * @var callable
	 */
	protected $URI_creator;

	/**
	 * @param MVC_Controller_Router $router
	 * @param string $controller_action
	 * @param string $module_action
	 */
	public function __construct( MVC_Controller_Router $router, string $controller_action, string $module_action )
	{
		$this->router = $router;
		$this->controller_action = $controller_action;
		$this->module_action = $module_action;
	}

	/**
	 * @return MVC_Controller_Router
	 */
	public function router(): MVC_Controller_Router
	{
		return $this->router;
	}

	/**
	 * @return MVC_Controller
	 */
	public function controller(): MVC_Controller
	{
		return $this->router()->getController();
	}


	/**
	 * @return string
	 */
	public function getControllerAction(): string
	{
		return $this->controller_action;
	}

	/**
	 * @return string
	 */
	public function getModuleAction(): string
	{
		return $this->module_action;
	}


	/**
	 * Callback prototype:
	 *
	 * someCallback( MVC_Controller_Router_Action $action )
	 *
	 * Callback return value: bool, true if resolved
	 *
	 * @param callable $resolver
	 *
	 * @return $this
	 */
	public function setResolver( callable $resolver ): static
	{
		$this->resolver = $resolver;

		return $this;
	}

	/**
	 * @param callable $URI_creator
	 *
	 * @return $this
	 */
	public function setURICreator( callable $URI_creator ): static
	{
		$this->URI_creator = $URI_creator;

		return $this;
	}


	/**
	 *
	 * @return bool|string
	 */
	public function resolve(): bool|string
	{
		if( !$this->resolver ) {
			return false;
		}

		$resolver = $this->resolver;

		return $resolver();
	}


	/**
	 *
	 * @param array $arguments
	 *
	 * @return string|bool
	 */
	public function URI( ...$arguments ): string|bool
	{
		if( !$this->authorize() ) {
			return false;
		}

		return call_user_func_array( $this->URI_creator, $arguments );
	}

	/**
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{

		$module_action = $this->getModuleAction();

		if( !$module_action ) {
			return true;
		}

		return $this->router->getController()->getModule()->actionIsAllowed( $module_action );
	}


}