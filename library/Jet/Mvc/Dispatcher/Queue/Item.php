<?php
/**
 *
 *
 *
 * Class that describes the item in the dispatcher queue
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Dispatcher
 */
namespace Jet;

class Mvc_Dispatcher_Queue_Item extends Object {
	/**
	 *
	 * @var string
	 */
	protected $module_name = '';

	/**
	 * @var string
	 */
	protected $custom_service_type = null;

	/**
	 *
	 * @var string
	 */
	protected $controller_action = Mvc_Dispatcher::DEFAULT_ACTION;

	/**
	 * Action parameters (optional, example: path fragments - Each fragment is a single parameter for AJAX, REST and SYS requests)
	 * @var array
	 */
	protected $controller_action_parameters = array();

	/**
	 *
	 * @var Mvc_Pages_Page_Content_Abstract
	 */
	protected $content_data = NULL;

	/**
	 *
	 * @param string $module_name
	 * @param string $controller_action
	 * @param array $controller_action_parameters
	 * @param Mvc_Pages_Page_Content_Abstract $content_data (optional)
	 */
	public function  __construct( $module_name, $controller_action='', $controller_action_parameters=array(), Mvc_Pages_Page_Content_Abstract $content_data=NULL ) {
		
		$this->module_name = $module_name;

		if($controller_action) {
			$this->controller_action = $controller_action;
		}
		$this->controller_action_parameters = $controller_action_parameters;

		$this->content_data = $content_data;

	}

	/**
	 *
	 * @return string
	 */
	public function getModuleName() {
		return $this->module_name;
	}

	/**
	 * @param string $controller_action
	 */
	public function setControllerAction($controller_action) {
		$this->controller_action = $controller_action;
	}

	/**
	 * @param array $controller_action_parameters
	 */
	public function setControllerActionParameters( array $controller_action_parameters) {
		$this->controller_action_parameters = $controller_action_parameters;
	}



	/**
	 *
	 * @return string
	 */
	public function getControllerAction() {
		return $this->controller_action;
	}

	/**
	 *
	 * @return array
	 */
	public function getControllerActionParameters() {
		return $this->controller_action_parameters;
	}

	/**
	 *
	 * @return Mvc_Pages_Page_Content_Abstract
	 */
	public function getContentData() {
		return $this->content_data;
	}

	/**
	 * @return string
	 */
	public function getCustomServiceType() {
		return $this->custom_service_type;
	}

	/**
	 * @param string $custom_service_type
	 */
	public function setCustomServiceType($custom_service_type) {
		$this->custom_service_type = $custom_service_type;
	}

}