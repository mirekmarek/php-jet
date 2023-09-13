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
	public function getAutoloaderCode() : string
	{
		return 'application/Modules';
	}

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

		return Application_Modules::getModuleDir( $module_name ) . $this->classNameToPath( $class_name );

	}
};