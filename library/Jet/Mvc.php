<?php
/**
 *
 *
 *
 * Mvc class provides simple access to mostly used Mvc system components
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 */
namespace Jet;

class Mvc {

	/**
	 * Initializes system and run dispatch.
	 *
	 * @see Mvc/readme.txt
	 *
	 * @static
	 *
	 * @param string|null $URL (optional; URL to dispatch; default: null = current URL)
	 * @param bool|null $cache_enabled (optional; default: null = by configuration)
	 *
	 * @throws Mvc_Router_Exception
	 *
	 * @return null|string
	 */
	public static function run( $URL=null, $cache_enabled=null  ) {
		$router = Mvc_Router::getNewRouterInstance();
		if(!$URL) {
			$URL = Http_Request::getURL();
		}

		$router->setRenderOnly( false );

		if(!$router->initialize($URL, $cache_enabled)) {
			throw new Mvc_Router_Exception('FATAL: Unable to resolve page and site... Probably there is no site or we have some data problem.');
		}

		if($router->getCacheLoaded()) {
			if( ($output=$router->getCacheOutput() )!==null ) {
				echo $output;
				return null;
			}
		}

		$router->handleRedirect();
		$router->handlePublicFile();

		$router->setupErrorHandler();

		if(
			!$router->getSite()->getIsActive()
		) {
			$router->getFrontController()->handleDeactivatedSite();
		}

		if(
			!$router->getSite()->getLocalizedData($router->getLocale())->getIsActive()
		) {
			$router->getFrontController()->handleDeactivatedLocale();
		}


		$router->handle404();



		Auth::initialize( $router );
		$router->setupLayout();

		$router->getFrontController()->checkPermissionsToViewThePage();

		$dispatcher = Mvc_Dispatcher::getNewDispatcherInstance();
		$dispatcher->initialize($router);

		$output = $dispatcher->dispatch();

		echo $output;
		$router->cacheSave();


		Auth::shutdown();
		Mvc_Dispatcher::dropCurrentDispatcherInstance();
		Mvc_Router::dropCurrentRouterInstance();

	}

	/**
	 * Initializes system and run dispatch.
	 *
	 * @see Mvc/readme.txt
	 *
	 * @static
	 *
	 * @param string $URL
	 *
	 * @throws Mvc_Router_Exception
	 *
	 * @return null|string
	 */
	public static function render( $URL ) {
		$router = Mvc_Router::getNewRouterInstance();

		$router->setRenderOnly( true );

		if(!$router->initialize($URL, false)) {
			return false;
		}


		if(
			!$router->getSite()->getIsActive()
		) {
			$router->getFrontController()->handleDeactivatedSite();
		}

		if(
			!$router->getSite()->getLocalizedData($router->getLocale())->getIsActive()
		) {
			$router->getFrontController()->handleDeactivatedLocale();
		}

		if(
			$router->getIs404() ||
			$router->getIsRedirect() ||
			$router->getIsPublicFile()
		) {
			return false;
		}


		Auth::initialize( $router );
		$router->setupLayout();

		if(!$router->getFrontController()->checkPermissionsToViewThePage(true)) {
			return false;
		}

		$dispatcher = Mvc_Dispatcher::getNewDispatcherInstance();
		$dispatcher->initialize($router);
		$output = $dispatcher->dispatch();

		Auth::shutdown();
		Mvc_Dispatcher::dropCurrentDispatcherInstance();
		Mvc_Router::dropCurrentRouterInstance();

		return $output;
	}


	/**
	 * Sets current step provides dynamic content (disables cache for current output part)
	 *
	 * Equivalent of Mvc_Dispatcher::getCurrentDispatcherInstance()->setCurrentStepProvidesDynamicContent();
	 * @see Mvc_Dispatcher_Abstract::setCurrentStepProvidesDynamicContent()
	 *
	 */
	public static function setProvidesDynamicContent() {
		$dispatcher = Mvc_Dispatcher::getCurrentDispatcherInstance();
		if($dispatcher) {
			$dispatcher->setCurrentStepProvidesDynamicContent();
		}
	}

