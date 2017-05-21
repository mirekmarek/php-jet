<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Autoloader_Loader;

/**
 *
 */
class Autoloader_ApplicationClasses extends Autoloader_Loader
{

	/**
	 * @param $class_name
	 *
	 * @return string|bool
	 */
	public function getScriptPath( $class_name )
	{

		if( substr( $class_name, 0, 15 )!='JetApplication\\' ) {
			return false;
		}

		$class_name = substr( $class_name, 14 );

		$class_name = str_replace( '\\', DIRECTORY_SEPARATOR, $class_name );
		$class_name = str_replace( '_', DIRECTORY_SEPARATOR, $class_name );

		return JET_PATH_APPLICATION.'classes/'.$class_name.'.php';

	}
}