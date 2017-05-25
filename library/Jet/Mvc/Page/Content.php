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
	protected $controller_action_parameters = [];

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
	 *
	 */
	protected $_controller_instance;

	/**
	 * @param string $module_name (optional)
	 * @param string $controller_action (optional)
	 * @param array  $controller_action_parameters (optional)
	 * @param string $output_position (optional)
	 * @param int    $output_position_order (optional)
	 */
	public function __construct( $module_name = '', $controller_action = '', $controller_action_parameters = [], $output_position = '', $output_position_order = 0 )
	{

		$this->module_name = $module_name;
		$this->controller_action = $controller_action;
		$this->controller_action_parameters = $controller_action_parameters;

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
		if( !isset( $data['controller_action_parameters'] ) ) {
			$data['controller_action_parameters'] = [];
		}

		if(
			!is_array( $data['controller_action_parameters'] ) &&
			$data['controller_action_parameters']
		) {
			$data['controller_action_parameters'] = [ $data['controller_action_parameters'] ];
		} else {
			$data['controller_action_parameters'] = [];
		}

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

		$block_name = $module_name.':'.$controller_action;

		Debug_Profiler::blockStart( 'Dispatch '.$block_name );


		$controller = $this->getControllerInstance();

		if( !$controller ) {

			Debug_Profiler::message( 'Module is not installed and/or activated - skipping' );

		} else {
			Debug_Profiler::message( 'Dispatch:'.$this->getPage()->getKey().'|'.$module_name.':'.get_class($controller).':'.$controller_action );

			$translator_namespace = Translator::getCurrentNamespace();
			Translator::setCurrentNamespace( $module_name );

			$module_instance = Application_Modules::getModuleInstance( $module_name );
			$module_instance->callControllerAction(
				$controller,
				$controller_action,
				$this->getControllerActionParameters()
			);



			Translator::setCurrentNamespace( $translator_namespace );
		}

		Debug_Profiler::blockEnd( 'Dispatch '.$block_name );

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
	 * @return string
	 */
	public function getControllerAction()
	{
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
	 *
	 * @return Mvc_Controller|bool
	 */
	public function getControllerInstance()
	{
		if( $this->_controller_instance!==null ) {
			return $this->_controller_instance;
		}


		$module_name = $this->getModuleName();

		if( !Application_Modules::getModuleIsActivated( $module_name ) ) {
			$this->_controller_instance = false;

			return false;
		}

		$module_instance = Application_Modules::getModuleInstance( $module_name );

		if( !$module_instance ) {

			return false;
		}

		$this->_controller_instance = $module_instance->getControllerInstance( $this );

		return $this->_controller_instance;
	}

	/**
	 * @return array
	 */
	public function getControllerActionParameters()
	{
		return $this->controller_action_parameters;
	}

	/**
	 * @param array $controller_action_parameters
	 */
	public function setControllerActionParameters( array $controller_action_parameters )
	{
		$this->controller_action_parameters = $controller_action_parameters;
	}


}