	/**
	 * Truncate cache.
	 *
	 * Alias of: Mvc_Factory::getRouterInstance()->cacheTruncate($URL);
	 *
	 * URL can be:
	 *
	 * null - total cache truncate
	 * string - delete record for specified URL
	 * array - delete records for specified URLs
	 *
	 * @param null|string|array $URL
	 */
	public static function truncateRouterCache( $URL=null ) {
		Mvc_Factory::getRouterInstance()->cacheTruncate($URL);
	}

	/**
	 *
	 * Alias of:Mvc_Router::getCurrentRouterInstance()->generateMap();
	 *
	 */
	public static function generateRouterMap() {
		$router = Mvc_Router::getCurrentRouterInstance();

		$router->generateMap();
		$router->cacheTruncate();
	}

	/**
	 * Returns current locale
	 *
	 * Equivalent of  Mvc_Router::getCurrentRouterInstance()->getPage()->getLocale()
	 *
	 * @see Mvc_Pages_Page_Abstract::getLocale()
	 *
	 * @return Locale|string
	 */
	public static function getCurrentLocale() {
		return Mvc_Router::getCurrentRouterInstance()->getLocale();
	}


	/**
	 * Equivalent of Mvc_Router::getCurrentRouterInstance()->getPage()->getURI()
	 *
	 * @return string
	 */
	public static function getCurrentURI() {
		return Mvc_Router::getCurrentRouterInstance()->getPage()->getURI();
	}


	/**
	 * Returns current locales list
	 *
	 * Equivalent of  Mvc_Router::getCurrentRouterInstance()->getSite()->getLocales($get_as_string)
	 *
	 * @see Mvc_Sites_Site_Abstract::getLocales()
	 *
	 * @param bool $get_as_string (optional; if TRUE, string value of locale is returned; default: false)
	 *
	 * @return Locale[]|string[]
	 */
	public static function getCurrentLocalesList($get_as_string = false) {
		return Mvc_Router::getCurrentRouterInstance()->getSite()->getLocales($get_as_string);
	}	
	/**
	 * Returns current site ID
	 *
	 * Equivalent of Mvc_Router::getCurrentRouterInstance()->getSiteID()
	 *
	 * @see DataModel_ID
	 * @see Mvc_Sites
	 * @see Mvc_Router_Abstract::getSiteID()
	 *
	 * @return Mvc_Sites_Site_ID_Abstract
	 */
	public static function getCurrentSiteID() {
		return Mvc_Router::getCurrentRouterInstance()->getSiteID();
	}

	/**
	 * Returns current site data
	 *
	 * Equivalent of Mvc_Router::getCurrentRouterInstance()->getSite()
	 *
	 * @see Mvc_Sites
	 * @see Mvc_Router_Abstract::getSite()
	 *
	 * @return Mvc_Sites_Site_Abstract
	 */
	public static function getCurrentSite() {
		return Mvc_Router::getCurrentRouterInstance()->getSite();
	}

	/**
	 * Returns current page ID
	 *
	 * Equivalent of Mvc_Router::getCurrentRouterInstance()->getPageID()
	 *
	 * @see DataModel_ID
	 * @see Mvc_Pages
	 * @see Mvc_Router_Abstract::getPageID()
	 *
	 * @return Mvc_Pages_Page_ID_Abstract
	 */
	public static function getCurrentPageID() {
		return Mvc_Router::getCurrentRouterInstance()->getPageID();
	}

	/**
	 * Returns current page dat
	 *
	 * Equivalent of Mvc_Router::getCurrentRouterInstance()->getPage()
	 *
	 * @see Mvc_Pages
	 * @see Mvc_Router_Abstract::getPage()
	 *
	 * @return Mvc_Pages_Page_Abstract
	 */
	public static function getCurrentPage() {
		return Mvc_Router::getCurrentRouterInstance()->getPage();
	}


	/**
	 * Returns true if request is admin
	 *
	 * Equivalent of Mvc_Router::getCurrentRouterInstance()->getIsAdminUI()
	 *
	 * @see Mvc_Router_Abstract::getIsAdminUI()
	 *
	 * @return bool
	 */
	public static function getIsAdminUIRequest() {
		return Mvc_Router::getCurrentRouterInstance()->getIsAdminUI();
	}


