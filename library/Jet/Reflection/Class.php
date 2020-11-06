<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Reflection_Class extends \ReflectionClass
{

	/**
	 * @var string
	 */
	protected $use_classes_str = '';

	/**
	 * @var array
	 */
	protected $use_classes_map;

	/**
	 * @return string
	 */
	public function getUseClassesStr()
	{
		$this->parseUseClasses();

		return $this->use_classes_str;
	}

	/**
	 *
	 */
	public function parseUseClasses()
	{
		if( $this->use_classes_map!==null ) {
			return;
		}

		$this->use_classes_str = '';
		$this->use_classes_map = [];


		$script = file_get_contents( $this->getFileName() );

		if( preg_match_all( '/use [0-9a-zA-Z_\\\\ ]+;/', $script, $matches, PREG_SET_ORDER ) ) {
			foreach( $matches as $m ) {
				$m = $m[0];
				if( strpos( $m, '\\' )===false ) {
					continue;
				}

				while( strpos( $m, '  ' )!==false ) {
					$m = str_replace( '  ', ' ', $m );
				}

				$this->use_classes_str .= $m;

				$m = trim( $m );
				$m = trim( substr( $m, 4, -1 ) );

				if( ( $as_pos = stripos( $m, ' as ' ) )!==false ) {
					$class = substr( $m, 0, $as_pos );
					$use_as = substr( $m, $as_pos+4 );
				} else {
					$class = $m;

					$use_as = substr( $class, strrpos( $class, '\\' )+1 );
				}

				$this->use_classes_map[$use_as] = $class;

			}
		}

	}

	/**
	 * @return array
	 */
	public function getUseClassesMap()
	{
		$this->parseUseClasses();

		return $this->use_classes_map;
	}


}