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
interface BaseObject_Cacheable_Interface
{

	/**
	 * @return bool
	 */
	public static function getCacheSaveEnabled();

	/**
	 * @param bool $cache_save_enabled
	 */
	public static function setCacheSaveEnabled( $cache_save_enabled );

	/**
	 * @return bool
	 */
	public static function getCacheLoadEnabled();

	/**
	 * @param bool $cache_load_enabled
	 */
	public static function setCacheLoadEnabled( $cache_load_enabled );


	/**
	 * @param callable $cache_loader
	 */
	public static function setCacheLoader( callable $cache_loader );

	/**
	 * @param callable $cache_saver
	 */
	public static function setCacheSaver( callable $cache_saver );
}