<?php
/**
 *
 *
 *
 * Default dispatcher class
 *
 * @see Mvc/readme.txt
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

class Mvc_Dispatcher_Default extends Mvc_Dispatcher_Abstract {

	/**
	 * Main initialization method
	 *
	 * @param Mvc_Router_Abstract $router
	 */
	public function initialize( Mvc_Router_Abstract $router ) {
		$this->router = $router;
		$this->service_type = $router->getServiceType();
		$this->router->setDispatcherInstance($this);

		$this->queue = $router->getUIManagerModuleInstance()->getDispatchQueue();
	}

	/**
	 * Dispatch: main method ...
	 *
	 *
	 * @throws Mvc_Dispatcher_Exception
	 *
	 * @return string|null
	 */
	public function dispatch() {

		if(!$this->router) {
			throw new Mvc_Dispatcher_Exception(
				"Dispatcher is not initialized yet. Please call \$dispatcher->initialize(\$router); first! ",
				Mvc_Dispatcher_Exception::CODE_DISPATCHER_IS_NOT_INITIALIZED
			);
		}

		$translator_namespace = Translator::COMMON_NAMESPACE;


		Translator::setCurrentLocale( $this->router->getLocale() );
		Translator::setCurrentNamespace( $translator_namespace );

		foreach( $this->queue as $qi ) {
			$this->dispatchQueueItem($qi);
		}

		Translator::setCurrentNamespace( $translator_namespace );

		$output = $this->router->getUIManagerModuleInstance()->finalizeDispatch();
		if($this->request_provides_static_content) {
			$this->router->setCacheOutput($output);
		}
		return $output;
	}

	/**
	 * @param Mvc_Dispatcher_Queue_Item $queue_item
	 *
	 * @return bool
	 */
	public function dispatchQueueItem( Mvc_Dispatcher_Queue_Item $queue_item ) {
		$this->current_queue_item = $queue_item;

		$module_name = $queue_item->getModuleName();
		if(!Application_Modules::getModuleIsActivated($module_name)) {
			return false;
		}

		$controller_class_suffix = $queue_item->getControllerClassSuffix();
		$controller_action = $queue_item->getControllerAction();

		$this->current_loop_ID =
				 "{$module_name}:"
				."{$controller_class_suffix}:"
				."{$this->service_type}:"
				."{$controller_action}:"
				.$this->loop_counter;

		$this->loop_counter++;

		$layout = $this->router->getLayout();

		if(
			$layout &&
			($output_part = $this->router->getCacheOutputParts($this->current_loop_ID))
		) {
			$layout->setOutputPart($output_part);
			if( $output_part->getIsStatic() ) {
				return;
			}
		}

		$this->current_loop_provides_dynamic_content = false;

		$translator_namespace = $module_name;

		Translator::setCurrentNamespace( $translator_namespace );

		$custom_service_type = $queue_item->getCustomServiceType();

		$controller = $this->getController(
			$module_name,
			$controller_class_suffix,
			$custom_service_type ? $custom_service_type: $this->service_type
		);

		if(!$controller) {
			//the module may not be installed and activated
			return false;
		}

		$this->callControllerAction(
			$controller,
			$controller_action,
			$queue_item->getControllerActionParameters()
		);

		if( ($output_part = $layout->getOutputPart($this->current_loop_ID)) ) {
			if($this->current_loop_provides_dynamic_content) {
				$output_part->setIsStatic(false);
			}

			$this->router->setCacheOutputParts($this->current_loop_ID, $output_part);
		}

		$this->current_queue_item = null;
		$this->current_loop_ID = null;

		return true;
	}


	/**
	 * Returns controller instance
	 *
	 * @abstract
	 *
	 * @param string $module_name
	 * @param string $controller_class_suffix
	 * @param string $service_type
	 *
	 * @throws Mvc_Dispatcher_Exception
	 * @internal param Mvc_Dispatcher_Queue_Item $queue_item
	 *
	 * @return Mvc_Controller_Abstract
	 */
	protected function getController( $module_name,  $controller_class_suffix, $service_type) {
		$module_info = Application_Modules::getModuleInfo($module_name, true);

		if(!$module_info) {
			//is not present ...
			return null;
		}

		$module_instance = Application_Modules::getModuleInstance( $module_name );
		$module_dir = $module_info->getModuleDir();

		if($controller_class_suffix) {
			$controller_class_suffix .=  "_";
		}


		$controller_suffix = "Controller_{$controller_class_suffix}{$service_type}";

		$controller_class_name = Application_Modules::MODULE_NAMESPACE."\\{$module_name}\\{$controller_suffix}";
		$controller_path = $module_dir.str_replace("_", "/", $controller_suffix).".php";

		/** @noinspection PhpIncludeInspection */
		require_once $controller_path;

		if(!class_exists($controller_class_name, false)) {
			throw new Mvc_Dispatcher_Exception(
				"Controller '$controller_class_name' does not exist. File: {$controller_path}",
				Mvc_Dispatcher_Exception::CODE_CONTROLLER_CLASS_DOES_NOT_EXIST
			);
		}

		$controller = new $controller_class_name( $module_instance, $module_info, $this->router, $this );

		if (!$controller instanceof Mvc_Controller_Abstract) {
			throw new Mvc_Dispatcher_Exception(
				"Controller '$controller_class_name' is not an instance of Mvc_Controller_Abstract",
				Mvc_Dispatcher_Exception::CODE_INVALID_CONTROLLER_CLASS
			);
		}

		return $controller;
	}


	/**
	 * Calls the action
	 *
	 * @param Mvc_Controller_Abstract $controller
	 * @param string $action
	 * @param array $action_parameters (optional)  @see Mvc_Dispatcher_QueueItem::$action_parameters
	 *
	 * @throws Mvc_Dispatcher_Exception
	 */
	protected function callControllerAction( Mvc_Controller_Abstract $controller, $action, array $action_parameters=array() ) {
		$method = $action."_Action";

		if( !method_exists($controller, $method) ) {
			throw new Mvc_Dispatcher_Exception(
				"Controller method ". get_class($controller)."->{$method}() does not exist",
				Mvc_Dispatcher_Exception::CODE_ACTION_DOES_NOT_EXIST
			);
		}

		$controller->checkACL($action, $action_parameters);

		call_user_func_array(array( $controller, $method ), $action_parameters);
	}

	/**
	 *
	 * @return Mvc_Dispatcher_Queue
	 */
	public function getQueue() {
		return $this->queue;
	}

	/**
	 *
	 * @return Mvc_Dispatcher_Queue_Item
	 */
	public function getCurrentQueueItem() {
		return $this->current_queue_item;
	}

	/**
	 *
	 */
	public function setCurrentLoopProvidesDynamicContent() {
		$this->current_loop_provides_dynamic_content = true;
		$this->request_provides_static_content = false;
	}

	/**
	 *
	 * @return bool
	 */
	public function getCurrentLoopProvidesDynamicContent() {
		return $this->current_loop_provides_dynamic_content;
	}

	/**
	 * @return bool
	 */
	public function getRequestProvidesStaticContent() {
		return $this->request_provides_static_content;
	}

	/**
	 * @return string
	 */
	public function getCurrentLoopID() {
		return $this->current_loop_ID;
	}

}