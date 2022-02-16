<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use ReflectionClass;

/**
 *
 */
class Config_Definition_Config extends BaseObject
{
	/**
	 * @var string
	 */
	protected string $class_name = '';

	/**
	 * @var ReflectionClass
	 */
	protected ReflectionClass $class_reflection;

	/**
	 * @var array
	 */
	protected array $class_arguments = [];

	/**
	 * @var string
	 */
	protected string $name = '';

	/**
	 * @var Config_Definition_Property[]
	 */
	protected array $properties_definition = [];

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

		$this->class_arguments = Attributes::getClassDefinition( $this->class_reflection, Config_Definition::class );

		$this->name = $this->getClassArgument( 'name' );
		if( !$this->name ) {
			throw new DataModel_Exception(
				'Config Class \'' . $this->class_name . '\' does not have name! Please define attribute #[Config_Definition(name: \'some_name\')] ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$properties_definition_data = Attributes::getClassPropertyDefinition( $this->class_reflection, Config_Definition::class );

		if(
		!$properties_definition_data
		) {
			throw new Config_Exception(
				'Configuration \'' . $this->class_name . '\' does not have any properties defined!',
				Config_Exception::CODE_DEFINITION_NONSENSE
			);
		}

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

			$definition_class_name = Factory_Config::getPropertyDefinitionClassName( $definition_data['type'] );

			unset( $definition_data['type'] );

			$property = new $definition_class_name( $this->class_name, $property_name, $definition_data );

			$this->properties_definition[$property_name] = $property;
		}

	}


	/**
	 * @param string $argument
	 * @param mixed|string $default_value
	 *
	 * @return mixed
	 */
	protected function getClassArgument( string $argument, mixed $default_value = '' ): mixed
	{
		return $this->class_arguments[$argument] ?? $default_value;
	}


	/**
	 * @return string
	 */
	public function getClassName(): string
	{
		return $this->class_name;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}


	/**
	 * @return Config_Definition_Property[]
	 */
	public function getPropertiesDefinition(): array
	{
		return $this->properties_definition;
	}

}