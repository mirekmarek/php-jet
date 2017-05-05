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
class Config_Definition_Config extends BaseObject
{
	/**
	 * Property definition classes names prefix
	 */
	const BASE_PROPERTY_DEFINITION_CLASS_NAME = 'Config_Definition_Property';

	/**
	 * @var string
	 */
	protected $class_name = '';


	/**
	 * @var string
	 */
	protected $data_path = '';

	/**
	 * @var bool
	 */
	protected $section_is_obligatory = true;

	/**
	 * @var Config_Definition_Property_Abstract[]
	 */
	protected $properties_definition = [];

	/**
	 * @param string $class_name
	 *
	 * @throws Config_Exception
	 */
	public function __construct( $class_name = '' )
	{
		if( !$class_name ) {
			return;
		}

		$this->class_name = $class_name;

		$this->data_path = BaseObject_Reflection::get( $class_name, 'config_data_path', '' );

		$this->section_is_obligatory = BaseObject_Reflection::get( $class_name, 'config_section_is_obligatory', true );
		$propertied_definition_data = BaseObject_Reflection::get( $class_name, 'config_properties_definition', [] );

		$this->properties_definition = [];
		foreach( $propertied_definition_data as $property_name => $definition_data ) {
			if( !isset( $definition_data['type'] )||!$definition_data['type'] ) {
				throw new Config_Exception(
					'Property '.get_class( $this ).'::'.$property_name.': \'type\' parameter is not defined.',
					Config_Exception::CODE_CONFIG_CHECK_ERROR
				);

			}

			$class_name = __NAMESPACE__.'\\'.static::BASE_PROPERTY_DEFINITION_CLASS_NAME.'_'.$definition_data['type'];

			unset( $definition_data['type'] );

			$property = new $class_name( $class_name, $property_name, $definition_data );

			$this->properties_definition[$property_name] = $property;

		}


	}

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

		$definition = new static( $class_name );

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

	/**
	 * @param array $data
	 *
	 * @return static
	 */
	public static function __set_state( array $data )
	{
		$i = new static();

		foreach( $data as $key => $val ) {
			$i->{$key} = $val;
		}

		return $i;
	}

	/**
	 * @return string
	 */
	public function getClassName()
	{
		return $this->class_name;
	}

	/**
	 * @return string
	 */
	public function getDataPath()
	{
		return $this->data_path;
	}

	/**
	 * @return Config_Definition_Property_Abstract[]
	 */
	public function getPropertiesDefinition()
	{
		return $this->properties_definition;
	}

	/**
	 * @return bool
	 */
	public function getSectionIsObligatory()
	{
		return $this->section_is_obligatory;
	}

}