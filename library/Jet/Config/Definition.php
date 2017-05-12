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
class Config_Definition extends BaseObject implements BaseObject_Reflection_ParserInterface
{

	/**
	 * @param string $class_name
	 *
	 * @return Config_Definition_Config
	 */
	public static function getDefinition( $class_name )
	{

		$file_path = JET_CONFIG_DEFINITION_CACHE_PATH.str_replace( '\\', '__', $class_name.'.php' );

		if( JET_CONFIG_DEFINITION_CACHE_LOAD ) {

			if( IO_File::exists( $file_path ) ) {
				/** @noinspection PhpIncludeInspection */
				$definition = require $file_path;

				return $definition;
			}
		}

		$definition = new Config_Definition_Config( $class_name );

		if( JET_CONFIG_DEFINITION_CACHE_SAVE ) {
			/** @noinspection PhpUsageOfSilenceOperatorInspection */
			IO_File::write( $file_path, '<?php return '.@var_export( $definition, true ).';' );
		}


		return $definition;
	}


	/**
	 * @param BaseObject_Reflection_ParserData $data
	 *
	 * @throws BaseObject_Reflection_Exception
	 */
	public static function parseClassDocComment( BaseObject_Reflection_ParserData $data )
	{

		switch( $data->getKey() ) {
			case 'section_is_obligatory':
				$data->result_data['config_section_is_obligatory'] = $data->getValueAsBool();
				break;
			case 'data_path':
				$data->result_data['config_data_path'] = $data->getValueAsString();
				break;
			default:
				throw new BaseObject_Reflection_Exception(
					'Unknown definition! Class: \''.$data->getCurrentHierarchyClassReflection()->getName(
					).'\', definition: \''.$data->getDefinition().'\' ',
					BaseObject_Reflection_Exception::CODE_UNKNOWN_CLASS_DEFINITION
				);
		}

	}

	/**
	 * @param BaseObject_Reflection_ParserData $data
	 */
	public static function parsePropertyDocComment( BaseObject_Reflection_ParserData $data )
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