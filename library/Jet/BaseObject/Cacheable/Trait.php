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
	 * @return bool
	 */
	public static function getCacheLoadEnabled()
	{
		return static::$cache_load_enabled && static::$cache_loader;
	}



	/**
	 * @param callable $cache_loader
	 */
	public static function enableCacheLoad( callable $cache_loader )
	{
		static::$cache_load_enabled = true;
		static::$cache_loader = $cache_loader;
	}

	/**
	 * @param callable $cache_saver
	 */
	public static function enableCacheSave( callable $cache_saver )
	{
		static::$cache_save_enabled = true;
		static::$cache_saver = $cache_saver;
	}
}