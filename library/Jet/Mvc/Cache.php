<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

require_once 'Cache/Backend.php';

/**
 *
 */
class Mvc_Cache
{
	/**
	 * @var Mvc_Cache_Backend|null
	 */
	protected static ?Mvc_Cache_Backend $backend = null;

	/**
	 * @param Mvc_Cache_Backend $backend
	 */
	public static function init( Mvc_Cache_Backend $backend ): void
	{
		static::$backend = $backend;
	}


	/**
	 * @return bool
	 */
	public static function isActive(): bool
	{
		if( !static::$backend ) {
			return false;
		}

		return static::$backend->isActive();
	}


	/**
	 *
	 */
	public static function reset(): void
	{
		if( static::$backend ) {
			static::$backend->reset();
		}
	}

	/**
	 * @return array|null
	 */
	public static function loadSiteMaps(): array|null
	{
		if( !static::$backend ) {
			return null;
		}

		return static::$backend->loadSiteMaps();
	}

	/**
	 * @param array $map
	 */
	public static function saveSiteMaps( array $map ): void
	{
		if( static::$backend ) {
			static::$backend->saveSiteMaps( $map );
		}
	}

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 *
	 * @return array|null
	 */
	public static function loadPageMaps( Mvc_Site_Interface $site, Locale $locale ): array|null
	{
		if( !static::$backend ) {
			return null;
		}

		return static::$backend->loadPageMaps( $site, $locale );
	}

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 *
	 * @param array $map
	 */
	public static function savePageMaps( Mvc_Site_Interface $site, Locale $locale, array $map ): void
	{
		if( static::$backend ) {
			static::$backend->savePageMaps( $site, $locale, $map );
		}
	}

	/**
	 * @param Mvc_Page_Content_Interface $content
	 *
	 * @return string|null
	 */
	public static function loadContentOutput( Mvc_Page_Content_Interface $content ): string|null
	{
		if( !static::$backend ) {
			return null;
		}

		return static::$backend->loadContentOutput( $content );
	}

	/**
	 * @param Mvc_Page_Content_Interface $content
	 * @param string $output
	 *
	 */
	public static function saveContentOutput( Mvc_Page_Content_Interface $content, string $output ): void
	{
		if( static::$backend ) {
			static::$backend->saveContentOutput( $content, $output );
		}
	}


}