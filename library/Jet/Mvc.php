<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Mvc
{
	/**
	 *
	 * @var ?Mvc_Router_Interface
	 */
	protected static ?Mvc_Router_Interface $router = null;

	/**
	 * @var ?Mvc_Base_Interface
	 */
	protected static ?Mvc_Base_Interface $current_base = null;

	/**
	 * @var ?Locale
	 */
	protected static ?Locale $current_locale = null;

	/**
	 * @var ?Mvc_Page_Interface
	 */
	protected static ?Mvc_Page_Interface $current_page = null;


	/**
	 * @return Mvc_Router_Interface
	 */
	public static function getRouter(): Mvc_Router_Interface
	{
		if( !static::$router ) {
			static::$router = Mvc_Factory::getRouterInstance();
		}

		return static::$router;
	}

	/**
	 * @param Mvc_Router_Interface $router
	 */
	public static function setRouter( Mvc_Router_Interface $router ): void
	{
		static::$router = $router;
	}

	/**
	 *
	 * @return Mvc_Base_Interface|null
	 */
	public static function getCurrentBase(): Mvc_Base_Interface|null
	{
		return static::$current_base;
	}

	/**
	 * @param Mvc_Base_Interface $current_base
	 */
	public static function setCurrentBase( Mvc_Base_Interface $current_base ): void
	{
		static::$current_base = $current_base;
	}

	/**
	 *
	 * @return Locale|null
	 */
	public static function getCurrentLocale(): Locale|null
	{
		return static::$current_locale;
	}

	/**
	 * @param Locale $current_locale
	 * @param bool $set_system_locale (optional, default: true)
	 * @param bool $set_translator_locale (optional, default: true)
	 */
	public static function setCurrentLocale( Locale $current_locale,
	                                         bool $set_system_locale = true,
	                                         bool $set_translator_locale = true ): void
	{
		if( $set_system_locale ) {
			Locale::setCurrentLocale( $current_locale );
		}

		if( $set_translator_locale ) {
			Translator::setCurrentLocale( $current_locale );
		}

		static::$current_locale = $current_locale;
	}

	/**
	 *
	 * @return Mvc_Page_Interface|null
	 */
	public static function getCurrentPage(): Mvc_Page_Interface|null
	{
		return static::$current_page;
	}

	/**
	 * @param Mvc_Page_Interface $current_page
	 */
	public static function setCurrentPage( Mvc_Page_Interface $current_page ): void
	{
		static::$current_page = $current_page;
	}


}