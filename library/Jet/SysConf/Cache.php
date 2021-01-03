<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class SysConf_Cache {

	/**
	 * @var bool
	 */
	protected static bool $mvc_enables = false;

	/**
	 * @var bool
	 */
	protected static bool $autoloader_enabled = false;

	/**
	 * @return bool
	 */
	public static function isMvcEnabled(): bool
	{
		return self::$mvc_enables;
	}

	/**
	 * @param bool $mvc_enables
	 */
	public static function setMvcEnabled( bool $mvc_enables ): void
	{
		self::$mvc_enables = $mvc_enables;
	}

	/**
	 * @return bool
	 */
	public static function isAutoloaderEnabled(): bool
	{
		return self::$autoloader_enabled;
	}

	/**
	 * @param bool $autoloader_enabled
	 */
	public static function setAutoloaderEnabled( bool $autoloader_enabled ): void
	{
		self::$autoloader_enabled = $autoloader_enabled;
	}


}
