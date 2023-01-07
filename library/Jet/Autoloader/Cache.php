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
class Autoloader_Cache
{
	/**
	 * @var Autoloader_Cache_Backend|null
	 */
	protected static ?Autoloader_Cache_Backend $backend = null;

	/**
	 * @param Autoloader_Cache_Backend $backend
	 */
	public static function init( Autoloader_Cache_Backend $backend ): void
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
	 * @return array|null
	 */
	public static function load(): array|null
	{
		return static::$backend?->load();
		
	}

	/**
	 * @param array $map
	 */
	public static function save( array $map ): void
	{
		static::$backend?->save( $map );
	}

	/**
	 *
	 */
	public static function reset(): void
	{
		static::$backend?->reset();
	}
}