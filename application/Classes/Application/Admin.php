<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Application as Jet_Application;
use Jet\Application_Log;
use Jet\Application_Module_Manifest_AdminDialog;
use Jet\Application_Module_Manifest_AdminSection;
use Jet\Application_Modules;
use Jet\Application_Module_Manifest;

use Jet\Mvc_Factory;
use Jet\Mvc_Site;
use Jet\Mvc_Page;
use Jet\Mvc_View;
use Jet\Mvc_Router;
use Jet\Mvc_Page_Exception;
use Jet\Mvc_Layout;

use Jet\Locale;

use Jet\Tr;

use Jet\Auth;

/**
 *
 */
class Application_Admin
{


	/**
	 * @return string
	 */
	public static function getSiteId() {
		return 'admin';
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
		Application_Log::setLogger( new Application_Log_Logger_Admin() );
		Auth::setController( new Auth_Controller_Admin() );

		$site = $router->getSite();

		foreach( Application_Modules::activatedModulesList() as $manifest ) {
			/**
			 * @var Application_Module_Manifest $manifest
			 */

			foreach( $site->getLocales() as $locale ) {
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


	/**
	 * @param string $dialog_id
	 * @param array  $options
	 *
	 * @return null|string
	 */
	public static function requireDialog( $dialog_id, array $options=[] ) {

		$page = Mvc_Page::get('dialog-'.$dialog_id);

		if(
			!$page ||
			!$page->getContent()
		) {
			return null;
		}

		$content = $page->getContent()[0];

		$module = $content->getModuleInstance();

		if(!$module) {
			return null;
		}

		$view = new Mvc_View( $module->getViewsDir().'dialog-hooks/' );
		foreach( $options as $k=>$v ) {
			$view->setVar( $k, $v );
		}

		return $view->render( $dialog_id );

	}

}