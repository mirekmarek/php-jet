<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Autoloader_Loader;
use Jet\SysConf_Path;

/**
 *
 */
class Autoloader_Jet extends Autoloader_Loader
{
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
		if( $root_namespace != 'Jet' ) {
			return false;
		}

		$class_name = str_replace( '_', DIRECTORY_SEPARATOR, $class_name );

		return SysConf_Path::getLibrary() . 'Jet/' . $class_name . '.php';

	}
}