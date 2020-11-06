<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
interface BaseObject_Cacheable_Interface
{

	/**
	 * @return bool
	 */
	public static function getCacheSaveEnabled();

	/**
	 * @return bool
	 */
	public static function getCacheLoadEnabled();


	/**
	 * @param callable $cache_loader
	 */
	public static function enableCacheLoad( callable $cache_loader );

	/**
	 * @param callable $cache_saver
	 */
	public static function enableCacheSave( callable $cache_saver );
}