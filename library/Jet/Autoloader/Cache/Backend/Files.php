<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

require_once SysConf_Path::getLibrary() . 'Jet/Cache.php';
require_once SysConf_Path::getLibrary() . 'Jet/Cache/Files.php';
require_once SysConf_Path::getLibrary() . 'Jet/Autoloader/Cache.php';
require_once SysConf_Path::getLibrary() . 'Jet/Autoloader/Cache/Backend.php';


/**
 *
 */
class Autoloader_Cache_Backend_Files extends Cache_Files implements Autoloader_Cache_Backend
{

	const KEY = 'autoloader_class_map';

	/**
	 * @return bool
	 */
	public function isActive(): bool
	{
		return SysConf_Jet_Autoloader::getCacheEnabled();
	}

	/**
	 *
	 * @return array|null
	 */
	public function load(): array|null
	{
		return $this->readData( static::KEY );
	}


	/**
	 * @param array $map
	 */
	public function save( array $map ): void
	{
		$this->writeData( static::KEY, $map );
	}

	/**
	 *
	 */
	public function reset(): void
	{
		$this->resetDataFile( static::KEY );
	}
}
