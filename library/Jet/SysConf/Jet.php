<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class SysConf_Jet
{
	protected static bool $devel_mode = false;

	protected static bool $debug_profiler_enabled = false;

	protected static bool $CSS_packager_enabled = true;
	protected static bool $JS_packager_enabled = true;

	protected static bool $translator_auto_append_unknown_phrase = true;

	protected static int $IO_mod_dir = 0777;
	protected static int $IO_mod_file = 0666;

	protected static bool $hide_http_request = true;

	protected static string $charset = 'UTF-8';

	protected static string $timezone = '';

	protected static bool $cache_mvc_enabled = false;
	protected static bool $cache_autoloader_enabled = false;

	/**
	 * @return bool
	 */
	public static function isDevelMode(): bool
	{
		return self::$devel_mode;
	}

	/**
	 * @param bool $val
	 */
	public static function setDevelMode( bool $val ): void
	{
		self::$devel_mode = $val;
	}

	/**
	 * @return bool
	 */
	public static function isDebugProfilerEnabled(): bool
	{
		return self::$debug_profiler_enabled;
	}

	/**
	 * @param bool $val
	 */
	public static function setDebugProfilerEnabled( bool $val ): void
	{
		self::$debug_profiler_enabled = $val;
	}

	/**
	 * @return bool
	 */
	public static function isCSSPackagerEnabled(): bool
	{
		return self::$CSS_packager_enabled;
	}

	/**
	 * @param bool $val
	 */
	public static function setCSSPackagerEnabled( bool $val ): void
	{
		self::$CSS_packager_enabled = $val;
	}

	/**
	 * @return bool
	 */
	public static function isJSPackagerEnabled(): bool
	{
		return self::$JS_packager_enabled;
	}

	/**
	 * @param bool $val
	 */
	public static function setJSPackagerEnabled( bool $val ): void
	{
		self::$JS_packager_enabled = $val;
	}

	/**
	 * @return bool
	 */
	public static function isTranslatorAutoAppendUnknownPhrase(): bool
	{
		return self::$translator_auto_append_unknown_phrase;
	}

	/**
	 * @param bool $val
	 */
	public static function setTranslatorAutoAppendUnknownPhrase( bool $val ): void
	{
		self::$translator_auto_append_unknown_phrase = $val;
	}

	/**
	 * @return int
	 */
	public static function getIOModDir(): int
	{
		return self::$IO_mod_dir;
	}

	/**
	 * @param int $val
	 */
	public static function setIOModDir( int $val ): void
	{
		self::$IO_mod_dir = $val;
	}

	/**
	 * @return int
	 */
	public static function getIOModFile(): int
	{
		return self::$IO_mod_file;
	}

	/**
	 * @param int $val
	 */
	public static function setIOModFile( int $val ): void
	{
		self::$IO_mod_file = $val;
	}

	/**
	 * @return bool
	 */
	public static function isHideHttpRequest(): bool
	{
		return self::$hide_http_request;
	}

	/**
	 * @param bool $val
	 */
	public static function setHideHttpRequest( bool $val ): void
	{
		self::$hide_http_request = $val;
	}

	/**
	 * @return string
	 */
	public static function getCharset(): string
	{
		return self::$charset;
	}

	/**
	 * @param string $val
	 */
	public static function setCharset( string $val ): void
	{
		self::$charset = $val;
	}

	/**
	 * @return string
	 */
	public static function getTimezone(): string
	{
		return self::$timezone;
	}

	/**
	 * @param string $timezone
	 */
	public static function setTimezone( string $timezone ): void
	{
		self::$timezone = $timezone;
	}

	/**
	 * @return bool
	 */
	public static function isCacheMvcEnabled(): bool
	{
		return self::$cache_mvc_enabled;
	}

	/**
	 * @param bool $mvc_enables
	 */
	public static function setCacheMvcEnabled( bool $mvc_enables ): void
	{
		self::$cache_mvc_enabled = $mvc_enables;
	}

	/**
	 * @return bool
	 */
	public static function isCacheAutoloaderEnabled(): bool
	{
		return self::$cache_autoloader_enabled;
	}

	/**
	 * @param bool $cache_autoloader_enabled
	 */
	public static function setCacheAutoloaderEnabled( bool $cache_autoloader_enabled ): void
	{
		self::$cache_autoloader_enabled = $cache_autoloader_enabled;
	}

}