<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Mvc
 * @package Jet
 */
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
	 * @var Mvc_Page_Content_Interface
	 */
	protected static $current_content;

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
	 * @return Mvc_Page_Interface
	 */
	public static function getCurrentPage()
	{
		return static::$current_page;
	}

	/**
	 * @param Mvc_Page_Content_Interface $current_content
	 */
	public static function setCurrentContent(Mvc_Page_Content_Interface $current_content)
	{
		static::$current_content = $current_content;
	}

	/**
	 *
	 */
	public static function unsetCurrentContent()
	{
		static::$current_content = null;

	}

	/**
	 * @return Mvc_Page_Content_Interface
	 */
	public static function getCurrentContent() {
		return static::$current_content;
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
	 * Equivalent of Mvc_Layout::getCurrentLayout()->requireJavascriptFile( $URI )
	 *
	 * @see Mvc_Layout::requireJavascriptFile()
	 *
	 * @param string $URI
	 *
	 */
	public static function requireJavascriptFile( $URI ) {
		Mvc_Layout::getCurrentLayout()->requireJavascriptFile( $URI );
	}

	/**
	 * Equivalent of Mvc_Layout::getCurrentLayout()->requireInitialJavascriptCode( $code )
	 *
	 * @see Mvc_Layout::requireInitialJavascriptCode()
	 *
	 * @param string $code
	 *
	 */
	public static function requireInitialJavascriptCode( $code ) {
		Mvc_Layout::getCurrentLayout()->requireInitialJavascriptCode( $code );
	}

	/**
	 * Equivalent of Mvc_Layout::getCurrentLayout()->requireJavascriptCode( $code )
	 *
	 * @param string $code
	 *
	 */
	public static function requireJavascriptCode( $code ) {
		Mvc_Layout::getCurrentLayout()->requireJavascriptCode( $code );
	}


	/**
	 * Equivalent of Mvc_Layout::getCurrentLayout()->requireCssFile( $URI )
	 *
	 * @see Mvc_Layout::requireCssFile()
	 *
	 * @param string $URI
	 * @param string $media (optional)
	 */
	public static function requireCssFile( $URI, $media='' ) {
		Mvc_Layout::getCurrentLayout()->requireCssFile( $URI, $media );
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