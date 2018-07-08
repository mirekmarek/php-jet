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
class Config_Definition_Config_Section extends Config_Definition_Config
{

	/** @noinspection PhpMissingParentConstructorInspection */
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

}