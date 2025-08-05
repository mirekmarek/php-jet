<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

use Jet\Autoloader_Loader;
use JetStudio\JetStudio;

return new class extends Autoloader_Loader
{
	public const MAIN_ROOT_NAMESPACE = 'JetStudioModule\\';
	
	public function getAutoloaderName() : string
	{
		return 'JetStudio/Modules';
	}
	
	public function getScriptPath( string $class_name ): false|string
	{
		if(
			!str_starts_with( $class_name, static::MAIN_ROOT_NAMESPACE )
		) {
			return false;
		}
		
		$name = explode('\\', $class_name);
		$module_name = $name[1];
		
		$manifest=JetStudio::getModuleManifest($module_name);
		
		if(!$manifest) {
			return false;
		}
		
		return $manifest->getBaseDir() . $this->classNameToPath( $name[2] );
		
	}
};