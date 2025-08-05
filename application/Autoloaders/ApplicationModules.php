<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

use Jet\Autoloader_Loader;
use Jet\Application_Modules;
use Jet\SysConf_Jet_Modules;

/**
 *
 */
return new class extends Autoloader_Loader
{
	/**
	 * @return string
	 */
	public function getAutoloaderName() : string
	{
		return 'application/Modules';
	}

	/**
	 *
	 * @param string $class_name
	 *
	 * @return false|string
	 */
	public function getScriptPath( string $class_name ): false|string
	{
		$modules_namespace = SysConf_Jet_Modules::getModuleRootNamespace().'\\';
		
		if(!str_starts_with($class_name, $modules_namespace)) {
			return false;
		}
		
		$module_and_class_name = substr( $class_name, strlen($modules_namespace) );
		
		$module_name_end = strrpos( $module_and_class_name, '\\' );
		
		$module_name = substr( $module_and_class_name, 0, $module_name_end );
		$class_name = substr( $module_and_class_name, $module_name_end+1 );
		
		$module_name = str_replace( '\\', '.', $module_name );
		

		if( !Application_Modules::moduleIsActivated( $module_name ) ) {
			return false;
		}

		return Application_Modules::getModuleDir( $module_name ) . $this->classNameToPath( $class_name );

	}
};