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
class SysConf_Path
{
	protected static string $base;
	protected static string $modules;
	protected static string $library;
	protected static string $bases;
	protected static string $menus;
	protected static string $css;
	protected static string $js;
	protected static string $images;
	protected static string $logs;
	protected static string $tmp;
	protected static string $cache;
	protected static string $application;
	protected static string $config;
	protected static string $data;
	protected static string $dictionaries;

	/**
	 * @return string
	 */
	public static function getBase(): string
	{
		return static::$base;
	}

	/**
	 * @param string $base
	 */
	public static function setBase( string $base ): void
	{
		static::$base = $base;
	}

	/**
	 * @return string
	 */
	public static function getModules(): string
	{
		return static::$modules;
	}

	/**
	 * @param string $modules
	 */
	public static function setModules( string $modules ): void
	{
		static::$modules = $modules;
	}

	/**
	 * @return string
	 */
	public static function getLibrary(): string
	{
		return static::$library;
	}

	/**
	 * @param string $library
	 */
	public static function setLibrary( string $library ): void
	{
		static::$library = $library;
	}

	/**
	 * @return string
	 */
	public static function getBases(): string
	{
		return static::$bases;
	}

	/**
	 * @param string $bases
	 */
	public static function setBases( string $bases ): void
	{
		static::$bases = $bases;
	}


	/**
	 * @return string
	 */
	public static function getMenus(): string
	{
		return static::$menus;
	}

	/**
	 * @param string $menus
	 */
	public static function setMenus( string $menus ): void
	{
		static::$menus = $menus;
	}

	/**
	 * @return string
	 */
	public static function getCss(): string
	{
		return static::$css;
	}

	/**
	 * @param string $css
	 */
	public static function setCss( string $css ): void
	{
		static::$css = $css;
	}

	/**
	 * @return string
	 */
	public static function getJs(): string
	{
		return static::$js;
	}

	/**
	 * @param string $js
	 */
	public static function setJs( string $js ): void
	{
		static::$js = $js;
	}

	/**
	 * @return string
	 */
	public static function getImages(): string
	{
		return static::$images;
	}

	/**
	 * @param string $images
	 */
	public static function setImages( string $images ): void
	{
		static::$images = $images;
	}


	/**
	 * @return string
	 */
	public static function getLogs(): string
	{
		return static::$logs;
	}

	/**
	 * @param string $logs
	 */
	public static function setLogs( string $logs ): void
	{
		static::$logs = $logs;
	}

	/**
	 * @return string
	 */
	public static function getTmp(): string
	{
		return static::$tmp;
	}

	/**
	 * @param string $tmp
	 */
	public static function setTmp( string $tmp ): void
	{
		static::$tmp = $tmp;
	}

	/**
	 * @return string
	 */
	public static function getCache(): string
	{
		return static::$cache;
	}

	/**
	 * @param string $cache
	 */
	public static function setCache( string $cache ): void
	{
		static::$cache = $cache;
	}

	/**
	 * @return string
	 */
	public static function getApplication(): string
	{
		return static::$application;
	}

	/**
	 * @param string $application
	 */
	public static function setApplication( string $application ): void
	{
		static::$application = $application;
	}

	/**
	 * @return string
	 */
	public static function getConfig(): string
	{
		return static::$config;
	}

	/**
	 * @param string $config
	 */
	public static function setConfig( string $config ): void
	{
		static::$config = $config;
	}

	/**
	 * @return string
	 */
	public static function getData(): string
	{
		return static::$data;
	}

	/**
	 * @param string $data
	 */
	public static function setData( string $data ): void
	{
		static::$data = $data;
	}

	/**
	 * @return string
	 */
	public static function getDictionaries(): string
	{
		return static::$dictionaries;
	}

	/**
	 * @param string $dictionaries
	 */
	public static function setDictionaries( string $dictionaries ): void
	{
		static::$dictionaries = $dictionaries;
	}
}