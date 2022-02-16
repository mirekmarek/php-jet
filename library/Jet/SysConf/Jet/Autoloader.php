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
class SysConf_Jet_Autoloader
{

	protected static bool $cache_enabled = false;

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