	/**
	 * Returns true if request is SSL
	 *
	 * Equivalent of Mvc_Router::getCurrentRouterInstance()->getIsSSLRequest()
	 *
	 * @see Mvc_Router_Abstract::getIsSSLRequest()
	 *
	 * @return bool
	 */
	public static function getIsSSLRequest() {
		return Mvc_Router::getCurrentRouterInstance()->getIsSSLRequest();
	}

	/**
	 * Returns current service type
	 *
	 * Equivalent of Mvc_Router::getCurrentRouterInstance()->getServiceType()
	 *
	 * @see Mvc/readme.txt
	 * @see Mvc_Router
	 * @see Mvc_Router_Abstract::getServiceType()
	 *
	 * @return string
	 */
	public static function getCurrentServiceType() {
		return Mvc_Router::getCurrentRouterInstance()->getServiceType();
	}

	/**
	 * Equivalent of Mvc_Router::getCurrentRouterInstance()->getFrontController()->getLayout()->requireJavascriptLib( $javascript )
	 *
	 * @see JavaScript
	 * @see Mvc_Layout::requireJavascriptLib()
	 *
	 * @param string $javascript
	 *
	 * @return Javascript_Lib_Abstract
	 */
	public static function requireJavascriptLib( $javascript ) {
		return Mvc_Router::getCurrentRouterInstance()->getLayout()->requireJavascriptLib( $javascript );
	}

	/**
	 * Equivalent of Mvc_Router::getCurrentRouterInstance()->getFrontController()->getLayout()->requireJavascriptFile( $URI )
	 *
	 * @see JavaScript
	 * @see Mvc_Layout::requireJavascriptFile()
	 *
	 * @param string $URI
	 *
	 */
	public static function requireJavascriptFile( $URI ) {
		Mvc_Router::getCurrentRouterInstance()->getLayout()->requireJavascriptFile( $URI );
	}

	/**
	 *
	 * @see JavaScript
	 * @see Mvc_Layout::requireJavascriptFile()
	 *
	 * @param string $file
	 *
	 */
	public static function requireSiteJavascriptFile( $file ) {
		$router = Mvc_Router::getCurrentRouterInstance();
		$path = $router->getSiteScriptsPath();

		$router->getLayout()->requireJavascriptFile( $path.$file );
	}

	/**
	 * Equivalent of Mvc_Router::getCurrentRouterInstance()->getFrontController()->getLayout()->requireInitialJavascriptCode( $code )
	 *
	 * @see JavaScript
	 * @see Mvc_Layout::requireInitialJavascriptCode()
	 *
	 * @param string $code
	 *
	 */
	public static function requireInitialJavascriptCode( $code ) {
		Mvc_Router::getCurrentRouterInstance()->getLayout()->requireInitialJavascriptCode( $code );
	}

	/**
	 * Equivalent of Mvc_Router::getCurrentRouterInstance()->getFrontController()->getLayout()->requireJavascriptCode( $code )
	 *
	 * @see JavaScript
	 * @see Mvc_Layout::requireJavascriptCode()
	 *
	 * @param string $code
	 *
	 */
	public static function requireJavascriptCode( $code ) {
		Mvc_Router::getCurrentRouterInstance()->getLayout()->requireJavascriptCode( $code );
	}


	/**
	 * Equivalent of Mvc_Router::getCurrentRouterInstance()->getFrontController()->getLayout()->requireCssFile( $URI )
	 *
	 * @see JavaScript
	 * @see Mvc_Layout::requireCssFile()
	 *
	 * @param string $URI
	 * @param string $media (optional)
	 */
	public static function requireCssFile( $URI, $media='' ) {
		Mvc_Router::getCurrentRouterInstance()->getLayout()->requireCssFile( $URI, $media );
	}

	/**
	 *
	 * @see JavaScript
	 * @see Mvc_Layout::requireCssFile()
	 *
	 * @param string $file
	 * @param string $media (optional)
	 */
	public static function requireSiteCssFile( $file, $media='' ) {
		$router = Mvc_Router::getCurrentRouterInstance();
		$path = $router->getSiteStylesPath();

		$router->getLayout()->requireCssFile( $path.$file, $media );
	}

