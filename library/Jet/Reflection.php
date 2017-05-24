<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

require_once 'BaseObject/Cacheable/Interface.php';
require_once 'BaseObject/Cacheable/Trait.php';

require_once 'Reflection/ParserInterface.php';

/**
 *
 */
class Reflection implements BaseObject_Cacheable_Interface
{
	use BaseObject_Cacheable_Trait;

	/**
	 * @var array
	 */
	protected static $_reflections = [];

	/**
	 * @var string
	 */
	protected static $parser_regexp = '/@([a-zA-Z_]*):([^=]*)=(.*)/';

	/**
	 * @var array
	 */
	protected static $parsers = [
		'JetDataModel'  => __NAMESPACE__.'\\DataModel_Definition',
		'JetConfig'     => __NAMESPACE__.'\\Config_Definition',
	];

	/**
	 * @return string
	 */
	public static function getParserRegexp()
	{
		return static::$parser_regexp;
	}

	/**
	 * @param string $parser_regexp
	 */
	public static function setParserRegexp( $parser_regexp )
	{
		static::$parser_regexp = $parser_regexp;
	}

	/**
	 * @return array
	 */
	public static function getParsers()
	{
		return static::$parsers;
	}

	/**
	 * @param string $annotation
	 * @param string $parser_class
	 */
	public static function registerParser( $annotation, $parser_class )
	{
		static::$parsers[$annotation] = $parser_class;
	}

	/**
	 * @param string $annotation
	 *
	 * @return string|null
	 */
	public static function getParserClass( $annotation )
	{
		if(!isset(static::$parsers[$annotation])) {
			return null;
		}

		return static::$parsers[$annotation];
	}

	/**
	 * @param string $class
	 * @param string $key
	 * @param string $default_value
	 *
	 * @return mixed
	 */
	public static function get( $class, $key, $default_value = null )
	{
		$data = static::getReflectionData( $class );

		return array_key_exists( $key, $data ) ? $data[$key] : $default_value;
	}

	/**
	 * @param string $class
	 *
	 * @throws Reflection_Exception
	 *
	 * @return array
	 */
	public static function getReflectionData( $class )
	{
		if( array_key_exists( $class, static::$_reflections ) ) {
			return static::$_reflections[$class];
		}

		if( static::getCacheLoadEnabled() ) {
			$loader = static::$cache_loader;

			$data = $loader( $class );

			if( is_array($data) ) {
				static::$_reflections[$class] = $data;

				return $data;
			}
		}

		$pd = new Reflection_ParserData( $class );


		foreach( $pd->getClassReflectionHierarchy() as $current_class_reflection ) {

			$pd->setCurrentHierarchyClassReflection( $current_class_reflection );
			$pd->setCurrentPropertyReflection( null );

			/**
			 * @var \ReflectionClass $current_class_reflection
			 */
			$doc_comment = $current_class_reflection->getDocComment();

			$matches = [];

			preg_match_all( static::$parser_regexp, $doc_comment, $matches, PREG_SET_ORDER );

			foreach( $matches as $m ) {

				$parser_class_name = static::getParserClass($m[1]);
				if(!$parser_class_name) {
					continue;
				}

				$pd->setCurrentElement(
					$m[0], $parser_class_name, $m[2], $m[3]
				);

				/**
				 * @var Reflection_ParserInterface $parser_class_name
				 */
				$parser_class_name::parseClassDocComment( $pd );

			}

			foreach( $current_class_reflection->getProperties() as $property_reflection ) {
				if( $property_reflection->getName()[0]=='_' ) {
					continue;
				}

				$comment = $property_reflection->getDocComment();

				$matches = [];

				preg_match_all( static::$parser_regexp, $comment, $matches, PREG_SET_ORDER );

				if( !$matches ) {
					continue;
				}

				$pd->setCurrentPropertyReflection( $property_reflection );

				foreach( $matches as $m ) {
					$parser_class_name = static::getParserClass($m[1]);
					if(!$parser_class_name) {
						continue;
					}

					$pd->setCurrentElement(
						$m[0], $parser_class_name, $m[2], $m[3]
					);

					/**
					 * @var Reflection_ParserInterface $parser_class_name
					 */
					$parser_class_name::parsePropertyDocComment( $pd );
				}


			}
		}

		static::$_reflections[$class] = $pd->result_data;

		if( static::getCacheSaveEnabled() ) {
			$saver = static::$cache_saver;

			register_shutdown_function(
				function() use ($saver, $class) {
					$saver( $class, Reflection::getReflectionData( $class ) );
				}
			);

		}

		return $pd->result_data;
	}

}
