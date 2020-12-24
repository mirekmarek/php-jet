<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Application_Logger;

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
	public static function getSiteId() : string
	{
		return 'web';
	}

	/**
	 * @return Mvc_Site
	 */
	public static function getSite() : Mvc_Site
	{
		return Mvc_Site::get( static::getSiteId() );
	}

	/**
	 * @param Mvc_Router $router
	 */
	public static function init( Mvc_Router $router ) : void
	{
		Application::initErrorPages( $router );
		Application_Logger::setLogger( new Application_Logger_Web() );
		Auth::setController( new Auth_Controller_Web() );
	}

}