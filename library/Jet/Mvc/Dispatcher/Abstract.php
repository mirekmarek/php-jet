<?php
/**
 *
 *
 *
 * Dispatcher abstract class ..
 * @see Mvc/readme.txt
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Dispatcher
 */
namespace Jet;

/**
 * Class Mvc_Dispatcher_Abstract
 *
 * @JetApplication_Signals:signal = '/dispatcher/started'
 * @JetApplication_Signals:signal = '/dispatcher/step/started'
 * @JetApplication_Signals:signal = '/dispatcher/step/ended'
 * @JetApplication_Signals:signal = '/dispatcher/step-render-only/started'
 * @JetApplication_Signals:signal = '/dispatcher/step-render-only/ended'
 * @JetApplication_Signals:signal = '/dispatcher/ended'
 *
 * @JetFactory:class = 'Jet\Mvc_Factory'
 * @JetFactory:method = 'getDispatcherInstance'
 * @JetFactory:mandatory_parent_class = 'Jet\Mvc_Dispatcher_Abstract'
 */
abstract class Mvc_Dispatcher_Abstract extends Object {

	/**
	 * Router
	 *
	 * @var Mvc_Router_Abstract
	 */
	protected $router = null;

	/**
	 *
	 * @var Mvc_Dispatcher_Queue
	 */
	protected $queue = null;

	/**
	 *
	 * @var Mvc_Dispatcher_Queue_Item
	 */
	protected $current_queue_item;

	/**
	 * @var int
	 */
	protected $step_counter = 0;


	/**
	 * which service (Standard, AJAX, ...)
	 *
	 * @var string
	 */
	protected $service_type = '';

	/**
	 * @var string
	 */
	protected $current_step_ID;


	/**
	 * @var bool
	 */
	protected $current_step_provides_dynamic_content = false;

	/**
	 * @var bool
	 */
	protected $request_provides_static_content = true;


	/**
	 * Main initialization method
	 *
	 * @abstract
	 * @param Mvc_Router_Abstract $router
	 */
	abstract function initialize( Mvc_Router_Abstract $router );

	/**
	 * Dispatch: main method ...
	 *
	 * @abstract
	 *
	 * @throws Mvc_Dispatcher_Exception
	 *
	 * @return string|null
	 */
	abstract public function dispatch();



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
	 * @return \Jet\Mvc_Router_Abstract
	 */
	public function getRouter() {
		return $this->router;
	}


	/**
	 * @param Mvc_Dispatcher_Queue_Item $queue_item
	 *
	 * @return bool
	 */
	abstract public function dispatchQueueItem( Mvc_Dispatcher_Queue_Item $queue_item );

	/**
	 * @param Mvc_Dispatcher_Queue_Item $queue_item
	 *
	 * @return string
	 */
	abstract public function renderQueueItem( Mvc_Dispatcher_Queue_Item $queue_item );

	/**
	 *
	 */
	abstract public function setCurrentStepProvidesDynamicContent();

	/**
	 *
	 * @return bool
	 */
	abstract public function getCurrentStepProvidesDynamicContent();

	/**
	 * @return bool
	 */
	abstract public function getRequestProvidesStaticContent();

	/**
	 * @abstract
	 *
	 * @return string
	 */
	abstract public function getCurrentStepID();

}