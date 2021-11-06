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
	const HOMEPAGE_ID = '_homepage_';

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
	 * @param string|null $base_id
	 *
	 * @return Mvc_Base_Interface|null
	 */
	public static function getBase( ?string $base_id=null ): Mvc_Base_Interface|null
	{
		if(!$base_id) {
			return static::getRouter()->getBase();
		}

		/**
		 * @var Mvc_Base_Interface $base_class_name
		 */
		$base_class_name = Factory_Mvc::getBaseClassName();

		return $base_class_name::_get( $base_id );
	}

	/**
	 * @return Mvc_Base_Interface[]
	 */
	public static function getBases(): array
	{
		/**
		 * @var Mvc_Base_Interface $base_class_name
		 */
		$base_class_name = Factory_Mvc::getBaseClassName();

		return $base_class_name::_getBases();
	}

	/**
	 *
	 * @return Mvc_Base_Interface|null
	 */
	public static function getDefaultBase(): Mvc_Base_Interface|null
	{
		$bases = static::getBases();

		foreach( $bases as $base ) {
			if( $base->getIsDefault() ) {
				return $base;
			}
		}

		return null;
	}



	/**
	 *
	 * @return Locale|null
	 */
	public static function getLocale(): Locale|null
	{
		return static::getRouter()->getLocale();
	}

	/**
	 * @param string|null $page_id (optional, null = current)
	 * @param Locale|null $locale (optional, null = current)
	 * @param string|null $base_id (optional, null = current)
	 *
	 * @return Mvc_Page_Interface|null
	 */
	public static function getPage( string|null $page_id=null, Locale|null $locale = null, string|null $base_id = null ): Mvc_Page_Interface|null
	{
		if(!$page_id && !$locale && !$base_id) {
			return static::getRouter()->getPage();
		}

		if( !$page_id ) {
			if( !Mvc::getPage() ) {
				return null;
			}
			$page_id = Mvc::getPage()->getId();
		}

		if( !$locale ) {
			$locale = Mvc::getLocale();
			if( !$locale ) {
				return null;
			}
		}

		if( !$base_id ) {
			if( !Mvc::getBase() ) {
				return null;
			}
			$base_id = Mvc::getBase()->getId();
		}

		/**
		 * @var Mvc_Page_Interface $page_class_name
		 */
		$page_class_name = Factory_Mvc::getPageClassName();

		return $page_class_name::_get( $page_id, $locale, $base_id );
	}

	/**
	 * @param Locale|null $locale (optional, null = current)
	 * @param string|null $base_id (optional, null = current)
	 *
	 * @return Mvc_Page_Interface|null
	 */
	public static function getHomePage( Locale|null $locale = null, string|null $base_id = null ): Mvc_Page_Interface|null
	{
		return static::getPage(static::HOMEPAGE_ID, $locale, $base_id);
	}


	/**
	 *
	 * @param string $base_id
	 * @param Locale $locale
	 *
	 * @return Mvc_Page_Interface[]
	 */
	public static function getPages( string $base_id, Locale $locale ): array
	{
		$base_class_name = Factory_Mvc::getBaseClassName();

		/**
		 * @var Mvc_Base_Interface $base_class_name
		 */
		$base = $base_class_name::_get( $base_id );

		$homepage = $base->getHomepage( $locale );

		$result = [];

		$getPages = function( Mvc_Page_Interface $parent ) use (&$getPages, &$result) {
			$result[] = $parent;

			foreach( $parent->getChildren() as $child ) {
				$getPages( $child );
			}
		};

		$getPages($homepage);

		return $result;
	}

}