<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Autoloader_Loader;

/**
 *
 */
class Autoloader_ModuleWizards extends Autoloader_Loader
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
		if( substr($namespace, 0, 23)!='JetStudio\ModuleWizard\\' ) {
			return false;
		}

		$path = substr($namespace, 23).'\\'.$class_name;
		$path = str_replace( '_', DIRECTORY_SEPARATOR,  $path);
		$path = str_replace( '\\', DIRECTORY_SEPARATOR,  $path);

		return ModuleWizards::getBasePath().$path.'.php';

	}
}

