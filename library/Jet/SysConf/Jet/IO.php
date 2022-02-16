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
class SysConf_Jet_IO
{
	protected static int $dir_mod = 0777;
	protected static int $file_mod = 0666;
	protected static array $extensions_mimes_map = [];

	/**
	 * @return int
	 */
	public static function getDirMod(): int
	{
		return static::$dir_mod;
	}

	/**
	 * @param int $val
	 */
	public static function setDirMod( int $val ): void
	{
		static::$dir_mod = $val;
	}

	/**
	 * @return int
	 */
	public static function getFileMod(): int
	{
		return static::$file_mod;
	}

	/**
	 * @param int $val
	 */
	public static function setFileMod( int $val ): void
	{
		static::$file_mod = $val;
	}

	/**
	 * @return array
	 */
	public static function getExtensionsMimesMap(): array
	{
		return static::$extensions_mimes_map;
	}

	/**
	 * @param array $extensions_mimes_map
	 */
	public static function setExtensionsMimesMap( array $extensions_mimes_map ): void
	{
		static::$extensions_mimes_map = $extensions_mimes_map;
	}

}