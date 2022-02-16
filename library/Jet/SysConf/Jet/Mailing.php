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
class SysConf_Jet_Mailing
{

	protected static string $templates_dir;

	/**
	 * @return string
	 */
	public static function getTemplatesDir(): string
	{
		return static::$templates_dir;
	}

	/**
	 * @param string $templates_dir
	 */
	public static function setTemplatesDir( string $templates_dir ): void
	{
		static::$templates_dir = $templates_dir;
	}


}