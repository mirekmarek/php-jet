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
class SysConf_Jet_Debug
{
	protected static bool $devel_mode = false;
	protected static bool $profiler_enabled = false;

	/**
	 * @return bool
	 */
	public static function getDevelMode(): bool
	{
		return static::$devel_mode;
	}

	/**
	 * @param bool $val
	 */
	public static function setDevelMode( bool $val ): void
	{
		static::$devel_mode = $val;
	}

	/**
	 * @return bool
	 */
	public static function getProfilerEnabled(): bool
	{
		return static::$profiler_enabled;
	}

	/**
	 * @param bool $val
	 */
	public static function setProfilerEnabled( bool $val ): void
	{
		static::$profiler_enabled = $val;
	}
}