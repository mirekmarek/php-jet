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
class SysConf_Jet_MVC_View
{
	/**
	 * @var string
	 */
	protected static string $script_file_suffix = 'phtml';

	/**
	 *
	 * view path information (<!-- VIEW START: /view/dir/view.phtml -->, <!-- VIEW START: /view/dir/view.phtml -->)
	 *
	 * @var bool
	 */
	protected static bool $add_script_path_info = false;

	/**
	 * @return string
	 */
	public static function getScriptFileSuffix(): string
	{
		return self::$script_file_suffix;
	}

	/**
	 * @param string $script_file_suffix
	 */
	public static function setScriptFileSuffix( string $script_file_suffix ): void
	{
		self::$script_file_suffix = $script_file_suffix;
	}

	/**
	 * @return bool
	 */
	public static function getAddScriptPathInfo(): bool
	{
		return self::$add_script_path_info;
	}

	/**
	 * @param bool $add_script_path_info
	 */
	public static function setAddScriptPathInfo( bool $add_script_path_info ): void
	{
		self::$add_script_path_info = $add_script_path_info;
	}


}