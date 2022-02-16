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
abstract class MVC_Controller extends BaseObject
{
	/**
	 * @var MVC_Page_Content_Interface
	 */
	protected MVC_Page_Content_Interface $content;

	/**
	 *
	 * @var Application_Module|null
	 */
	protected ?Application_Module $module = null;

	/**
	 * @var MVC_View
	 */
	protected MVC_View $view;

	/**
	 *
	 * @param MVC_Page_Content_Interface $content
	 */
	public function __construct( MVC_Page_Content_Interface $content )
	{
		$this->module = $content->getModuleInstance();
		$this->content = $content;

		$this->initializeDefaultView();
	}

	/**
	 *
	 * @see MVC_View
	 */
	protected function initializeDefaultView(): void
	{
		$this->view = Factory_MVC::getViewInstance( $this->module->getViewsDir() );
		$this->view->setController( $this );
	}


	/**
	 * @return MVC_Page_Content_Interface
	 */
	public function getContent(): MVC_Page_Content_Interface
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
	 * @return MVC_Controller_Router_Interface|null
	 */
	public function getControllerRouter(): MVC_Controller_Router_Interface|null
	{
		return null;
	}

	/**
	 *
	 */
	abstract public function handleNotAuthorized(): void;

}