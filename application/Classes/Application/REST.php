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

		foreach( Application_Modules::activatedModulesList() as $manifest ) {
			/**
			 * @var Application_Module_Manifest $manifest
			 */
			if($manifest->hasRestAPI()) {
				static::addRestHook( $router->getLocale(), $manifest );
			}
		}

	}

	/**
	 * @param Locale                      $locale
	 * @param Application_Module_Manifest $module_manifest
	 *
	 */
	protected static function addRestHook( Locale $locale, Application_Module_Manifest $module_manifest )
	{

		/**
		 * @var Mvc_Page $parent_page
		 */
		$parent_page = Application_REST::getSite()->getHomepage( $locale );

		$content = Mvc_Factory::getPageContentInstance();
		$content->setModuleName( $module_manifest->getName() );
		$content->setControllerAction( false );

		$parent_page->addContent( $content );


	}



}