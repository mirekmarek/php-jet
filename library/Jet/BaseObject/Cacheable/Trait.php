<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
trait BaseObject_Cacheable_Trait
{

	/**
	 * @var bool
	 */
	protected static $cache_save_enabled;

	/**
	 * @var bool
	 */
	protected static $cache_load_enabled;

	/**
	 * @var callable
	 */
	protected static $cache_loader;

	/**
	 * @var callable
	 */
	protected static $cache_saver;

	/**
	 * @return bool
	 */
	public static function getCacheSaveEnabled()
	{
		return static::$cache_save_enabled && static::$cache_saver;
	}

	/**
	 * @param bool $cache_save_enabled
	 */
	public static function setCacheSaveEnabled( $cache_save_enabled )
	{
		static::$cache_save_enabled = $cache_save_enabled;
	}

	/**
	 * @return bool
	 */
	public static function getCacheLoadEnabled()
	{
		return static::$cache_load_enabled && static::$cache_loader;
	}

	/**
	 * @param bool $cache_load_enabled
	 */
	public static function setCacheLoadEnabled( $cache_load_enabled )
	{
		static::$cache_load_enabled = $cache_load_enabled;
	}


	/**
	 * @param callable $cache_loader
	 */
	public static function setCacheLoader( callable $cache_loader )
	{
		self::$cache_loader = $cache_loader;
	}

	/**
	 * @param callable $cache_saver
	 */
	public static function setCacheSaver( callable $cache_saver )
	{
		self::$cache_saver = $cache_saver;
	}
}