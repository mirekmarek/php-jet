<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Autoloader_Loader;
use Jet\SysConf_Jet_Modules;

/**
 *
 */
class Autoloader_ProjectModules extends Autoloader_Loader
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

		$namespace = substr( $namespace, strlen( $root_namespace ) + 1 );

		$module_path = ProjectConf_Path::getApplicationModules() . $namespace . '/';

		$module_path = str_replace( '\\', '/', $module_path );
		$class_name = str_replace( '_', '/', $class_name );

		return $module_path . $class_name . '.php';
	}
}