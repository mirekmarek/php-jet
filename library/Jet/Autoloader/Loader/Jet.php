<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Autoloader_Loader_Jet extends Autoloader_Loader
{

	/**
	 *
	 */
	protected $library_path = JET_PATH_LIBRARY;

	/**
	 * @return mixed
	 */
	public function getLibraryPath()
	{
		return $this->library_path;
	}

	/**
	 * @param mixed $library_path
	 */
	public function setLibraryPath( $library_path )
	{
		$this->library_path = $library_path;
	}


	/**
	 *
	 * @param string $class_name
	 *
	 * @return string|bool
	 */
	public function getClassPath( $class_name )
	{
		if( substr( $class_name, 0, 4 )!=__NAMESPACE__.'\\' ) {
			return false;
		}

		$class_name = str_replace( '\\', DIRECTORY_SEPARATOR, $class_name );
		$class_name = str_replace( '_', DIRECTORY_SEPARATOR, $class_name );

		return $this->library_path.$class_name.'.php';

	}
}