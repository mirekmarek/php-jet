<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Config_Definition_Config
 * @package Jet
 */
class Config_Definition extends BaseObject implements Reflection_ParserInterface
{
	/**
	 * @var string
	 */
	protected static $cache_dir_path = JET_PATH_DATA.'config_definitions/';

	/**
	 * @var bool
	 */
	protected static $cache_save_enabled;

	/**
	 * @var bool
	 */
	protected static $cache_load_enabled;

	/**
	 * @return string
	 */
	public static function getCacheDirPath()
	{
		return static::$cache_dir_path;
	}

	/**
	 * @param string $cache_dir_path
	 */
	public static function setCacheDirPath( $cache_dir_path )
	{
		static::$cache_dir_path = $cache_dir_path;
	}

	/**
	 * @return bool
	 */
	public static function getCacheSaveEnabled()
	{
		if(static::$cache_save_enabled===null) {
			if(defined('JET_CONFIG_DEFINITION_CACHE_SAVE')) {
				static::$cache_save_enabled = JET_CONFIG_DEFINITION_CACHE_SAVE;
			} else {
				static::$cache_save_enabled = false;
			}
		}

		return static::$cache_save_enabled;
	}

	/**
	 * @param bool $cache_save_enabled
	 */
	public static function setCacheSaveEnabled( $cache_save_enabled )
	{
		static::$cache_save_enabled = $cache_save_enabled;
	}

	/**
	 * @return bool
	 */
	public static function getCacheLoadEnabled()
	{
		if(static::$cache_load_enabled===null) {
			if(defined('JET_CONFIG_DEFINITION_CACHE_LOAD')) {
				static::$cache_load_enabled = JET_CONFIG_DEFINITION_CACHE_LOAD;
			} else {
				static::$cache_load_enabled = false;
			}
		}

		return static::$cache_load_enabled;
	}

	/**
	 * @param bool $cache_load_enabled
	 */
	public static function setCacheLoadEnabled( $cache_load_enabled )
	{
		static::$cache_load_enabled = $cache_load_enabled;
	}


	/**
	 * @param string $class_name
	 *
	 * @return Config_Definition_Config
	 */
	public static function getDefinition( $class_name )
	{

		$file_path = static::getCacheDirPath().str_replace( '\\', '__', $class_name.'.php' );

		if( static::getCacheLoadEnabled() ) {

			if( IO_File::exists( $file_path ) ) {
				/** @noinspection PhpIncludeInspection */
				$definition = require $file_path;

				return $definition;
			}
		}

		$definition = new Config_Definition_Config( $class_name );

		if( static::getCacheSaveEnabled() ) {
			/** @noinspection PhpUsageOfSilenceOperatorInspection */
			IO_File::write( $file_path, '<?php return '.@var_export( $definition, true ).';' );
		}


		return $definition;
	}


	/**
	 * @param Reflection_ParserData $data
	 *
	 * @throws Reflection_Exception
	 */
	public static function parseClassDocComment( Reflection_ParserData $data )
	{

		switch( $data->getKey() ) {
			case 'section_is_obligatory':
				$data->result_data['config_section_is_obligatory'] = $data->getValueAsBool();
				break;
			case 'data_path':
				$data->result_data['config_data_path'] = $data->getValueAsString();
				break;
			default:
				throw new Reflection_Exception(
					'Unknown definition! Class: \''.$data->getCurrentHierarchyClassReflection()->getName(
					).'\', definition: \''.$data->getDefinition().'\' ',
					Reflection_Exception::CODE_UNKNOWN_CLASS_DEFINITION
				);
		}

	}

	/**
	 * @param Reflection_ParserData $data
	 */
	public static function parsePropertyDocComment( Reflection_ParserData $data )
	{

		switch( $data->getKey() ) {
			case 'config_factory_class_name':
			case 'item_class_name':
				$data->setResultDataPropertyValue( 'config_properties_definition', $data->getValueAsClassName() );
				break;
			case 'form_field_get_select_options_callback':
				$data->setResultDataPropertyValue( 'config_properties_definition', $data->getValueAsCallback() );
				break;
			default:
				$data->setResultDataPropertyValue( 'config_properties_definition', $data->getValue() );
				break;
		}


	}

}