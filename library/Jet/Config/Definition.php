<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Config_Definition extends BaseObject implements Reflection_ParserInterface, BaseObject_Cacheable_Interface
{
	use BaseObject_Cacheable_Trait;

	/**
	 * @param string $class_name
	 *
	 * @return Config_Definition_Config
	 */
	public static function get( $class_name )
	{
		if( static::getCacheLoadEnabled() ) {

			$loader = static::$cache_loader;
			$definition = $loader( $class_name );
			if($definition) {
				return $definition;
			}
		}

		$definition = new Config_Definition_Config( $class_name );

		if( static::getCacheSaveEnabled() ) {

			$saver = static::$cache_saver;
			$saver( $class_name, $definition );
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