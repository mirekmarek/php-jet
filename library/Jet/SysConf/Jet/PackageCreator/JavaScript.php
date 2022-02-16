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
class SysConf_Jet_PackageCreator_JavaScript
{
	protected static bool $enabled = false;
	protected static string $packages_dir_name = 'packages';

	/**
	 * @return bool
	 */
	public static function getEnabled(): bool
	{
		return self::$enabled;
	}

	/**
	 * @param bool $enabled
	 */
	public static function setEnabled( bool $enabled ): void
	{
		self::$enabled = $enabled;
	}

	/**
	 * @return string
	 */
	public static function getPackagesDirName(): string
	{
		return self::$packages_dir_name;
	}

	/**
	 * @param string $packages_dir_name
	 */
	public static function setPackagesDirName( string $packages_dir_name ): void
	{
		self::$packages_dir_name = $packages_dir_name;
	}

}