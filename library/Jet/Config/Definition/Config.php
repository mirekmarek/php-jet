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
class Config_Definition_Config extends BaseObject
{
	/**
	 *
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
	 * @var Config_Definition_Property[]
	 */
	protected $properties_definition = [];

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

		$this->data_path = Reflection::get( $class_name, 'config_data_path', '' );

		$this->section_is_obligatory = Reflection::get( $class_name, 'config_section_is_obligatory', true );
		$propertied_definition_data = Reflection::get( $class_name, 'config_properties_definition', [] );

		$this->properties_definition = [];
		foreach( $propertied_definition_data as $property_name => $definition_data ) {
			if(
				!isset( $definition_data['type'] ) ||
				!$definition_data['type']
			) {
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
	 * @return Config_Definition_Property[]
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