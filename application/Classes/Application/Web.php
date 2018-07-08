<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Application_Log;

use Jet\Mvc_Site;
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
	public static function getSiteId() {
		return 'web';
	}

	/**
	 * @return Mvc_Site
	 */
	public static function getSite() {
		return Mvc_Site::get( static::getSiteId() );
	}

	/**
	 * @param Mvc_Router $router
	 */
	public static function init( Mvc_Router $router )
	{
		Application::initErrorPages( $router );
		Application_Log::setLogger( new Application_Log_Logger_Web() );
		Auth::setController( new Auth_Controller_Web() );
	}

}