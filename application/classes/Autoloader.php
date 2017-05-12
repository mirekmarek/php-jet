<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetExampleApp;

use Jet\Autoloader_Loader;

/**
 *
 */
class Autoloader extends Autoloader_Loader
{

	/**
	 * @param $class_name
	 *
	 * @return string|bool
	 */
	public function getClassPath( $class_name )
	{
		if( substr( $class_name, 0, 14 )!='JetExampleApp\\' ) {
			return false;
		}

		$class_name = substr( $class_name, 14 );

		$class_name = str_replace( '\\', DIRECTORY_SEPARATOR, $class_name );
		$class_name = str_replace( '_', DIRECTORY_SEPARATOR, $class_name );

		return JET_APPLICATION_PATH.'classes/'.$class_name.'.php';

	}
}