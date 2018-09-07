<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Test\REST;

use Jet\Application_Module;
use Jet\Session;

/**
 *
 */
class Main extends Application_Module
{

	/**
	 * @var Session
	 */
	protected static $session;

	/**
	 * @return Session
	 */
	public static function getSession() {
		if(!static::$session) {
			static::$session = new Session('rest_rest');
		}

		return static::$session;
	}
}