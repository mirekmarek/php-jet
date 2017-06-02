<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

require_once 'Content/Interface.php';

/**
 *
 */
class Mvc_Page_Content extends BaseObject implements Mvc_Page_Content_Interface
{
	const DEFAULT_CONTROLLER_ACTION = 'default';

	/**
	 * @var Mvc_Page
	 */
	protected $page;

	/**
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 *
	 * @var string
	 */
	protected $module_name = '';

	/**
	 *
	 * @var string
	 */
	protected $custom_controller = '';

	/**
	 *
	 * @var string
	 */
	protected $controller_action = '';

	/**
	 *
	 * @var array
	 */
	protected $parameters = [];

	/**
	 *
	 * @var string|callable
	 */
	protected $output = '';

	/**
	 *
	 * @var string
	 */
	protected $output_position = '';

	/**
	 *
	 * @var int
	 */
	protected $output_position_order = 0;

	/**
	 * @var Application_Module
	 */
	protected $_module_instance;

	/**
	 *
	 * @var Mvc_Controller
	 */
	protected $_controller_instance;

	/**
	 * @param string $module_name (optional)
	 * @param string $controller_action (optional)
	 * @param array  $parameters (optional)
	 * @param string $output_position (optional)
	 * @param int    $output_position_order (optional)
	 */
	public function __construct( $module_name = '', $controller_action = '', $parameters = [], $output_position = '', $output_position_order = 0 )
	{

		$this->module_name = $module_name;
		$this->controller_action = $controller_action;
		$this->parameters = $parameters;

		$this->output_position = $output_position;
		$this->output_position_order = (int)$output_position_order;

	}

	/**
	 * @param array $data
	 *
	 * @return void
	 */
	public function setData( array $data )
	{
		foreach( $data as $key => $val ) {
			$this->{$key} = $val;
		}
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 *
	 */
	public function setId( $id )
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getCustomController()
	{
		return $this->custom_controller;
	}

	/**
	 * @param string $custom_controller
	 */
	public function setCustomController( $custom_controller )
	{
		$this->custom_controller = $custom_controller;
	}

	/**
	 * @return string
	 */
	public function getOutputPosition()
	{
		return $this->output_position;
	}

	/**
	 * @param string $output_position
	 */
	public function setOutputPosition( $output_position )
	{
		$this->output_position = $output_position;
	}


	/**
	 * @return int
	 */
	public function getOutputPositionOrder()
	{
		return $this->output_position_order;
	}

	/**
	 * @param int $output_position_order
	 */
	public function setOutputPositionOrder( $output_position_order )
	{
		$this->output_position_order = (int)$output_position_order;
	}

	/**
	 * @return string|callable
	 */
	public function getOutput()
	{
		return $this->output;
	}

	/**
	 * @param string|callable $output
	 */
	public function setOutput( $output )
	{
		$this->output = $output;
	}

	/**
	 * @return string
	 */
	public function getKey()
	{
		$page = $this->getPage();

		$site_id = $page->getSite()->getId();
		$locale = $page->getLocale();
		$page_id = $page->getId();

		return $site_id.':'.$locale.':'.$page_id.':'.$this->id;
	}

	/**
	 * @return Mvc_Page_Interface
	 */
	public function getPage()
	{
		if( !$this->page ) {
			return Mvc::getCurrentPage();
		}

		return $this->page;
	}

	/**
	 * @param Mvc_Page_Interface $page
	 */
	public function setPage( Mvc_Page_Interface $page )
	{
		$this->page = $page;
	}

	/**
	 * @return string
	 */
	public function getModuleName()
	{
		return $this->module_name;
	}

	/**
	 * @param string $module_name
	 */
	public function setModuleName( $module_name )
	{
		$this->module_name = $module_name;
	}

	/**
	 * @return Application_Module|bool
	 */
	public function getModuleInstance()
	{
		if( $this->_module_instance!==null ) {
			return $this->_module_instance;
		}


		$module_name = $this->getModuleName();

		if( !Application_Modules::getModuleIsActivated( $module_name ) ) {
			$this->_module_instance = false;

			return false;
		}

		$this->_module_instance = Application_Modules::getModuleInstance( $module_name );

		if( !$this->_module_instance ) {
			$this->_module_instance = false;

			return false;
		}

		return $this->_module_instance;
	}


	/**
	 * @return string
	 */
	public function getControllerAction()
	{
		if($this->controller_action===false) {
			return false;
		}

		return $this->controller_action ? $this->controller_action : static::DEFAULT_CONTROLLER_ACTION;
	}

	/**
	 * @param string $controller_action
	 */
	public function setControllerAction( $controller_action )
	{
		$this->controller_action = $controller_action;
	}



	/**
	 * @return array
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	/**
	 * @param array $parameters
	 */
	public function setParameters( array $parameters )
	{
		$this->parameters = $parameters;
	}

	/**
	 * @param string $key
	 * @param mixed  $default_value
	 *
	 * @return mixed
	 */
	public function getParameter( $key, $default_value = null )
	{
		if( !array_key_exists( $key, $this->parameters ) ) {
			return $default_value;
		}

		return $this->parameters[$key];
	}

	/**
	 * @param string $key
	 * @param mixed  $value
	 */
	public function setParameter( $key, $value )
	{
		$this->parameters[$key] = $value;
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function parameterExists( $key )
	{
		return array_key_exists( $key, $this->parameters );
	}


	/**
	 *
	 * @return Mvc_Controller|bool
	 */
	public function getControllerInstance()
	{
		if( $this->_controller_instance!==null ) {
			return $this->_controller_instance;
		}

		$module_instance = $this->getModuleInstance();
		if(!$module_instance) {
			return false;
		}

		$controller_class_name = $module_instance->getControllerClassName( $this );

		$this->_controller_instance = new $controller_class_name( $this );

		return $this->_controller_instance;
	}


	/**
	 *
	 */
	public function dispatch()
	{

		if( ($output=$this->getOutput()) ) {
			if(is_callable($output)) {
				$output = $output( $this->getPage(), $this );
			}

			Mvc_Layout::getCurrentLayout()->addOutputPart(
				$output,
				$this->output_position,
				$this->output_position_order,
				$this->getKey()
			);

			return;
		}

		$module_name = $this->getModuleName();
		$controller_action = $this->getControllerAction();

		if($controller_action===false) {
			return;
		}

		$block_name = $module_name.':'.$controller_action;

		Debug_Profiler::blockStart( 'Dispatch '.$block_name );


		$controller = $this->getControllerInstance();

		if( !$controller ) {

			Debug_Profiler::message( 'Module is not installed and/or activated - skipping' );

		} else {
			Debug_Profiler::message( 'Dispatch:'.$this->getPage()->getKey().'|'.$module_name.':'.get_class($controller).':'.$controller_action );

			$translator_namespace = Translator::getCurrentNamespace();
			Translator::setCurrentNamespace( $module_name );

			if( $controller->checkAccess()) {
				$controller->dispatch();
			}


			Translator::setCurrentNamespace( $translator_namespace );
		}

		Debug_Profiler::blockEnd( 'Dispatch '.$block_name );

	}


}