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
class Mvc_Controller_Router extends BaseObject
{

	/**
	 * @var Application_Module
	 */
	protected $module_instance;

	/**
	 * @var string
	 */
	protected $path = '';


	/**
	 * @var Mvc_Controller_Router_Action[]
	 */
	protected $actions = [];

	/**
	 * @var string
	 */
	protected $default_action_name = '';


	/**
	 * @param Application_Module $module_instance
	 * @param string|null        $path
	 */
	public function __construct( Application_Module $module_instance, $path=null )
	{
		$this->module_instance = $module_instance;
		if($path===null) {
			$path = Mvc::getRouter()->getPath();
		}

		$this->path = $path;
	}

	/**
	 * @param string $action_name
	 * @param string $regexp
	 * @param string $ACL_action
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function addAction( $action_name, $regexp, $ACL_action )
	{
		$action = new Mvc_Controller_Router_Action( $action_name, $regexp, $ACL_action );

		$this->actions[$action_name] = $action;

		return $action;
	}

	/**
	 * @return string
	 */
	public function getDefaultActionName()
	{
		return $this->default_action_name;
	}

	/**
	 * @param string $default_controller_action_name
	 */
	public function setDefaultActionName( $default_controller_action_name )
	{
		$this->default_action_name = $default_controller_action_name;
	}

	/**
	 * @return Mvc_Controller_Router_Action[]
	 */
	public function getActions()
	{
		return $this->actions;
	}

	/**
	 * @return Application_Module
	 */
	public function getModuleInstance()
	{
		return $this->module_instance;
	}


	/**
	 * @param Mvc_Page_Content_Interface $page_content
	 *
	 * @return bool
	 */
	public function resolve( Mvc_Page_Content_Interface $page_content )
	{

		if( $this->default_action_name ) {
			$action_name = $this->default_action_name;
			$action_parameters = [];
		} else {
			$action_name = null;
			$action_parameters = [];

		}

		foreach( $this->actions as $action ) {
			$action->setRouter($this);

			if( !$action->resolve( $this->path ) ) {
				continue;
			}

			$action_name = $action->getActionName();
			$action_parameters = $action->getActionParameters();

			break;
		}

		if( $action_name ) {
			$page_content->setControllerAction( $action_name );
			$page_content->setControllerActionParameters( $action_parameters );

			return true;
		}

		return false;

	}

	/**
	 *
	 * @param string $action_name
	 * @param ...
	 *
	 * @return string|bool
	 */
	public function getActionURI( $action_name )
	{

		$arguments = func_get_args();
		array_shift( $arguments );

		return $this->actions[$action_name]->getURI( $arguments );
	}

	/**
	 * @param string $action_name
	 *
	 * @return bool
	 */
	public function getActionAllowed( $action_name )
	{
		$action = $this->actions[$action_name];

		$ACL_action_name = $action->getACLAction();

		if( !$ACL_action_name ) {
			return true;
		}

		return $this->module_instance->checkAclCanDoAction( $ACL_action_name );
	}

}