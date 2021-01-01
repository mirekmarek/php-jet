<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @var ?Session
	 */
	protected static ?Session $session = null;

	/**
	 * @return Session
	 */
	public static function getSession() : Session {
		if(!static::$session) {
			static::$session = new Session('REST_test');
		}

		return static::$session;
	}
}