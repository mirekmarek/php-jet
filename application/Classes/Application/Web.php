<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Logger;

use Jet\Mvc_Base;
use Jet\Mvc_Router;

use Jet\Auth;

/**
 *
 */
class Application_Web
{
	/**
	 * @return string
	 */
	public static function getBaseId(): string
	{
		return 'web';
	}

	/**
	 * @return Mvc_Base
	 */
	public static function getBase(): Mvc_Base
	{
		return Mvc_Base::get( static::getBaseId() );
	}

	/**
	 * @param Mvc_Router $router
	 */
	public static function init( Mvc_Router $router ): void
	{
		Application::initErrorPages( $router );
		Logger::setLogger( new Logger_Web() );
		Auth::setController( new Auth_Controller_Web() );
	}

}