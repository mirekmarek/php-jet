<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class DataModel_Factory
{

	/**
	 * @var string
	 */
	protected static string $backend_class_name_prefix = __NAMESPACE__ . '\DataModel_Backend_';

	/**
	 * @var string
	 */
	protected static string $model_definition_class_name_prefix = __NAMESPACE__ . '\DataModel_Definition_Model_';


	/**
	 * @var string
	 */
	protected static string $property_definition_class_name_prefix = __NAMESPACE__ . '\DataModel_Definition_Property_';

	/**
	 * @return string
	 */
	public static function getModelDefinitionClassNamePrefix(): string
	{
		return self::$model_definition_class_name_prefix;
	}

	/**
	 * @param string $model_definition_class_name_prefix
	 */
	public static function setModelDefinitionClassNamePrefix( string $model_definition_class_name_prefix )
	{
		self::$model_definition_class_name_prefix = $model_definition_class_name_prefix;
	}


	/**
	 * @return string
	 */
	public static function getPropertyDefinitionClassNamePrefix(): string
	{
		return static::$property_definition_class_name_prefix;
	}

	/**
	 * @param string $property_definition_class_name_prefix
	 */
	public static function setPropertyDefinitionClassNamePrefix( string $property_definition_class_name_prefix )
	{
		static::$property_definition_class_name_prefix = $property_definition_class_name_prefix;
	}


	/**
	 *
	 * @param string $data_model_class_name
	 * @param string $name
	 * @param array $definition_data
	 *
	 * @return DataModel_Definition_Property
	 * @throws DataModel_Exception
	 */
	public static function getPropertyDefinitionInstance( string $data_model_class_name,
	                                                      string $name,
	                                                      array $definition_data ): DataModel_Definition_Property
	{
		if(
			!isset( $definition_data['type'] ) ||
			!$definition_data['type']
		) {
			throw new DataModel_Exception(
				'Property ' . $data_model_class_name . '::' . $name . ': \'type\' parameter is not defined ... ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$class_name = static::getPropertyDefinitionClassNamePrefix() . $definition_data['type'];

		return new $class_name( $data_model_class_name, $name, $definition_data );
	}

	/**
	 * @return string
	 */
	public static function getBackendClassNamePrefix(): string
	{
		return static::$backend_class_name_prefix;
	}

	/**
	 * @param string $backend_class_name_prefix
	 */
	public static function setBackendClassNamePrefix( string $backend_class_name_prefix ): void
	{
		static::$backend_class_name_prefix = $backend_class_name_prefix;
	}

	/**
	 *
	 * @param string $type
	 * @param array $data
	 *
	 * @return DataModel_Backend_Config
	 */
	public static function getBackendConfigInstance( string $type, array $data = [] ): DataModel_Backend_Config
	{
		$class_name = static::getBackendClassNamePrefix() . $type . '_Config';

		return new $class_name( $data );
	}

	/**
	 * Returns instance of DataModel Backend class
	 *
	 * @param string $type
	 * @param DataModel_Backend_Config $backend_config
	 *
	 * @return DataModel_Backend
	 */
	public static function getBackendInstance( string $type, DataModel_Backend_Config $backend_config ): DataModel_Backend
	{
		$class_name = static::getBackendClassNamePrefix() . $type;

		return new $class_name( $backend_config );
	}


}