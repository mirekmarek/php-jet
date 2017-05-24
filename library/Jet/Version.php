<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Version extends BaseObject
{

	const API_VERSION = 201701;

	/**
	 * @return string
	 */
	public static function getVersionNumber()
	{
		return '1.0';
	}

	/**
	 * @return int
	 */
	public static function getAPIVersionNumber()
	{
		return static::API_VERSION;
	}

	/**
	 * @param int $API_version
	 *
	 * @return bool
	 */
	public static function getAPIIsCompatible( $API_version )
	{
		return $API_version==static::API_VERSION;
	}
}