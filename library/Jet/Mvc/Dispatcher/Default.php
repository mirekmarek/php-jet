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
		Debug_Profiler::MainBlockStart('Modules dispatch');

		if(!$this->router) {
			throw new Mvc_Dispatcher_Exception(
				'Dispatcher is not initialized yet. Please call $dispatcher->initialize($router); first! ',
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
		Debug_Profiler::MainBlockEnd('Modules dispatch');

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

		$controller_action = $queue_item->getControllerAction();

		$block_name =  $module_name.':'.$this->service_type.':'.$controller_action;

		Debug_Profiler::blockStart( 'Dispatch '.$block_name );


		if(!Application_Modules::getModuleIsActivated($module_name)) {

			Debug_Profiler::message('Module is not installed and/or activated - skipping');
			Debug_Profiler::blockEnd( 'Dispatch '.$block_name );

			return false;
		}


		$this->current_loop_ID =
				$block_name.':'.$this->loop_counter;

		Debug_Profiler::message('Loop ID:'.$this->current_loop_ID);

		$this->loop_counter++;

		$layout = $this->router->getLayout();

		if(
			$layout &&
			($output_part = $this->router->getCacheOutputParts($this->current_loop_ID))
		) {

			$layout->setOutputPart($output_part);
			if( $output_part->getIsStatic() ) {

				Debug_Profiler::message('Cache hit: IS STATIC');
				Debug_Profiler::blockEnd( 'Dispatch '.$block_name );

				return true;
			}
		}

		$module_instance = Application_Modules::getModuleInstance( $module_name );
		if(!$module_instance) {
			Debug_Profiler::message('Module is not installed and/or activated - skipping');
			Debug_Profiler::blockEnd( 'Dispatch '.$block_name );

			return false;
		}

		$this->current_loop_provides_dynamic_content = false;


		Translator::setCurrentNamespace( $module_name );

		$custom_service_type = $queue_item->getCustomServiceType();

		$controller = $module_instance->getControllerInstance(
											$this,
											$custom_service_type ? $custom_service_type: $this->service_type
										);

		$module_instance->callControllerAction(
											$controller,
											$controller_action,
											$queue_item->getControllerActionParameters()
										);


		if( ($output_part = $layout->getOutputPart($this->current_loop_ID)) ) {

			if( $this->current_loop_provides_dynamic_content ) {
				$output_part->setIsStatic(false);
			} else {
				Debug_Profiler::message('Is static');
			}

			$this->router->setCacheOutputParts($this->current_loop_ID, $output_part);
		}

		$this->current_queue_item = null;
		$this->current_loop_ID = null;

		Debug_Profiler::blockEnd( 'Dispatch '.$block_name );

		return true;
	}


	/**
	 *
	 */
	public function setCurrentLoopProvidesDynamicContent() {

		Debug_Profiler::message('Provides dynamic content' );

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