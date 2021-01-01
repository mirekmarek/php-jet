<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
trait BaseObject_Cacheable_Trait
{

	/**
	 * @var bool|null
	 */
	protected static ?bool $cache_save_enabled = null;

	/**
	 * @var bool|null
	 */
	protected static ?bool $cache_load_enabled = null;

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
	public static function getCacheSaveEnabled() : bool
	{
		return static::$cache_save_enabled && static::$cache_saver;
	}

	/**
	 * @return bool
	 */
	public static function getCacheLoadEnabled() : bool
	{
		return static::$cache_load_enabled && static::$cache_loader;
	}



	/**
	 * @param callable $cache_loader
	 */
	public static function enableCacheLoad( callable $cache_loader ) : void
	{
		static::$cache_load_enabled = true;
		static::$cache_loader = $cache_loader;
	}

	/**
	 * @param callable $cache_saver
	 */
	public static function enableCacheSave( callable $cache_saver ) : void
	{
		static::$cache_save_enabled = true;
		static::$cache_saver = $cache_saver;
	}
}