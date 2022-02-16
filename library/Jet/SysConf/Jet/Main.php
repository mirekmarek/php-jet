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
class SysConf_Jet_Main
{
	protected static string $charset = 'UTF-8';
	protected static string $timezone = '';


	/**
	 * @return string
	 */
	public static function getCharset(): string
	{
		return static::$charset;
	}

	/**
	 * @param string $val
	 */
	public static function setCharset( string $val ): void
	{
		static::$charset = $val;
	}

	/**
	 * @return string
	 */
	public static function getTimezone(): string
	{
		return static::$timezone;
	}

	/**
	 * @param string $timezone
	 */
	public static function setTimezone( string $timezone ): void
	{
		static::$timezone = $timezone;
	}

}