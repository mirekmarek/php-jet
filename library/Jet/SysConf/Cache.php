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
	protected static bool $MVC_ENABLED = false;

	/**
	 * @var bool
	 */
	protected static bool $AUTOLOADER_ENABLED = false;

	/**
	 * @return bool
	 */
	public static function isMVC_ENABLED(): bool
	{
		return self::$MVC_ENABLED;
	}

	/**
	 * @param bool $MVC_ENABLED
	 */
	public static function setMVC_ENABLED( bool $MVC_ENABLED ): void
	{
		self::$MVC_ENABLED = $MVC_ENABLED;
	}

	/**
	 * @return bool
	 */
	public static function isAUTOLOADER_ENABLED(): bool
	{
		return self::$AUTOLOADER_ENABLED;
	}

	/**
	 * @param bool $AUTOLOADER_ENABLED
	 */
	public static function setAUTOLOADER_ENABLED( bool $AUTOLOADER_ENABLED ): void
	{
		self::$AUTOLOADER_ENABLED = $AUTOLOADER_ENABLED;
	}


}
