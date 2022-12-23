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
class SysConf_Jet_Modules
{
	protected static string $install_directory = '_install';
	protected static string $install_script = 'install.php';
	protected static string $uninstall_script = 'uninstall.php';
	protected static string $views_dir = 'views';
	protected static string $menu_items_dir = 'menu-items';
	protected static string $pages_dir = 'pages';
	protected static string $module_root_namespace = 'JetApplicationModule';
	protected static string $manifest_file_name = 'manifest.php';
	protected static ?string $installed_modules_list_file_path = null;
	protected static ?string $activated_modules_list_file_path = null;

	/**
	 * @return string
	 */
	public static function getInstallDirectory(): string
	{
		return static::$install_directory;
	}

	/**
	 * @param string $install_directory
	 */
	public static function setInstallDirectory( string $install_directory ): void
	{
		static::$install_directory = $install_directory;
	}

	/**
	 * @return string
	 */
	public static function getInstallScript(): string
	{
		return static::$install_script;
	}

	/**
	 * @param string $install_script
	 */
	public static function setInstallScript( string $install_script ): void
	{
		static::$install_script = $install_script;
	}

	/**
	 * @return string
	 */
	public static function getUninstallScript(): string
	{
		return static::$uninstall_script;
	}

	/**
	 * @param string $uninstall_script
	 */
	public static function setUninstallScript( string $uninstall_script ): void
	{
		static::$uninstall_script = $uninstall_script;
	}

	/**
	 * @return string
	 */
	public static function getViewsDir(): string
	{
		return static::$views_dir;
	}

	/**
	 * @param string $views_dir
	 */
	public static function setViewsDir( string $views_dir ): void
	{
		static::$views_dir = $views_dir;
	}

	/**
	 * @return string
	 */
	public static function getMenuItemsDir(): string
	{
		return static::$menu_items_dir;
	}

	/**
	 * @param string $menu_items_dir
	 */
	public static function setMenuItemsDir( string $menu_items_dir ): void
	{
		static::$menu_items_dir = $menu_items_dir;
	}

	/**
	 * @return string
	 */
	public static function getModuleRootNamespace(): string
	{
		return static::$module_root_namespace;
	}

	/**
	 * @param string $module_root_namespace
	 */
	public static function setModuleRootNamespace( string $module_root_namespace ): void
	{
		static::$module_root_namespace = $module_root_namespace;
	}

	/**
	 * @return string
	 */
	public static function getManifestFileName(): string
	{
		return static::$manifest_file_name;
	}

	/**
	 * @param string $manifest_file_name
	 */
	public static function setManifestFileName( string $manifest_file_name ): void
	{
		static::$manifest_file_name = $manifest_file_name;
	}

	/**
	 * @return string
	 */
	public static function getPagesDir(): string
	{
		return static::$pages_dir;
	}

	/**
	 * @param string $pages_dir
	 */
	public static function setPagesDir( string $pages_dir ): void
	{
		static::$pages_dir = $pages_dir;
	}


	/**
	 * @return string
	 */
	public static function getInstalledModulesListFilePath(): string
	{
		if( !static::$installed_modules_list_file_path ) {
			static::$installed_modules_list_file_path = SysConf_Path::getData() . 'installed_modules_list.php';
		}
		return static::$installed_modules_list_file_path;
	}

	/**
	 * @param string $installed_modules_list_file_path
	 */
	public static function setInstalledModulesListFilePath( string $installed_modules_list_file_path ) : void
	{
		static::$installed_modules_list_file_path = $installed_modules_list_file_path;
	}

	/**
	 * @return string
	 */
	public static function getActivatedModulesListFilePath(): string
	{
		if( !static::$activated_modules_list_file_path ) {
			static::$activated_modules_list_file_path = SysConf_Path::getData() . 'activated_modules_list.php';
		}
		return static::$activated_modules_list_file_path;
	}

	/**
	 * @param string $activated_modules_list_file_path
	 */
	public static function setActivatedModulesListFilePath( string $activated_modules_list_file_path ) : void
	{
		static::$activated_modules_list_file_path = $activated_modules_list_file_path;
	}
	
}