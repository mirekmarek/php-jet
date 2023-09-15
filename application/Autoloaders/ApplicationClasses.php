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
		return 'application/Classes';
	}

	/**
	 *
	 * @param string $root_namespace
	 * @param string $namespace
	 * @param string $class_name
	 *
	 * @return bool|string
	 */
	public function getScriptPath( string $root_namespace, string $namespace, string $class_name ): bool|string
	{

		if( $root_namespace != 'JetApplication' ) {
			return false;
		}

		return SysConf_Path::getApplication() . 'Classes/' . $this->classNameToPath( $class_name );

	}
};