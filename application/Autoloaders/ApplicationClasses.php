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
	public const ROOT_NAMESPACE = 'JetApplication\\';
	
	/**
	 * @return string
	 */
	public function getAutoloaderName() : string
	{
		return 'application/Classes';
	}

	/**
	 *
	 * @param string $class_name
	 *
	 * @return bool|string
	 */
	public function getScriptPath( string $class_name ): bool|string
	{
		if( !str_starts_with($class_name, static::ROOT_NAMESPACE ) ) {
			return false;
		}
		
		return SysConf_Path::getApplication() . 'Classes/' . $this->classNameToPath( substr($class_name, strlen(static::ROOT_NAMESPACE)) );

	}
};