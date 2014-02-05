<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Autoloader
 * @subpackage Autoloader_Loader
 */

namespace Jet;

class Autoloader_Loader_ApplicationModules extends Autoloader_Loader_Abstract {

		
	/**
	 * Installed and activated modules list
	 *
	 * @var Application_Modules_Module_Manifest[] $modules_list
	 */
	protected $modules_list = null;

	/**
	 * Get class script path or false
	 *
	 * @param $class_name
	 *
	 * @return string|bool
	 */
	public function getClassPath($class_name) {

		if(
			substr($class_name, 0, 21)!='JetApplicationModule\\'
		) {
			return false;
		}

		$pos = strrpos($class_name, '\\');

		$module_name = substr( $class_name , 21, $pos-21 );
		$class_name = substr( $class_name,  $pos+1);

		if( $this->modules_list===null ){
			$this->modules_list = Application_Modules::getActivatedModulesList();
		}

		$module_manifest = null;
		if( isset($this->modules_list[$module_name]) ){
			$module_manifest = $this->modules_list[$module_name];
		} else {
			if( Application_Modules::getInstallationInProgress() ) {
				$all_modules = Application_Modules::getAllModulesList();
				if( isset($all_modules[$module_name]) ) {
					$module_manifest = $all_modules[$module_name];
				}
			}
		}
		if(!$module_manifest) {
			return false;
		}

		$module_path = $module_manifest->getModuleDir();

		$class_name = str_replace( '_', DIRECTORY_SEPARATOR, $class_name );
		$path = $module_path.$class_name.'.php';

		return $path;

	}
}