<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	public function getScriptPath( string $root_namespace, string $namespace, string $class_name ): bool|string
	{
		if( !str_starts_with( $namespace, 'JetStudio\ModuleWizard\\' ) ) {
			return false;
		}

		$path = substr( $namespace, 23 ) . '\\' . $class_name;
		$path = str_replace( '_', DIRECTORY_SEPARATOR, $path );
		$path = str_replace( '\\', DIRECTORY_SEPARATOR, $path );

		return ModuleWizards::getBasePath() . $path . '.php';

	}
}

