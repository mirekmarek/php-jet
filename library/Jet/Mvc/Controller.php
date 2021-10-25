<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
abstract class Mvc_Controller extends BaseObject
{
	/**
	 * @var Mvc_Page_Content_Interface
	 */
	protected Mvc_Page_Content_Interface $content;

	/**
	 *
	 * @var Application_Module|null
	 */
	protected $module = null;

	/**
	 * @var Mvc_View
	 */
	protected Mvc_View $view;

	/**
	 *
	 * @param Mvc_Page_Content_Interface $content
	 */
	public function __construct( Mvc_Page_Content_Interface $content )
	{
		$this->module = $content->getModuleInstance();
		$this->content = $content;

		$this->initializeDefaultView();
	}

	/**
	 *
	 * @see Mvc_View
	 */
	protected function initializeDefaultView(): void
	{
		$this->view = Factory_Mvc::getViewInstance( $this->module->getViewsDir() );
		$this->view->setController( $this );
	}


	/**
	 * @return Mvc_Page_Content_Interface
	 */
	public function getContent(): Mvc_Page_Content_Interface
	{
		return $this->content;
	}

	/**
	 * @return Application_Module|null
	 */
	public function getModule(): Application_Module|null
	{
		return $this->module;
	}

	/**
	 *
	 * @return bool|string
	 */
	public function resolve(): bool|string
	{
		$router = $this->getControllerRouter();
		if( !$router ) {
			return true;
		}

		return $router->resolve();
	}


	/**
	 *
	 *
	 * @throws Exception
	 */
	public function dispatch(): void
	{
		$method = $this->content->getControllerAction() . '_Action';

		if( !method_exists( $this, $method ) ) {
			throw new Exception(
				'Controller method ' . get_class( $this ) . '::' . $method . '() does not exist'
			);
		}

		$this->{$method}();
	}

	/**
	 *
	 * @param string $view_script
	 */
	protected function output( string $view_script ): void
	{
		$output = $this->view->render( $view_script );

		$this->content->output( $output );
	}


	/**
	 * @return Mvc_Controller_Router_Interface|null
	 */
	public function getControllerRouter(): Mvc_Controller_Router_Interface|null
	{
		return null;
	}

	/**
	 *
	 */
	abstract public function handleNotAuthorized(): void;


	/**
	 * @param string $action_message
	 * @param string $context_object_id
	 * @param string $context_object_name
	 * @param mixed $context_object_data
	 */
	public function logAllowedAction( string $action_message,
	                                  string $context_object_id = '',
	                                  string $context_object_name = '',
	                                  mixed $context_object_data = [] )
	{

		$module_name = $this->module->getModuleManifest()->getName();
		$module_action = $this->content->getControllerAction();

		Logger::success(
			'allowed_action:' . $module_name . ':' . $module_action,
			$action_message,
			$context_object_id,
			$context_object_name,
			$context_object_data
		);

	}

}