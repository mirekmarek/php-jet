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
class SysConf_Jet_ErrorPages
{
	/**
	 * @var string
	 */
	protected static string $error_pages_dir = '';


	/**
	 * @param string $error_pages_dir
	 *
	 */
	public static function setErrorPagesDir( string $error_pages_dir ): void
	{
		static::$error_pages_dir = $error_pages_dir;
	}

	/**
	 * @return string
	 */
	public static function getErrorPagesDir(): string
	{
		return static::$error_pages_dir;
	}

}