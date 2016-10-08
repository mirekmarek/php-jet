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
	 *
	 * @var Mvc_Router_Abstract
	 */
	protected static $current_router;


	/**
	 * @var Mvc_Site_Interface
	 */
	protected static $current_site;

	/**
	 * @var Locale
	 */
	protected static $current_locale;

	/**
	 * @var Mvc_Page_Interface
	 */
	protected static $current_page;

	/**
	 * @param Mvc_Router_Abstract $current_router
	 */
	public static function setCurrentRouter( Mvc_Router_Abstract $current_router)
	{
		self::$current_router = $current_router;
	}

	/**
	 * @return Mvc_Router_Abstract
	 */
	public static function getCurrentRouter()
	{
		if(!self::$current_router) {
			self::$current_router = Mvc_Factory::getRouterInstance();
		}

		return self::$current_router;
	}



	/**
	 * @param Mvc_Site_Interface $current_site
	 */
	public static function setCurrentSite( Mvc_Site_Interface $current_site)
	{
		self::$current_site = $current_site;
	}

	/**
	 *
	 * @return Mvc_Site_Interface
	 */
	public static function getCurrentSite()
	{
		return static::$current_site;
	}


	/**
	 * @param Locale $current_locale
	 */
	public static function setCurrentLocale( Locale $current_locale)
	{
		Translator::setCurrentLocale( $current_locale );
		Locale::setCurrentLocale( $current_locale );

		self::$current_locale = $current_locale;
	}

	/**
	 *
	 * @return Locale
	 */
	public static function getCurrentLocale()
	{
		return static::$current_locale;
	}

	/**
	 * @param Mvc_Page_Interface $current_page
	 */
	public static function setCurrentPage( Mvc_Page_Interface $current_page )
	{
		self::$current_page = $current_page;
	}

	/**
	 *
	 */
	public static function unsetCurrentPage()
	{
		self::$current_page = null;
	}

	/**
	 *
	 *
	 * @return Mvc_Page_Interface
	 */
	public static function getCurrentPage()
	{
		return static::$current_page;
	}


	/**
	 * @throws Mvc_Controller_Exception
	 */
	public static function checkCurrentContentIsDynamic() {
		$page=static::getCurrentPage();

		if( $page ) {
			$content = $page->getCurrentContent();

			if($content) {
				if(!$content->getIsDynamic()) {
					throw new Mvc_Controller_Exception('Content '.$content->getContentKey().' (module:'.$content->getModuleName().', controller action:'.$content->getControllerAction().')  must be marked as dynamic');
				}
			}
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
	 * @return string
	 */
	public static function getCurrentPageURI() {
		return static::getCurrentPage()->getURI();
	}


	/**
	 * Returns current locales list
	 *
	 *
	 * @param bool $get_as_string (optional; if TRUE, string value of locale is returned; default: false)
	 *
	 * @return Locale[]|string[]
	 */
	public static function getCurrentLocalesList($get_as_string = false) {
		return static::getCurrentSite()->getLocales($get_as_string);
	}


	/**
	 * Returns true if request is admin
	 *
	 *
	 * @return bool
	 */
	public static function getIsAdminUIRequest() {
		if(!static::getCurrentPage()) {
			return false;
		}

		return static::getCurrentPage()->getIsAdminUI();
	}


	/**
	 * Returns true if request is SSL
	 *
	 * Equivalent of Mvc::getCurrentRouter()->getIsSSLRequest()
	 *
	 * @return bool
	 */
	public static function getIsSSLRequest() {
		return static::getCurrentRouter()->getIsSSLRequest();
	}

	/**
	 * Equivalent of Mvc::getCurrentPage()->getLayout()->requireJavascriptFile( $URI )
	 *
	 * @see Mvc_Layout::requireJavascriptFile()
	 *
	 * @param string $URI
	 *
	 */
	public static function requireJavascriptFile( $URI ) {
		static::getCurrentPage()->getLayout()->requireJavascriptFile( $URI );
	}

	/**
	 *
	 * @see Mvc_Layout::requireJavascriptFile()
	 *
	 * @param string $file
	 *
	 */
	public static function requireSiteJavascriptFile( $file ) {
		$path = Mvc::getCurrentSite()->getPublicPath();

		static::getCurrentPage()->getLayout()->requireJavascriptFile( $path.$file );
	}

	/**
	 * Equivalent of Mvc::getCurrentPage()->getLayout()->requireInitialJavascriptCode( $code )
	 *
	 * @see Mvc_Layout::requireInitialJavascriptCode()
	 *
	 * @param string $code
	 *
	 */
	public static function requireInitialJavascriptCode( $code ) {
		static::getCurrentPage()->getLayout()->requireInitialJavascriptCode( $code );
	}

	/**
	 * Equivalent of Mvc::getCurrentPage()->getLayout()->requireJavascriptCode( $code )
	 *
	 * @see Mvc_Layout::requireJavascriptCode()
	 *
	 * @param string $code
	 *
	 */
	public static function requireJavascriptCode( $code ) {
		static::getCurrentPage()->getLayout()->requireJavascriptCode( $code );
	}


	/**
	 * Equivalent of Mvc::getCurrentPage()->getLayout()->requireCssFile( $URI )
	 *
	 * @see Mvc_Layout::requireCssFile()
	 *
	 * @param string $URI
	 * @param string $media (optional)
	 */
	public static function requireCssFile( $URI, $media='' ) {
		static::getCurrentPage()->getLayout()->requireCssFile( $URI, $media );
	}

	/**
	 *
	 * @see Mvc_Layout::requireCssFile()
	 *
	 * @param string $file
	 * @param string $media (optional)
	 */
	public static function requireSiteCssFile( $file, $media='' ) {
		$path = Mvc::getCurrentSite()->getPublicPath();

		static::getCurrentPage()->getLayout()->requireCssFile( $path.$file, $media );
	}

	/**
	 * Show 404 page
	 *
	 * Equivalent of  static::getCurrentSite()->handle404()
	 *
	 */
	public static function handle404() {
		static::getCurrentSite()->handle404();
	}

}