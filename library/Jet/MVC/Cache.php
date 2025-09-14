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
	 *
	 */
	public static function resetOutputCache(): void
	{
		static::$backend?->resetOutputCache();
	}


	/**
	 * @return array<string,array<string,string|string[]>>|null
	 */
	public static function loadBaseMaps(): array|null
	{
		return static::$backend?->loadBaseMaps();
		
	}

	/**
	 * @param array<string,array<string,string|string[]>> $map
	 */
	public static function saveBaseMaps( array $map ): void
	{
		static::$backend?->saveBaseMaps( $map );
	}

	/**
	 * @param MVC_Base_Interface $base
	 * @param Locale $locale
	 *
	 * @return array<string,array<string,string|string[]>>|null
	 */
	public static function loadPageMaps( MVC_Base_Interface $base, Locale $locale ): array|null
	{
		return static::$backend?->loadPageMaps( $base, $locale );
		
	}

	/**
	 * @param MVC_Base_Interface $base
	 * @param Locale $locale
	 *
	 * @param array<string,array<string,string|string[]>> $map
	 */
	public static function savePageMaps( MVC_Base_Interface $base, Locale $locale, array $map ): void
	{
		static::$backend?->savePageMaps( $base, $locale, $map );
	}

	/**
	 * @param MVC_Page_Content_Interface $content
	 * @param ?int $ttl
	 *
	 * @return ?Cache_Record_HTMLSnippet
	 */
	public static function loadContentOutput( MVC_Page_Content_Interface $content, ?int $ttl=null ): ?Cache_Record_HTMLSnippet
	{
		$cache_rec =  static::$backend?->loadContentOutput( $content );
		
		if(
			$cache_rec &&
			$ttl>0
		) {
			$age = time() - $cache_rec->getTimestamp();
			
			if($age > $ttl) {
				$cache_rec = null;
			}
		}
		
		return $cache_rec;
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
	
	
	/**
	 * @param string $key
	 * @param ?int $ttl
	 *
	 * @return ?Cache_Record_HTMLSnippet
	 */
	public static function loadCustomOutput( string $key, ?int $ttl=null ): ?Cache_Record_HTMLSnippet
	{
		$cache_rec =  static::$backend?->loadCustomOutput( $key );
		
		if(
			$cache_rec &&
			$ttl>0
		) {
			$age = time() - $cache_rec->getTimestamp();
			
			if($age > $ttl) {
				$cache_rec = null;
			}
		}
		
		return $cache_rec;
	}
	
	/**
	 * @param string $key
	 * @param string $output
	 *
	 */
	public static function saveCustomOutput( string $key, string $output ): void
	{
		static::$backend?->saveCustomOutput( $key, $output );
	}

}