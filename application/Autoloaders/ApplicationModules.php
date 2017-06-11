<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Application_Factory;
use Jet\Application_Module_Manifest;
use Jet\Application_Modules_Exception;
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
	public function getScriptPath( $root_namespace, $namespace, $class_name )
	{
		$root_namespace = Application_Modules::getModuleRootNamespace();

		if( $root_namespace!=$root_namespace ) {
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