	/**
	 * Returns current UI module instance
	 *
	 * Equivalent of Mvc_Router::getCurrentRouterInstance()->getFrontController()
	 *
	 * @return Mvc_FrontControllerModule_Abstract
	 */
	public static function getCurrentFrontController() {
		return Mvc_Router::getCurrentRouterInstance()->getFrontController();
	}

	/**
	 * Show 404 page
	 *
	 * Equivalent of  Mvc_Router::getCurrentRouterInstance()->getFrontController()->handle404()
	 *
	 */
	public static function handle404() {
		Mvc_Router::getCurrentRouterInstance()->getFrontController()->handle404();
	}

	/**
	 * Returns a list of all locales for all sites
	 *
	 * @param bool $get_as_string (optional; if TRUE, string values of locales are returned; default: false)
	 *
	 * @return Locale[]|string[]
	 */
	public static function getAllSitesLocalesList($get_as_string = true) {
		$sites = Mvc_Sites::getAllSitesList();
		$locales = array();

		if($get_as_string) {

			foreach( $sites as $site ) {
				/**
				 * @var Mvc_Sites_Site_Abstract $site
				 */
				foreach( $site->getLocales(false) as $locale ) {
					$locales[(string)$locale] = $locale->getName();
				}
			}

			asort($locales);

		} else {
			foreach( $sites as $site ) {
				/**
				 * @var Mvc_Sites_Site_Abstract $site
				 */
				foreach( $site->getLocales(false) as $locale ) {
					$locales[(string)$locale] = $locale;
				}
			}
		}

		return $locales;
	}

	/**
	 * @param string $page_ID
	 * @param null|string $locale (optional, default: current)
	 * @param null|string $site_ID (optional, default: current)
	 *
	 * @return Mvc_Pages_Page_Abstract
	 */
	public static function getPage( $page_ID, $locale=null, $site_ID=null ) {
		if(!$locale) {
			$locale = static::getCurrentLocale();
		}
		if(!$site_ID) {
			$site_ID = static::getCurrentSiteID();
		}

		$page_class_name = Mvc_Factory::getPageClassName();

		/**
		 * @var Mvc_Pages_Page_Abstract $page_class_name
		 */
		$page_ID_instance = $page_class_name::createID(
			$site_ID,
			$locale,
			$page_ID
		);

		return Mvc_Pages::getPage( $page_ID_instance );
	}

	/**
	 * Equivalent of Mvc_Router::getCurrentRouterInstance()->getPathFragments()
	 *
	 * @return array
	 */
	public static function getPathFragments() {
		return Mvc_Router::getCurrentRouterInstance()->getPathFragments();
	}

	/**
	 * Equivalent of Mvc_Router::getCurrentRouterInstance()->putUsedPathFragment()
	 *
	 *
	 * @param string $used_path_fragment
	 *
	 * @return array
	 */
	public static function putUsedPathFragment( $used_path_fragment ) {
		return Mvc_Router::getCurrentRouterInstance()->putUsedPathFragment( $used_path_fragment );
	}

	/**
	 * Equivalent of Mvc_Router::getCurrentRouterInstance()->parsePathFragmentIntValue()
	 *
	 * @param string $template  (example: 'page:%VAL%' )
	 * @param mixed $default_value
	 * @param int $fragment_index (optional, default: 0)
	 *
	 * @return int
	 */
	public static function parsePathFragmentIntValue( $template, $default_value=null, $fragment_index=0 ) {
		return Mvc_Router::getCurrentRouterInstance()->parsePathFragmentIntValue( $template, $default_value, $fragment_index );
	}

	/**
	 * Equivalent of Mvc_Router::getCurrentRouterInstance()->parsePathFragmentValue()
	 *
	 * @param string $template
	 * @param string $fragment_index
	 * @param string $reg_exp_part
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public static function parsePathFragmentValue( $template, $fragment_index, $reg_exp_part ) {
		return Mvc_Router::getCurrentRouterInstance()->parsePathFragmentValue( $template, $fragment_index, $reg_exp_part );
	}

}