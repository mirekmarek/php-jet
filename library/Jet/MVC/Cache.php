<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

require_once 'Cache/Backend.php';

/**
 *
 */
class MVC_Cache
{
	/**
	 * @var MVC_Cache_Backend|null
	 */
	protected static ?MVC_Cache_Backend $backend = null;

	/**
	 * @param MVC_Cache_Backend $backend
	 */
	public static function init( MVC_Cache_Backend $backend ): void
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
		static::$backend?->reset();
	}

	/**
	 * @return array|null
	 */
	public static function loadBaseMaps(): array|null
	{
		return static::$backend?->loadBaseMaps();
		
	}

	/**
	 * @param array $map
	 */
	public static function saveBaseMaps( array $map ): void
	{
		static::$backend?->saveBaseMaps( $map );
	}

	/**
	 * @param MVC_Base_Interface $base
	 * @param Locale $locale
	 *
	 * @return array|null
	 */
	public static function loadPageMaps( MVC_Base_Interface $base, Locale $locale ): array|null
	{
		return static::$backend?->loadPageMaps( $base, $locale );
		
	}

	/**
	 * @param MVC_Base_Interface $base
	 * @param Locale $locale
	 *
	 * @param array $map
	 */
	public static function savePageMaps( MVC_Base_Interface $base, Locale $locale, array $map ): void
	{
		static::$backend?->savePageMaps( $base, $locale, $map );
	}

	/**
	 * @param MVC_Page_Content_Interface $content
	 *
	 * @return string|null
	 */
	public static function loadContentOutput( MVC_Page_Content_Interface $content ): string|null
	{
		return static::$backend?->loadContentOutput( $content );
		
	}

	/**
	 * @param MVC_Page_Content_Interface $content
	 * @param string $output
	 *
	 */
	public static function saveContentOutput( MVC_Page_Content_Interface $content, string $output ): void
	{
		static::$backend?->saveContentOutput( $content, $output );
	}


}