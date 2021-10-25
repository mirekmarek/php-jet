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
class SysConf_Jet
{
	protected static string $charset = 'UTF-8';
	protected static string $timezone = '';


	/**
	 * @return string
	 */
	public static function getCharset(): string
	{
		return self::$charset;
	}

	/**
	 * @param string $val
	 */
	public static function setCharset( string $val ): void
	{
		self::$charset = $val;
	}

	/**
	 * @return string
	 */
	public static function getTimezone(): string
	{
		return self::$timezone;
	}

	/**
	 * @param string $timezone
	 */
	public static function setTimezone( string $timezone ): void
	{
		self::$timezone = $timezone;
	}

}