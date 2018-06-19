<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Application_Log;
use Jet\Application_Modules;
use Jet\Application_Module_Manifest;

use Jet\Mvc_Factory;
use Jet\Mvc_Site;
use Jet\Mvc_Page;
use Jet\Mvc_Router;

use Jet\Locale;

use Jet\Auth;

/**
 *
 */
class Application_REST
{
	/**
	 * @return string
	 */
	public static function getSiteId() {
		return 'rest';
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
		Application_Log::setLogger( new Application_Log_Logger_REST() );
		Auth::setController( new Auth_Controller_REST() );

		$site = $router->getSite();
		$locale = $router->getLocale();

		foreach( Application_Modules::activatedModulesList() as $manifest ) {
			/**
			 * @var Application_Module_Manifest $manifest
			 */


			$pages = $manifest->getPages(
				$site,
				$locale
			);

			$parent_page = $router->getSite()->getHomepage( $locale );

			foreach( $pages as $page ) {
				$page->setParent( $parent_page );

				Mvc_Page::appendPage( $page );
			}

		}
	}

}