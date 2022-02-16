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
class SysConf_Jet_MVC
{
	protected static string $page_data_file_name = 'page_data.php';
	protected static bool $use_module_pages = true;
	protected static string $base_data_file_name = 'base_data.php';
	protected static string $base_pages_dir = 'pages';
	protected static string $base_layouts_dir = 'layouts';
	protected static string $base_views_dir = 'views';
	protected static bool $force_slash_on_URL_end = false;
	protected static bool $cache_enabled = false;

	/**
	 * @return bool
	 */
	public static function getUseModulePages(): bool
	{
		return static::$use_module_pages;
	}

	/**
	 * @param bool $use_module_pages
	 */
	public static function setUseModulePages( bool $use_module_pages ): void
	{
		static::$use_module_pages = $use_module_pages;
	}

	/**
	 * @return string
	 */
	public static function getPageDataFileName(): string
	{
		return self::$page_data_file_name;
	}

	/**
	 * @param string $page_data_file_name
	 */
	public static function setPageDataFileName( string $page_data_file_name ): void
	{
		self::$page_data_file_name = $page_data_file_name;
	}

	/**
	 * @return string
	 */
	public static function getBaseDataFileName(): string
	{
		return self::$base_data_file_name;
	}

	/**
	 * @param string $base_data_file_name
	 */
	public static function setBaseDataFileName( string $base_data_file_name ): void
	{
		self::$base_data_file_name = $base_data_file_name;
	}

	/**
	 * @return string
	 */
	public static function getBasePagesDir(): string
	{
		return self::$base_pages_dir;
	}

	/**
	 * @param string $base_pages_dir
	 */
	public static function setBasePagesDir( string $base_pages_dir ): void
	{
		self::$base_pages_dir = $base_pages_dir;
	}

	/**
	 * @return string
	 */
	public static function getBaseLayoutsDir(): string
	{
		return self::$base_layouts_dir;
	}

	/**
	 * @param string $base_layouts_dir
	 */
	public static function setBaseLayoutsDir( string $base_layouts_dir ): void
	{
		self::$base_layouts_dir = $base_layouts_dir;
	}

	/**
	 * @return string
	 */
	public static function getBaseViewsDir(): string
	{
		return self::$base_views_dir;
	}

	/**
	 * @param string $base_views_dir
	 */
	public static function setBaseViewsDir( string $base_views_dir ): void
	{
		self::$base_views_dir = $base_views_dir;
	}


	/**
	 * @return bool
	 */
	public static function getForceSlashOnURLEnd(): bool
	{
		return self::$force_slash_on_URL_end;
	}

	/**
	 * @param bool $force_slash_on_URL_end
	 */
	public static function setForceSlashOnURLEnd( bool $force_slash_on_URL_end ): void
	{
		self::$force_slash_on_URL_end = $force_slash_on_URL_end;
	}

	/**
	 * @return bool
	 */
	public static function getCacheEnabled(): bool
	{
		return self::$cache_enabled;
	}

	/**
	 * @param bool $cache_enabled
	 */
	public static function setCacheEnabled( bool $cache_enabled ): void
	{
		self::$cache_enabled = $cache_enabled;
	}


}