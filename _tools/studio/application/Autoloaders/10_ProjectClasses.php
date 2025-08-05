<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

use Jet\Autoloader_Loader;
use JetStudio\JetStudio;
use JetStudio\JetStudio_Conf_Path;

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
		return 'JetStudio/application/Classes';
	}

	/**
	 *
	 * @param string $class_name
	 *
	 * @return bool|string
	 */
	public function getScriptPath( string $class_name ): false|string
	{
		$root_namespace = JetStudio::getApplicationNamespace().'\\';
		
		if( !str_starts_with($class_name, $root_namespace ) ) {
			return false;
		}
		
		return JetStudio_Conf_Path::getApplicationClasses() . $this->classNameToPath( substr($class_name, strlen($root_namespace)) );
	}
};