<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Autoloader_Loader;
use Jet\Application_Modules;

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
	public function getScriptPath( string $root_namespace, string $namespace, string $class_name ) : bool|string
	{
		if( $root_namespace!=Application_Modules::getModuleRootNamespace() ) {
			return false;
		}

		$module_name = str_replace( '\\', '.', substr( $namespace, strlen($root_namespace)+1 ) );

		if( !Application_Modules::moduleIsActivated( $module_name) ) {
			return false;
		}


		$module_path = Application_Modules::getModuleDir( $module_name );

		$class_name = str_replace( '_', DIRECTORY_SEPARATOR, $class_name );
		$path = $module_path.$class_name.'.php';


		return $path;

	}
}