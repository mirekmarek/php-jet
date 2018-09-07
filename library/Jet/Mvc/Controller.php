<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * Format:
	 *
	 * controller_action => module_action_name | false
	 *
	 *
	 *
	 * Example:
	 *
	 * <code>
	 * const ACL_ACTIONS_MAP = [
	 *      'default' => Main::ACTION_GET,
	 *      'add'     => Main::ACTION_ADD,
	 *      'edit'    => Main::ACTION_UPDATE,
	 *      'view'    => Main::ACTION_GET,
	 *      'delete'  => Main::ACTION_DELETE,
	 * ];
	 *
	 * @var array
	 */
	const ACL_ACTIONS_MAP = [];

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
	 * @return Mvc_Controller_Router|null
	 */
	public function getControllerRouter()
	{
		return null;
	}

	/**
	 * @param string $controller_action
	 *
	 * @return string|bool
	 *
	 * @throws Mvc_Controller_Exception
	 */
	public function getModuleAction( $controller_action )
	{
		if( !array_key_exists( $controller_action, static::ACL_ACTIONS_MAP ) ) {
			throw new Mvc_Controller_Exception(
				'Action \''.$controller_action.'\' is not specified in ACL check map! Please enter the ACL rules. Add '.get_called_class().'::ACL_ACTIONS_MAP['.$controller_action.'] entry.',
				Mvc_Controller_Exception::CODE_UNKNOWN_ACL_ACTION
			);
		}

		return static::ACL_ACTIONS_MAP[$controller_action];
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
		$module_action = $this->getModuleAction( $this->content->getControllerAction() );

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
	 * @param string $path
	 *
	 * @return bool
	 */
	public function resolve( $path )
	{
		if(!$path) {
			return false;
		}

		$router = $this->getControllerRouter();
		if( !$router ) {
			return false;
		}


		return $router->resolve( $path );
	}



	/**
	 *
	 * @throws Mvc_Controller_Exception
	 *
	 * @return bool
	 */
	public function actionIsAllowed()
	{

		$module_action = $this->getModuleAction( $this->content->getControllerAction() );

		if( $module_action===false ) {
			return true;
		}

		if( !$this->module->actionIsAllowed( $module_action ) ) {
			$this->responseAccessDenied();

			return false;
		}

		return true;
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
	 * @param string $position (optional, default: by current dispatcher queue item)
	 * @param int    $position_order (optional, default: by current dispatcher queue item)
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


		return;
	}


}