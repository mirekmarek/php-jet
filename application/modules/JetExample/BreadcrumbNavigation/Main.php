<?php
/**
 *
 *
 *
 * BreadcrumbNavigation module
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule_BreadcrumbNavigation
 */
namespace JetApplicationModule\JetExample\BreadcrumbNavigation;
use Jet;

class Main extends Jet\Application_Modules_Module_Abstract {
	
	/**
	 * Initialization method 
	 */
	protected function initialize() {
	}

	/**
	 * @param Jet\Mvc_Router_Abstract $router
	 * @param Jet\Mvc_Dispatcher_Queue_Item $dispatch_queue_item
	 */
	public function resolveRequest( Jet\Mvc_Router_Abstract $router, Jet\Mvc_Dispatcher_Queue_Item $dispatch_queue_item=null ) {
	}

}