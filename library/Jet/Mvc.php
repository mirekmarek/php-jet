<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Mvc
{

	/**
	 * @var bool
	 */
	protected static $force_slash_on_URL_end = true;

	/**
	 *
	 * @var Mvc_Router_Interface
	 */
	protected static $router;

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
	 * @return boolean
	 */
	public static function getForceSlashOnURLEnd()
	{
		return self::$force_slash_on_URL_end;
	}

	/**
	 * @param boolean $force_slash_on_URL_end
	 */
	public static function setForceSlashOnURLEnd( $force_slash_on_URL_end )
	{
		self::$force_slash_on_URL_end = $force_slash_on_URL_end;
	}

	/**
	 * @return Mvc_Router_Interface
	 */
	public static function getRouter()
	{
		if( !static::$router ) {
			static::$router = Mvc_Factory::getRouterInstance();
		}

		return static::$router;
	}

	/**
	 * @param Mvc_Router_Interface $router
	 */
	public static function setRouter( Mvc_Router_Interface $router )
	{
		static::$router = $router;
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
	 * @param bool   $set_system_locale (optional, default: true)
	 * @param bool   $set_translator_locale (optional, default: true)
	 */
	public static function setCurrentLocale( Locale $current_locale, $set_system_locale=true, $set_translator_locale=true )
	{
		if($set_system_locale) {
			Locale::setCurrentLocale( $current_locale );
		}

		if($set_translator_locale) {
			Translator::setCurrentLocale( $current_locale );
		}

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


}