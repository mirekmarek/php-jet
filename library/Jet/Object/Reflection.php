<?php
/**
 *
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Object
 * @subpackage Reflection
 */

namespace Jet;

/**
 * No OOP, but this one must be realy fast
 *
 */
class Object_Reflection {

	/**
	 * @var bool
	 */
	protected static $_save_function_registered = false;

	/**
	 * @var array
	 */
	protected static $_save_list = array();

	/**
	 * @var array
	 */
	protected static $_reflections = array();

	/**
	 * @param $class
	 *
	 * @throws Object_Reflection_Exception
	 *
	 * @return array
	 */
	public static function getReflectionData( $class ) {
		if( isset(static::$_reflections[$class]) ) {
			return static::$_reflections[$class];
		}

		if( JET_OBJECT_REFLECTION_CACHE_LOAD ) {
			$file_path = JET_OBJECT_REFLECTION_CACHE_PATH.str_replace('\\', '__', $class.'.php');

			if(IO_File::isReadable($file_path)) {
				/** @noinspection PhpIncludeInspection */
				static::$_reflections[$class] = require $file_path;

				return static::$_reflections[$class];

			}
		}

		$reflection_data = array();


		$reflection = new \ReflectionClass( $class );

		/**
		 * @var \ReflectionClass[] $reflections
		 */
		$reflections = array();

		while($reflection) {

			foreach( $reflection->getTraits() as $trait_reflection ) {
				array_unshift( $reflections, $trait_reflection );
			}

			array_unshift( $reflections, $reflection );


			if($reflection->isAbstract()) {
				break;
			}

			$reflection = $reflection->getParentClass();
		};

		foreach( $reflections as $reflection ) {

			/**
			 * @var \ReflectionClass $reflection
			 */
			$doc_comment=$reflection->getDocComment();

			$matches = array();

			preg_match_all('/@Jet([a-zA-Z_]*):([^=]*)=(.*)/', $doc_comment, $matches, PREG_SET_ORDER);

			foreach( $matches as $m ) {

				$definition = $m[0];
				$class_name = $m[1];
				$key = trim($m[2]);
				$value_raw = trim($m[3]);

				$value = null;
				$eval_res = @eval('$value='.$value_raw.'; return true;');

				if( !$eval_res ) {
					throw new Object_Reflection_Exception( 'Value parse error! Class:\''.$class.'\', Definition: \''.$definition.'\' ' );
				}

				/**
				 * @var Object_Reflection_ParserInterface $_class_name
				 */
				$_class_name = 'Jet\\'.$class_name;

				$_class_name::parseClassDocComment( $reflection_data, $key, $definition, $value );

			}

			foreach( $reflection->getProperties() as $prop_ref ) {
				if($prop_ref->getName()[0]=='_') {
					continue;
				}

				$comment = $prop_ref->getDocComment();

				$matches = array();

				preg_match_all('/@Jet([a-zA-Z]*):([^=]*)=(.*)/', $comment, $matches, PREG_SET_ORDER);

				if(!$matches) {
					continue;
				}

				$property_name = $prop_ref->getName();

				foreach( $matches as $m ) {
					$definition = $m[0];
					$class_name = $m[1];
					$key = trim($m[2]);
					$raw_value = trim($m[3]);

					$value = null;
					$eval_res = @eval('$value='.$raw_value.'; return true;');

					if( !$eval_res ) {
						throw new Object_Reflection_Exception( 'Value parse error! Class:\''.$class.'\', Definition: \''.$definition.'\' ' );
					}

					/**
					 * @var Object_Reflection_ParserInterface $_class_name
					 */
					$_class_name = 'Jet\\'.$class_name;

					$_class_name::parsePropertyDocComment( $reflection_data, $property_name, $key, $definition, $value );
				}


			}
		}

		static::$_reflections[$class] = $reflection_data;

		if(JET_OBJECT_REFLECTION_CACHE_SAVE) {
			static::$_save_list[] = $class;

			if(!static::$_save_function_registered) {
				register_shutdown_function( function() {
					static::_save();
				} );

				static::$_save_function_registered = true;
			}
		}

		return $reflection_data;
	}

	/**
	 *
	 */
	protected static  function _save() {
		foreach( static::$_save_list as $class ) {
			$file_path = JET_OBJECT_REFLECTION_CACHE_PATH.str_replace('\\', '__', $class.'.php');

			IO_File::write($file_path, '<?php return '.var_export( static::$_reflections[$class], true ).';' );
		}
	}

	/**
	 * @param $class
	 * @param $key
	 * @param $default_value
	 *
	 * @return mixed
	 */
	public static function get( $class, $key, $default_value=null ) {
		$data = static::getReflectionData( $class );

		return array_key_exists($key, $data) ? $data[$key] : $default_value;
	}


}
