<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Autoloader_Loader;
use Jet\Application_Modules;
use Jet\SysConf_Jet_Modules;

/**
 *
 */
class Autoloader_ApplicationModules extends Autoloader_Loader
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
		if( $root_namespace != SysConf_Jet_Modules::getModuleRootNamespace() ) {
			return false;
		}

		$module_name = str_replace( '\\', '.', substr( $namespace, strlen( $root_namespace ) + 1 ) );

		if( !Application_Modules::moduleIsActivated( $module_name ) ) {
			return false;
		}


		$module_path = Application_Modules::getModuleDir( $module_name );

		$class_name = str_replace( '_', DIRECTORY_SEPARATOR, $class_name );
		return $module_path . $class_name . '.php';

	}
}