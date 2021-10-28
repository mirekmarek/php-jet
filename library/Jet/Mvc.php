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
	 * @return Mvc_Router_Interface
	 */
	public static function getRouter(): Mvc_Router_Interface
	{
		if( !static::$router ) {
			static::$router = Factory_Mvc::getRouterInstance();
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
	public static function base(): Mvc_Base_Interface|null
	{
		return static::getRouter()->getBase();
	}

	/**
	 *
	 * @return Locale|null
	 */
	public static function locale(): Locale|null
	{
		return static::getRouter()->getLocale();
	}

	/**
	 *
	 * @return Mvc_Page_Interface|null
	 */
	public static function page(): Mvc_Page_Interface|null
	{
		return static::getRouter()->getPage();
	}
	
}