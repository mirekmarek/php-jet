<?php
/**
 *
 *
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

class Mvc_Dispatcher {
	const DEFAULT_ACTION = 'default';

	/**
	 *
	 * @var Mvc_Dispatcher_Abstract
	 */
	protected static $current_dispatcher_instance = NULL;

	/**
	 * @var Mvc_Dispatcher_Abstract[]
	 */
	protected static $dispatchers = array();

	/**
	* Returns Dispatcher singleton instance
	*
	* @return Mvc_Dispatcher_Abstract
	*/
	public static function getNewDispatcherInstance() {
		static::$current_dispatcher_instance = Mvc_Factory::getDispatcherInstance();

		static::$dispatchers[] = static::$current_dispatcher_instance;

		return static::$current_dispatcher_instance;
	}

	/**
	 * @static
	 * @return null|Mvc_Dispatcher_Abstract
	 */
	public static function getCurrentDispatcherInstance() {
		return static::$current_dispatcher_instance;
	}

	/**
	 * @static
	 *
	 */
	public static function dropCurrentDispatcherInstance() {
		if(!static::$dispatchers) {
			return;
		}
		unset(static::$dispatchers[count(static::$dispatchers)-1]);

		if(static::$dispatchers) {
			static::$current_dispatcher_instance = static::$dispatchers[count(static::$dispatchers)-1];
		} else {
			static::$current_dispatcher_instance = null;
		}
	}

}