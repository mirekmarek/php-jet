<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Autoloader_Loader;
use Jet\Application_Modules;

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
	public function getScriptPath( string $root_namespace, string $namespace, string $class_name ) : bool|string
	{
		if( $root_namespace!=Application_Modules::getModuleRootNamespace() ) {
			return false;
		}

		$namespace = substr($namespace, strlen($root_namespace)+1);

		$module_path = ProjectConf_PATH::APPLICATION_MODULES().$namespace.'/';

		$module_path = str_replace('\\', '/', $module_path);
		$class_name = str_replace( '_', '/', $class_name );

		$path = $module_path.$class_name.'.php';

		return $path;
	}
}