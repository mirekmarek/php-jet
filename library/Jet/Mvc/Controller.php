<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
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
	 * controller_action => false|module_action_name
	 *
	 * @see Mvc_Modules_Module::$ACL_actions_check_map
	 *
	 *
	 * Example:
	 *
	 * <code>
	 * protected static $ACL_actions_check_map = array(
	 *      'get_public_data' => false, //do not check this
	 *      'get_data' => 'get_data_module_action',
	 *      'put_data' => 'update_record_module_action',
	 *      'post_data => 'add_record_module_action'
	 *      'delete_data => 'delete_record_module_action'
	 * );
	 * </code>
	 *
	 * @var array
	 */
	protected static $ACL_actions_check_map = [];
	/**
	 *
	 * @var Application_Module
	 */
	protected $module_instance;
	/**
	 * @var Mvc_View
	 */
	protected $view;
	/**
	 * @var string
	 */
	protected $current_action = '';
	/**
	 * @var array
	 */
	protected $action_parameters = [];

	/**
	 *
	 * @param Application_Module $module_instance
	 */
	public function __construct( Application_Module $module_instance )
	{
		$this->module_instance = $module_instance;

		$this->initializeDefaultView();
	}

	/**
	 * Creates default view instance
	 *
	 * @see Mvc_View
	 */
	protected function initializeDefaultView()
	{
		$this->view = Mvc_Factory::getViewInstance( $this->module_instance->getViewsDir() );
	}

	/**
	 * @param Mvc_Page_Content_Interface $page_content
	 *
	 * @return bool
	 */
	public function parseRequestPath( Mvc_Page_Content_Interface $page_content = null )
	{

		$router = $this->getControllerRouter();
		if( !$router ) {
			return false;
		}


		return $router->resolve( $page_content );
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
	 * @param string $action
	 * @param array  $action_parameters
	 *
	 * @throws Mvc_Controller_Exception
	 *
	 * @return bool
	 */
	public function checkACL( $action, $action_parameters )
	{

		$module_action = static::getModuleAction( $action );

		if( $module_action===false ) {
			return true;
		}

		if( !$this->module_instance->checkAclCanDoAction( $module_action ) ) {
			$this->responseAclAccessDenied( $module_action, $action, $action_parameters );

			return false;
		}

		return true;
	}

	/**
	 * @param string $controller_action
	 *
	 * @return string|bool
	 *
	 * @throws Mvc_Controller_Exception
	 */
	public static function getModuleAction( $controller_action )
	{
		if( !isset( static::$ACL_actions_check_map[$controller_action] ) ) {
			throw new Mvc_Controller_Exception(
				'Action \''.$controller_action.'\' is not specified in ACL check map! Please enter the ACL rules. Add '.get_called_class(
				).'::$ACL_actions_check_map['.$controller_action.'] entry.',
				Mvc_Controller_Exception::CODE_UNKNOWN_ACL_ACTION
			);
		}

		return static::$ACL_actions_check_map[$controller_action];
	}

	/**
	 * @param string $module_action
	 * @param string $controller_action
	 * @param array  $action_parameters
	 *
	 */
	abstract public function responseAclAccessDenied( $module_action, $controller_action, $action_parameters );

	/**
	 * @param string $action_message
	 * @param string $context_object_id
	 * @param string $context_object_name
	 * @param array  $context_object_data
	 */
	public function logAllowedAction( $action_message, $context_object_id = '', $context_object_name = '', $context_object_data = [] )
	{

		$action = $this->module_instance->getModuleManifest()->getName(
			).':'.static::$ACL_actions_check_map[$this->current_action];

		Application_Log::success(
			'allowed_action:'.$action, $action_message, $context_object_id, $context_object_name, $context_object_data
		);

	}

	/**
	 * @param string $action
	 * @param array  $action_parameters
	 *
	 * @throws Exception
	 */
	public function callAction( $action, array $action_parameters )
	{

		$method = $action.'_Action';

		if( !method_exists( $this, $method ) ) {
			throw new Exception(
				'Controller method '.get_class( $this ).'::'.$method.'() does not exist'
			);
		}

		$this->setActionParameters( $action_parameters );
		$this->setCurrentAction( $action );

		$this->{$method}();

	}

	/**
	 * @return string
	 */
	public function getCurrentAction()
	{
		return $this->current_action;
	}

	/**
	 * @param string $current_action
	 */
	public function setCurrentAction( $current_action )
	{
		$this->current_action = $current_action;
	}

	/**
	 * @return array
	 */
	public function getActionParameters()
	{
		return $this->action_parameters;
	}

	/**
	 * @param array $action_parameters
	 */
	public function setActionParameters( array $action_parameters )
	{
		$this->action_parameters = $action_parameters;
	}

	/**
	 * @param string $key
	 * @param mixed  $default_value
	 *
	 * @return mixed
	 */
	public function getActionParameterValue( $key, $default_value = null )
	{
		if( !array_key_exists( $key, $this->action_parameters ) ) {
			return $default_value;
		}

		return $this->action_parameters[$key];
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function getActionParameterExists( $key )
	{
		return array_key_exists( $key, $this->action_parameters );
	}

	/**
	 * Renders the output and adds it into the default layout.
	 *
	 * @param string $script
	 * @param string $position (optional, default: by current dispatcher queue item)
	 * @param bool   $position_required (optional, default: by current dispatcher queue item)
	 * @param int    $position_order (optional, default: by current dispatcher queue item)
	 */
	public function render( $script, $position = null, $position_required = null, $position_order = null )
	{

		$current_content = Mvc::getCurrentContent();

		if( !$position ) {
			$position = $current_content->getOutputPosition();
		}

		if( $position_required===null ) {
			$position_required = $current_content->getOutputPositionRequired();
		}

		if( $position_order===null ) {
			$position_order = $current_content->getOutputPositionOrder();
		}

		if( !$position ) {
			$position = Mvc_Layout::DEFAULT_OUTPUT_POSITION;
		}

		$output_id = $current_content->getKey();

		Mvc_Layout::getCurrentLayout()->renderView(
			$this->view, $script, $position, $position_required, $position_order, $output_id
		);

		return;
	}

}