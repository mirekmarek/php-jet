<?php
/**
 *
 *
 *
 * Main router class
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
 * @subpackage Mvc_Router
 */
namespace Jet;

class Mvc_Router extends Object {
	
	const SERVICE_TYPE_AJAX = "AJAX";
	const SERVICE_TYPE_SYS = "SYS";
	const SERVICE_TYPE_REST = "REST";
	const SERVICE_TYPE_STANDARD = "Standard";
	const SERVICE_TYPE__JETJS_ = "_JetJS_";

	const REDIRECT_TYPE_PERMANENTLY = "permanently";
	const REDIRECT_TYPE_TEMPORARY = "temporary";


	/**
	 *
	 * @var Mvc_Router_Abstract
	 */
	protected static $current_router_instance = NULL;

	/**
	 * @var Mvc_Router_Abstract[]
	 */
	protected static $routers = array();

	/**
	* Returns router singleton instance
	*
	* @return Mvc_Router_Abstract
	*/
	public static function getNewRouterInstance() {
		static::$current_router_instance = Mvc_Factory::getRouterInstance();

		static::$routers[] = static::$current_router_instance;

		return static::$current_router_instance;
	}


	/**
	 * @static
	 * @return null|Mvc_Router_Abstract
	 */
	public static function getCurrentRouterInstance() {
		return static::$current_router_instance;
	}

	/**
	 * @static
	 *
	 */
	public static function dropCurrentRouterInstance() {
		if(!static::$routers) {
			return;
		}
		unset(static::$routers[count(static::$routers)-1]);

		if(static::$routers) {
			static::$current_router_instance = static::$routers[count(static::$routers)-1];
		} else {
			static::$current_router_instance = null;
		}
	}
}