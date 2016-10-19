<?php
/**
 *
 *
 *
 * Main controller class
 * @see Mvc/readme.txt
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Controller
 */
namespace Jet;

abstract class Mvc_Controller_Abstract extends BaseObject {
	/**
	 *
	 * @var Application_Modules_Module_Abstract
	 */
	protected $module_instance;

	/**
	 *
	 * @var Application_Modules_Module_Manifest
	 */
	protected $module_manifest;

	/**
	 * @var Mvc_Controller_Router
	 */
	protected static $controller_router;

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
	protected static $ACL_actions_check_map = [
	];



	/**
	 *
	 * @param Application_Modules_Module_Abstract $module_instance
	 */
	public function __construct( Application_Modules_Module_Abstract $module_instance ) {
		$this->module_instance = $module_instance;
		$this->module_manifest = $module_instance->getModuleManifest();

		$this->initializeDefaultView();
	}


	/**
	 * @param Mvc_Page_Content_Interface $page_content
	 *
	 * @return bool
	 */
	public function parseRequestURL( Mvc_Page_Content_Interface $page_content=null ) {
		$router = static::getControllerRouter();

		if(!$router) {
			return false;
		}

		return $router->resolve( $page_content );
	}


	/**
	 *
	 * @return Mvc_Controller_Router|null
	 */
	public static function getControllerRouter() {
		return null;
	}


	/**
	 * Is called after controller instance is created
	 */
	abstract public function initialize();

	/**
	 * @param $controller_action
	 * @return string|bool
	 *
	 * @throws Mvc_Controller_Exception
	 */
	public static function getModuleAction( $controller_action )
	{
		if(!isset(static::$ACL_actions_check_map[$controller_action])) {
			throw new Mvc_Controller_Exception(
				'Action \''.$controller_action.'\' is not specified in ACL check map! Please specify the ACL rules. Add '.get_called_class().'::$ACL_actions_check_map['.$controller_action.'] entry.',
				Mvc_Controller_Exception::CODE_UNKNOWN_ACL_ACTION
			);
		}

		return static::$ACL_actions_check_map[$controller_action];
	}


	/**
	 * @param string $action
	 * @param array $action_parameters
	 *
	 * @throws Mvc_Controller_Exception
	 *
	 * @return bool
	 */
	public function checkACL( $action, $action_parameters ) {

		$module_action = $this->getModuleAction($action);

		if($module_action===false) {
			return true;
		}

		if( !$this->module_instance->checkAclCanDoAction( $module_action ) ) {
			$this->responseAclAccessDenied( $module_action, $action, $action_parameters );

			return false;
		}

		return true;
	}

	/**
	 * @param mixed $action_parameters
	 * @param string|null $action
	 */
	public function logAllowedAction( $action_parameters=null, $action=null )
	{
		if( $action_parameters && $action_parameters instanceof BaseObject_Serializable_JSON) {
			$action_parameters = $action_parameters->jsonSerialize();
		}

		$action_parameters = ($action_parameters!==null) ? $action_parameters : $this->action_parameters;
		$action = ($action) ?
			$action
			:
			$this->module_manifest->getName().':'.static::$ACL_actions_check_map[$this->current_action];

		Auth::logEvent(
			'action:'.$action,
			['action_params'=>$action_parameters],
			'Allowed action: '.$action
		);

	}

    /**
     * @param string $action
     * @param array $action_parameters
     *
     * @throws Exception
     */
    public function callAction( $action, array $action_parameters ) {

        $method = $action.'_Action';

        if( !method_exists($this, $method) ) {
            throw new Exception(
                'Controller method '. get_class($this).'::'.$method.'() does not exist'
            );
        }

        $this->setActionParameters($action_parameters);
	    $this->setCurrentAction($action);

        $this->{$method}();

    }


	/**
	 * @param string $module_action
	 * @param string $controller_action
	 * @param array $action_parameters
	 *
	 */
	abstract public function responseAclAccessDenied( $module_action, $controller_action, $action_parameters );

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
	public function setCurrentAction($current_action)
	{
		$this->current_action = $current_action;
	}

    /**
     * @param array $action_parameters
     */
    public function setActionParameters( array $action_parameters)
    {
        $this->action_parameters = $action_parameters;
    }

    /**
     * @return array
     */
    public function getActionParameters()
    {
        return $this->action_parameters;
    }

    /**
     * @param string $key
     * @param mixed $default_value
     *
     * @return mixed
     */
    public function getActionParameterValue( $key, $default_value=null ) {
        if(!array_key_exists($key, $this->action_parameters)) {
            return $default_value;
        }

        return $this->action_parameters[$key];
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function getActionParameterExists( $key ) {
        return array_key_exists($key, $this->action_parameters);
    }


	/**
	 * Creates default view instance
	 *
	 * @see Mvc_View
	 */
	protected function initializeDefaultView() {
		$this->view = new Mvc_View( $this->module_instance->getViewsDir() );
	}

	/**
	 * Renders the output and adds it into the default layout.
	 * @see Mvc/readme.txt
	 *
	 * @param string $script
	 * @param string $position (optional, default: by current dispatcher queue item)
	 * @param bool $position_required (optional, default: by current dispatcher queue item)
	 * @param int $position_order (optional, default: by current dispatcher queue item)
	 */
	public function render(
		$script,
		$position = null,
		$position_required = null,
		$position_order = null
	) {

		$current_content = Mvc::getCurrentContent();

		if(!$position) {
			$position = $current_content->getOutputPosition();
		}

		if($position_required===null) {
			$position_required = $current_content->getOutputPositionRequired();
		}

		if($position_order===null) {
			$position_order = $current_content->getOutputPositionOrder();
		}

		if(!$position) {
			$position = Mvc_Layout::DEFAULT_OUTPUT_POSITION;
		}

		$output_ID = $current_content->getContentKey();

		Mvc_Layout::getCurrentLayout()->renderView(
			$this->view,
			$script,
			$position,
			$position_required,
			$position_order,
			$output_ID
		);

		return;
	}

}