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

abstract class Mvc_Dispatcher_Abstract extends Object {
	/**
	 * @var string
	 */
	protected static $__factory_class_name = "Jet\\Mvc_Factory";
	/**
	 * @var string
	 */
	protected static $__factory_class_method = "getDispatcherInstance";
	/**
	 * @var string
	 */
	protected static $__factory_must_be_instance_of_class_name = "Jet\\Mvc_Dispatcher_Abstract";

	/**
	 * Router
	 *
	 * @var Mvc_Router_Abstract
	 */
	protected $router = NULL;

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
	protected $loop_counter = 0;


	/**
	 * which service (Standard, AJAX, ...)
	 *
	 * @var string
	 */
	protected $service_type = "";

	/**
	 * @var string
	 */
	protected $current_loop_ID;


	/**
	 * @var bool
	 */
	protected $current_loop_provides_dynamic_content = false;

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
	 * @abstract
	 *
	 * @return Mvc_Dispatcher_Queue
	 */
	abstract public function getQueue();

	/**
	 * @abstract
	 *
	 * @return Mvc_Dispatcher_Queue_Item
	 */
	abstract public function getCurrentQueueItem();

	/**
	 * @param Mvc_Dispatcher_Queue_Item $queue_item
	 *
	 * @return true
	 */
	abstract public function dispatchQueueItem( Mvc_Dispatcher_Queue_Item $queue_item );

	/**
	 *
	 */
	abstract public function setCurrentLoopProvidesDynamicContent();

	/**
	 *
	 * @return bool
	 */
	abstract public function getCurrentLoopProvidesDynamicContent();

	/**
	 * @return bool
	 */
	abstract public function getRequestProvidesStaticContent();

	/**
	 * @abstract
	 *
	 * @return string
	 */
	abstract public function getCurrentLoopID();

}