<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Logger;
use Jet\MVC;
use Jet\MVC_Base_Interface;
use Jet\MVC_Router;
use Jet\Auth;

/**
 *
 */
class Application_REST
{
	/**
	 * @return string
	 */
	public static function getBaseId(): string
	{
		return 'rest';
	}

	/**
	 * @return MVC_Base_Interface
	 */
	public static function getBase(): MVC_Base_Interface
	{
		return MVC::getBase( static::getBaseId() );
	}

	/**
	 * @param MVC_Router $router
	 */
	public static function init( MVC_Router $router ): void
	{
		Logger::setLogger( new Logger_REST() );
		Auth::setController( new Auth_Controller_REST() );
	}

}