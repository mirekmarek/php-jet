<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2016-2015 Miroslav Marek <mirek.marek.2m@gmail.com>
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
 * No OOP, but this one must be really fast
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
	 * @param string $class_name
	 * @throws Application_Modules_Exception
	 * @return string
	 */
	public static function parseClassName( $class_name ) {

		$prefix = 'module:';
		$prefix_len = strlen($prefix);

		if(substr($class_name,0, $prefix_len)==$prefix) {

			list($module_name, $class_name) = explode('\\', substr($class_name, $prefix_len));

			$module_manifest = Application_Modules::getModuleManifest($module_name);

			if(!$module_manifest) {
				throw new Application_Modules_Exception('Unknown module '.$module_name);
			}

			$class_name = $module_manifest->getNamespace().$class_name;

			return $class_name;
		}

		if(strpos($class_name,'\\')===false) {
			$class_name = __NAMESPACE__.'\\'.$class_name;
		}

		return $class_name;

	}

	/**
	 * @param callable|array $callback
	 * @param string $class_name
	 *
	 * @return array
	 */
	public static function parseCallback( $callback, $class_name ) {
		if(is_array($callback)) {
			if($callback[0]=='this') {
				$callback[0] = $class_name;
			} else {
				$callback[0] = static::parseClassName($callback[0]);
			}
		}

		return $callback;
	}

	/**
	 * @param string $class
	 * @param \ReflectionClass $reflection
	 * @param string $definition
	 * @param string $value_raw
	 * @return mixed
	 *
	 * @throws BaseObject_Reflection_Exception
	 */
	public static function parseValue(
							$class,
							/** @noinspection PhpUnusedParameterInspection */
							\ReflectionClass $reflection,
							$definition,
							$value_raw
						) {
		$value = null;

		$eval_code = '';
		$eval_code .= 'namespace '.__NAMESPACE__.';';
		$eval_code .= '$value='.$value_raw.'; return true;';

		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		$eval_res = @eval($eval_code);

		if( !$eval_res ) {
			throw new BaseObject_Reflection_Exception( 'Value parse error! Class:\''.$class.'\', Definition: \''.$definition.'\' ' );
		}

		return $value;
	}


	/**
	 * @param $class
	 *
	 * @throws BaseObject_Reflection_Exception
	 *
	 * @return array
	 */
	public static function getReflectionData( $class ) {
		if( isset(static::$_reflections[$class]) ) {
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

		$reflection_data = [];


		$reflection = new \ReflectionClass( $class );

		/**
		 * @var \ReflectionClass[] $reflections
		 */
		$reflections = [];

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

			$matches = [];

			preg_match_all('/@Jet([a-zA-Z_]*):([^=]*)=(.*)/', $doc_comment, $matches, PREG_SET_ORDER);

			foreach( $matches as $m ) {

				$definition = $m[0];
				$reflection_parser_class_name = $m[1];
				$key = trim($m[2]);
				$value_raw = trim($m[3]);

				$value = static::parseValue( $class, $reflection, $definition, $value_raw );


				/**
				 * @var BaseObject_Reflection_ParserInterface $_reflection_parser_class_name
				 */
				$_reflection_parser_class_name = __NAMESPACE__.'\\'.$reflection_parser_class_name;

				$_reflection_parser_class_name::parseClassDocComment($reflection_data, $class, $key, $definition, $value);

			}

			foreach( $reflection->getProperties() as $prop_ref ) {
				if($prop_ref->getName()[0]=='_') {
					continue;
				}

				$comment = $prop_ref->getDocComment();

				$matches = [];

				preg_match_all('/@Jet([a-zA-Z]*):([^=]*)=(.*)/', $comment, $matches, PREG_SET_ORDER);

				if(!$matches) {
					continue;
				}

				$property_name = $prop_ref->getName();

				foreach( $matches as $m ) {
					$definition = $m[0];
					$reflection_parser_class_name = $m[1];
					$key = trim($m[2]);
					$raw_value = trim($m[3]);

					$value = static::parseValue( $class, $reflection, $definition, $raw_value );


					/**
					 * @var BaseObject_Reflection_ParserInterface $_class_name
					 */
					$_reflection_parser_class_name = __NAMESPACE__.'\\'.$reflection_parser_class_name;

					$_reflection_parser_class_name::parsePropertyDocComment($reflection_data, $class, $property_name, $key, $definition, $value);
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
