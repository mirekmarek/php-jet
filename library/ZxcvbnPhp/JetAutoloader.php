<?php
/**
 * @copyright Benjamin Jeavons
 * @autor Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

use Jet\Autoloader_Loader;
use Jet\SysConf_Path;

return new class extends Autoloader_Loader
{
	public function getAutoloaderName() : string
	{
		return 'library/ZxcvbnPhp';
	}

	public function getScriptPath( string $class_name ): false|string
	{
		
		if(!str_starts_with($class_name, 'ZxcvbnPhp\\')) {
			return false;
		}
		
		return SysConf_Path::getLibrary() . $this->classNameToPath( $class_name );
		
	}
};