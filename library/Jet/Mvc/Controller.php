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
abstract class Mvc_Controller extends BaseObject
{
	/**
	 * @var Mvc_Page_Content_Interface
	 */
	protected $content;
	/**
	 *
	 * @var Application_Module
	 */
	protected $module;

	/**
	 * @var Mvc_View
	 */
	protected $view;

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
	 * Creates default view instance
	 *
	 * @see Mvc_View
	 */
	protected function initializeDefaultView()
	{
		$this->view = Mvc_Factory::getViewInstance( $this->module->getViewsDir() );
		$this->view->setController($this);
	}


	/**
	 * @return Mvc_Page_Content_Interface
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @return Application_Module
	 */
	public function getModule()
	{
		return $this->module;
	}

	/**
	 *
	 *
	 * @return Mvc_Controller_Router|Mvc_Controller_Router_Interface|null
	 */
	public function getControllerRouter()
	{
		return null;
	}

	/**
	 *
	 */
	abstract public function responseAccessDenied();

	/**
	 * @param string $action_message
	 * @param string $context_object_id
	 * @param string $context_object_name
	 * @param array  $context_object_data
	 */
	public function logAllowedAction( $action_message, $context_object_id = '', $context_object_name = '', $context_object_data = [] )
	{

		$module_name = $this->module->getModuleManifest()->getName();
		$module_action = $this->content->getControllerAction();

		Application_Logger::success(
			'allowed_action:'.$module_name.':'.$module_action,
			$action_message,
			$context_object_id,
			$context_object_name,
			$context_object_data
		);

	}


	/**
	 * @return array
	 */
	public function getParameters()
	{
		return $this->content->getParameters();
	}

	/**
	 * @param string $key
	 * @param mixed  $default_value
	 *
	 * @return mixed
	 */
	public function getParameter( $key, $default_value = null )
	{
		return $this->content->getParameter( $key, $default_value );
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function parameterExists( $key )
	{
		return $this->content->parameterExists( $key );
	}



	/**
	 *
	 * @return bool|string
	 */
	public function resolve()
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
	public function dispatch()
	{
		$method = $this->content->getControllerAction().'_Action';

		if( !method_exists( $this, $method ) ) {
			throw new Exception(
				'Controller method '.get_class( $this ).'::'.$method.'() does not exist'
			);
		}

		$this->{$method}();
	}

	/**
	 * Renders the output and adds it into the default layout.
	 *
	 * @param string $script
	 * @param string|null $position (optional, default: by current dispatcher queue item)
	 * @param int|null    $position_order (optional, default: by current dispatcher queue item)
	 */
	protected function render( $script, $position = null, $position_order = null )
	{


		if( !$position ) {
			$position = $this->content->getOutputPosition();
			if( !$position ) {
				$position = Mvc_Layout::DEFAULT_OUTPUT_POSITION;
			}
		}


		if( $position_order===null ) {
			$position_order = $this->content->getOutputPositionOrder();
		}


		$output = $this->view->render( $script );

		Mvc_Layout::getCurrentLayout()->addOutputPart(
			$output,
			$position,
			$position_order
		);

	}

}