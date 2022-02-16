<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class MVC
{
	const HOMEPAGE_ID = '_homepage_';

	const MAIN_CONTROLLER_NAME = 'Main';

	/**
	 *
	 * @var ?MVC_Router_Interface
	 */
	protected static ?MVC_Router_Interface $router = null;


	/**
	 * @return MVC_Router_Interface
	 */
	public static function getRouter(): MVC_Router_Interface
	{
		if( !static::$router ) {
			static::$router = Factory_MVC::getRouterInstance();
		}

		return static::$router;
	}

	/**
	 * @param MVC_Router_Interface $router
	 */
	public static function setRouter( MVC_Router_Interface $router ): void
	{
		static::$router = $router;
	}

	/**
	 * @param string|null $base_id
	 *
	 * @return MVC_Base_Interface|null
	 */
	public static function getBase( ?string $base_id=null ): MVC_Base_Interface|null
	{
		if(!$base_id) {
			return static::getRouter()->getBase();
		}

		/**
		 * @var MVC_Base_Interface $base_class_name
		 */
		$base_class_name = Factory_MVC::getBaseClassName();

		return $base_class_name::_get( $base_id );
	}

	/**
	 * @return MVC_Base_Interface[]
	 */
	public static function getBases(): array
	{
		/**
		 * @var MVC_Base_Interface $base_class_name
		 */
		$base_class_name = Factory_MVC::getBaseClassName();

		return $base_class_name::_getBases();
	}

	/**
	 *
	 * @return MVC_Base_Interface|null
	 */
	public static function getDefaultBase(): MVC_Base_Interface|null
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
	 * @return MVC_Page_Interface|null
	 */
	public static function getPage( string|null $page_id=null, Locale|null $locale = null, string|null $base_id = null ): MVC_Page_Interface|null
	{
		if(!$page_id && !$locale && !$base_id) {
			return static::getRouter()->getPage();
		}

		if( !$page_id ) {
			if( !MVC::getPage() ) {
				return null;
			}
			$page_id = MVC::getPage()->getId();
		}

		if( !$locale ) {
			$locale = MVC::getLocale();
			if( !$locale ) {
				return null;
			}
		}

		if( !$base_id ) {
			if( !MVC::getBase() ) {
				return null;
			}
			$base_id = MVC::getBase()->getId();
		}

		/**
		 * @var MVC_Page_Interface $page_class_name
		 */
		$page_class_name = Factory_MVC::getPageClassName();

		return $page_class_name::_get( $page_id, $locale, $base_id );
	}

	/**
	 * @param Locale|null $locale (optional, null = current)
	 * @param string|null $base_id (optional, null = current)
	 *
	 * @return MVC_Page_Interface|null
	 */
	public static function getHomePage( Locale|null $locale = null, string|null $base_id = null ): MVC_Page_Interface|null
	{
		return static::getPage(static::HOMEPAGE_ID, $locale, $base_id);
	}


	/**
	 *
	 * @param string $base_id
	 * @param Locale $locale
	 *
	 * @return MVC_Page_Interface[]
	 */
	public static function getPages( string $base_id, Locale $locale ): array
	{
		$base_class_name = Factory_MVC::getBaseClassName();

		/**
		 * @var MVC_Base_Interface $base_class_name
		 */
		$base = $base_class_name::_get( $base_id );

		$homepage = $base->getHomepage( $locale );

		$result = [];

		$getPages = function( MVC_Page_Interface $parent ) use (&$getPages, &$result) {
			$result[] = $parent;

			foreach( $parent->getChildren() as $child ) {
				$getPages( $child );
			}
		};

		$getPages($homepage);

		return $result;
	}

}