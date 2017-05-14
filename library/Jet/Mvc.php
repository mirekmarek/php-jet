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
class Mvc
{

	/**
	 *
	 * @var Mvc_Router_Interface
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
	 * @return Mvc_Router_Interface
	 */
	public static function getCurrentRouter()
	{
		if( !static::$current_router ) {
			static::$current_router = Mvc_Factory::getRouterInstance();
		}

		return static::$current_router;
	}

	/**
	 * @param Mvc_Router_Interface $current_router
	 */
	public static function setCurrentRouter( Mvc_Router_Interface $current_router )
	{
		static::$current_router = $current_router;
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
	 * @param Mvc_Site_Interface $current_site
	 */
	public static function setCurrentSite( Mvc_Site_Interface $current_site )
	{
		static::$current_site = $current_site;
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
	 * @param Locale $current_locale
	 */
	public static function setCurrentLocale( Locale $current_locale )
	{
		Translator::setCurrentLocale( $current_locale );
		Locale::setCurrentLocale( $current_locale );

		static::$current_locale = $current_locale;
	}

	/**
	 *
	 */
	public static function unsetCurrentPage()
	{
		static::$current_page = null;
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
	 * @param Mvc_Page_Interface $current_page
	 */
	public static function setCurrentPage( Mvc_Page_Interface $current_page )
	{
		static::$current_page = $current_page;
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
	public static function getCurrentContent()
	{
		return static::$current_content;
	}

	/**
	 * @param Mvc_Page_Content_Interface $current_content
	 */
	public static function setCurrentContent( Mvc_Page_Content_Interface $current_content )
	{
		static::$current_content = $current_content;
	}

}