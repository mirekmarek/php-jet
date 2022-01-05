<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use ReflectionClass;

/**
 *
 */
class Config_Definition_Config_Section extends Config_Definition_Config
{

	/**
	 * @param string $class_name
	 *
	 * @throws Config_Exception
	 */
	public function __construct( string $class_name = '' )
	{
		if( !$class_name ) {
			return;
		}

		$this->class_name = $class_name;
		$this->class_reflection = new ReflectionClass( $class_name );

		$properties_definition_data = Attributes::getClassPropertyDefinition( $this->class_reflection, Config_Definition::class );

		if( !$properties_definition_data ) {
			throw new Config_Exception(
				'Configuration \'' . $this->class_name . '\' does not have any properties defined!',
				Config_Exception::CODE_DEFINITION_NONSENSE
			);
		}


		$this->properties_definition = [];
		foreach( $properties_definition_data as $property_name => $definition_data ) {
			if(
				!isset( $definition_data['type'] ) ||
				!$definition_data['type']
			) {
				throw new Config_Exception(
					'Property ' . get_class( $this ) . '::' . $property_name . ': \'type\' parameter is not defined.',
					Config_Exception::CODE_CONFIG_CHECK_ERROR
				);

			}

			$class_name = __NAMESPACE__ . '\\' . static::BASE_PROPERTY_DEFINITION_CLASS_NAME . '_' . $definition_data['type'];

			unset( $definition_data['type'] );

			$property = new $class_name( $class_name, $property_name, $definition_data );

			$this->properties_definition[$property_name] = $property;

		}


	}

}