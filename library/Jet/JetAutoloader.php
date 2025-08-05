<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

use Jet\Autoloader_Loader;
use Jet\SysConf_Path;

/**
 *
 */
return new class extends Autoloader_Loader
{
	/**
	 * @return string
	 */
	public function getAutoloaderName() : string
	{
		return 'library/Jet';
	}
	
	/**
	 *
	 * @param string $class_name
	 *
	 * @return false|string
	 */
	public function getScriptPath( string $class_name ): false|string
	{
		
		if(!str_starts_with($class_name, 'Jet\\')) {
			return false;
		}
		
		return SysConf_Path::getLibrary() . $this->classNameToPath( $class_name );
		
	}
};