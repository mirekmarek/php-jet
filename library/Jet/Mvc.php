<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	protected static bool $force_slash_on_URL_end = false;

	/**
	 *
	 * @var ?Mvc_Router_Interface
	 */
	protected static ?Mvc_Router_Interface $router = null;

	/**
	 * @var ?Mvc_Site_Interface
	 */
	protected static ?Mvc_Site_Interface $current_site = null;

	/**
	 * @var ?Locale
	 */
	protected static ?Locale $current_locale = null;

	/**
	 * @var ?Mvc_Page_Interface
	 */
	protected static ?Mvc_Page_Interface $current_page = null;


	/**
	 * @return bool
	 */
	public static function getForceSlashOnURLEnd() : bool
	{
		return self::$force_slash_on_URL_end;
	}

	/**
	 * @param bool $force_slash_on_URL_end
	 */
	public static function setForceSlashOnURLEnd( bool $force_slash_on_URL_end ) : void
	{
		self::$force_slash_on_URL_end = $force_slash_on_URL_end;
	}

	/**
	 * @return Mvc_Router_Interface
	 */
	public static function getRouter() : Mvc_Router_Interface
	{
		if( !static::$router ) {
			static::$router = Mvc_Factory::getRouterInstance();
		}

		return static::$router;
	}

	/**
	 * @param Mvc_Router_Interface $router
	 */
	public static function setRouter( Mvc_Router_Interface $router ) : void
	{
		static::$router = $router;
	}

	/**
	 *
	 * @return Mvc_Site_Interface|null
	 */
	public static function getCurrentSite() : Mvc_Site_Interface|null
	{
		return static::$current_site;
	}

	/**
	 * @param Mvc_Site_Interface $current_site
	 */
	public static function setCurrentSite( Mvc_Site_Interface $current_site ) : void
	{
		static::$current_site = $current_site;
	}

	/**
	 *
	 * @return Locale|null
	 */
	public static function getCurrentLocale() : Locale|null
	{
		return static::$current_locale;
	}

	/**
	 * @param Locale $current_locale
	 * @param bool   $set_system_locale (optional, default: true)
	 * @param bool   $set_translator_locale (optional, default: true)
	 */
	public static function setCurrentLocale( Locale $current_locale,
	                                         bool $set_system_locale=true,
	                                         bool $set_translator_locale=true ) : void
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
	public static function unsetCurrentPage() : void
	{
		static::$current_page = null;
	}

	/**
	 *
	 * @return Mvc_Page_Interface|null
	 */
	public static function getCurrentPage() : Mvc_Page_Interface|null
	{
		return static::$current_page;
	}

	/**
	 * @param Mvc_Page_Interface $current_page
	 */
	public static function setCurrentPage( Mvc_Page_Interface $current_page ) : void
	{
		static::$current_page = $current_page;
	}


}