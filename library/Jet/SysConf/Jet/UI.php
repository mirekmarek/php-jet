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
class SysConf_Jet_UI
{
	protected static string $views_dir;

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

}