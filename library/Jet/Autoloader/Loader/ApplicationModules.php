<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Autoloader_Loader_ApplicationModules
 * @package Jet
 */
class Autoloader_Loader_ApplicationModules extends Autoloader_Loader
{


	/**
	 * Installed and activated modules list
	 *
	 * @var Application_Module_Manifest[] $modules_list
	 */
	protected $modules_list = null;

	/**
	 * Get class script path or false
	 *
	 * @param string $class_name
	 *
	 * @return string|bool
	 */
	public function getClassPath( $class_name )
	{

		$ns = Application_Module_Manifest::getDefaultModuleNamespace().'\\';
		$namespace_len = strlen( $ns );

		if( substr( $class_name, 0, $namespace_len )!=$ns ) {
			return false;
		}

		$pos = strrpos( $class_name, '\\' );

		$module_name = str_replace( '\\', '.', substr( $class_name, $namespace_len, $pos-$namespace_len ) );
		$class_name = substr( $class_name, $pos+1 );

		if( $this->modules_list===null ) {
			$this->modules_list = Application_Modules::getActivatedModulesList();
		}

		$module_manifest = null;
		if( isset( $this->modules_list[$module_name] ) ) {
			$module_manifest = $this->modules_list[$module_name];
		} else {
			if( Application_Modules::getInstallationInProgress() ) {
				$all_modules = Application_Modules::getAllModulesList();
				if( isset( $all_modules[$module_name] ) ) {
					$module_manifest = $all_modules[$module_name];
				}
			}
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