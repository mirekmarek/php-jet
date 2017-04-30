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
class BaseObject_Reflection {

	/**
	 * @var bool
	 */
	protected static $_save_function_registered = false;

	/**
	 * @var array
	 */
	protected static $_save_list = [];

	/**
	 * @var array
	 */
	protected static $_reflections = [];





	/**
	 * @param $class
	 *
	 * @throws BaseObject_Reflection_Exception
	 *
	 * @return array
	 */
	public static function getReflectionData( $class ) {
		if( array_key_exists($class, static::$_reflections) ) {
			return static::$_reflections[$class];
		}

		if( JET_OBJECT_REFLECTION_CACHE_LOAD ) {
			$file_path = JET_OBJECT_REFLECTION_CACHE_PATH.str_replace('\\', '__', $class.'.php');

			if(IO_File::exists($file_path)) {
				/** @noinspection PhpIncludeInspection */
				static::$_reflections[$class] = require $file_path;

				return static::$_reflections[$class];

			}
		}

		$pd = new BaseObject_Reflection_ParserData($class);


		foreach( $pd->getClassReflectionHierarchy() as $current_class_reflection ) {

			$pd->setCurrentHierarchyClassReflection($current_class_reflection);
			$pd->setCurrentPropertyReflection(null);

			/**
			 * @var \ReflectionClass $current_class_reflection
			 */
			$doc_comment=$current_class_reflection->getDocComment();

			$matches = [];

			preg_match_all('/@Jet([a-zA-Z_]*):([^=]*)=(.*)/', $doc_comment, $matches, PREG_SET_ORDER);

			foreach( $matches as $m ) {

				$pd->setCurrentElement(
					$m[0],
					$m[1],
					$m[2],
					$m[3]
				);

				/**
				 * @var BaseObject_Reflection_ParserInterface $_reflection_parser_class_name
				 */
				$_reflection_parser_class_name = __NAMESPACE__.'\\'.$pd->getReflectionParserClassName();

				$_reflection_parser_class_name::parseClassDocComment( $pd );

			}

			foreach( $current_class_reflection->getProperties() as $property_reflection ) {
				if($property_reflection->getName()[0]=='_') {
					continue;
				}

				$comment = $property_reflection->getDocComment();

				$matches = [];

				preg_match_all('/@Jet([a-zA-Z]*):([^=]*)=(.*)/', $comment, $matches, PREG_SET_ORDER);

				if(!$matches) {
					continue;
				}

				$pd->setCurrentPropertyReflection($property_reflection);

				foreach( $matches as $m ) {
					$pd->setCurrentElement(
						$m[0],
						$m[1],
						$m[2],
						$m[3]
					);


					/**
					 * @var BaseObject_Reflection_ParserInterface $_class_name
					 */
					$_reflection_parser_class_name = __NAMESPACE__.'\\'.$pd->getReflectionParserClassName();

					$_reflection_parser_class_name::parsePropertyDocComment( $pd );
				}


			}
		}

		static::$_reflections[$class] = $pd->result_data;

		if(JET_OBJECT_REFLECTION_CACHE_SAVE) {
			static::$_save_list[] = $class;

			if(!static::$_save_function_registered) {
				register_shutdown_function( function() {
					static::_save();
				} );

				static::$_save_function_registered = true;
			}
		}

		return $pd->result_data;
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
