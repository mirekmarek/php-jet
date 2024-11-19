<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

/**
 *
 */
class JetStudio_Conf_Path
{
	protected static string $root;
	protected static string $bases;
	protected static string $logs;
	protected static string $tmp;
	protected static string $cache;
	protected static string $application;
	protected static string $application_classes;
	protected static string $application_modules;
	protected static string $config;
	protected static string $data;
	protected static string $dictionaries;
	protected static string $templates;

	public static function getRoot(): string
	{
		return static::$root;
	}

	public static function setRoot( string $root ): void
	{
		static::$root = $root;
	}

	public static function getBases(): string
	{
		return static::$bases;
	}

	public static function setBases( string $bases ): void
	{
		static::$bases = $bases;
	}

	public static function getLogs(): string
	{
		return static::$logs;
	}

	public static function setLogs( string $logs ): void
	{
		static::$logs = $logs;
	}

	public static function getTmp(): string
	{
		return static::$tmp;
	}

	public static function setTmp( string $tmp ): void
	{
		static::$tmp = $tmp;
	}

	public static function getCache(): string
	{
		return static::$cache;
	}

	public static function setCache( string $cache ): void
	{
		static::$cache = $cache;
	}

	public static function getApplication(): string
	{
		return static::$application;
	}

	public static function setApplication( string $application ): void
	{
		static::$application = $application;
	}

	public static function getApplicationClasses(): string
	{
		return static::$application_classes;
	}

	public static function setApplicationClasses( string $application_classes ): void
	{
		static::$application_classes = $application_classes;
	}


	public static function getApplicationModules(): string
	{
		return static::$application_modules;
	}

	public static function setApplicationModules( string $application_modules ): void
	{
		static::$application_modules = $application_modules;
	}

	public static function getConfig(): string
	{
		return static::$config;
	}

	public static function setConfig( string $config ): void
	{
		static::$config = $config;
	}

	public static function getData(): string
	{
		return static::$data;
	}

	public static function setData( string $data ): void
	{
		static::$data = $data;
	}

	public static function getDictionaries(): string
	{
		return static::$dictionaries;
	}

	public static function setDictionaries( string $dictionaries ): void
	{
		static::$dictionaries = $dictionaries;
	}
	
}