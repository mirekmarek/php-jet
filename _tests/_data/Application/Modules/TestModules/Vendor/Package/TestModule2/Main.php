<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule_TestModule2
 */
namespace JetApplicationModule\Vendor\Package\TestModule2;
use Jet;
use Jet\Mvc_Dispatcher_Queue_Item;
use Jet\Mvc_Router_Abstract;

class Main extends Jet\Application_Modules_Module_Abstract {


	/**
	 * @param Mvc_Router_Abstract $router
	 * @param Mvc_Dispatcher_Queue_Item $dispatch_queue_item
	 */
	public function resolveRequest(Mvc_Router_Abstract $router, Mvc_Dispatcher_Queue_Item $dispatch_queue_item) {
	}
}