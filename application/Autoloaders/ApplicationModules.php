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
	 * @param string $class_name
	 *
	 * @return string|bool
	 */
	public function getScriptPath( $class_name )
	{

		/**
		 * @var Application_Module_Manifest $manifest_class_name
		 */
		$manifest_class_name = Application_Factory::getModuleManifestClassName();

		$ns = $manifest_class_name::getDefaultModuleNamespace().'\\';
		$namespace_len = strlen( $ns );

		if( substr( $class_name, 0, $namespace_len )!=$ns ) {
			return false;
		}

		$pos = strrpos( $class_name, '\\' );

		$module_name = str_replace( '\\', '.', substr( $class_name, $namespace_len, $pos-$namespace_len ) );

		if( !Application_Modules::moduleIsActivated( $module_name) ) {
			return false;
		}

		$class_name = substr( $class_name, $pos+1 );


		try {
			$module_manifest = Application_Modules::moduleManifest( $module_name );
		} catch( Application_Modules_Exception $e ) {
			$module_manifest = null;
		}

		if( !$module_manifest ) {
			return false;
		}

		$module_path = $module_manifest->getModuleDir();

		$class_name = str_replace( '_', DIRECTORY_SEPARATOR, $class_name );
		$path = $module_path.$class_name.'.php';

		return $path;

	}
}