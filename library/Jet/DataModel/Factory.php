<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
	protected static $backend_class_name_prefix = __NAMESPACE__.'\DataModel_Backend_';

	/**
	 * @var string
	 */
	protected static $property_definition_class_name_prefix = __NAMESPACE__.'\DataModel_Definition_Property_';

	/**
	 * @return string
	 */
	public static function getPropertyDefinitionClassNamePrefix()
	{
		return static::$property_definition_class_name_prefix;
	}

	/**
	 * @param string $property_definition_class_name_prefix
	 */
	public static function setPropertyDefinitionClassNamePrefix( $property_definition_class_name_prefix )
	{
		static::$property_definition_class_name_prefix = $property_definition_class_name_prefix;
	}


	/**
	 *
	 * @param string $data_model_class_name
	 * @param string $name
	 * @param array  $definition_data
	 *
	 * @throws DataModel_Exception
	 * @return DataModel_Definition_Property
	 */
	public static function getPropertyDefinitionInstance( $data_model_class_name, $name, $definition_data )
	{
		if(
			!isset( $definition_data['type'] ) ||
			!$definition_data['type']
		) {
			throw new DataModel_Exception(
				'Property '.$data_model_class_name.'::'.$name.': \'type\' parameter is not defined ... ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$class_name = static::getPropertyDefinitionClassNamePrefix().$definition_data['type'];

		return new $class_name( $data_model_class_name, $name, $definition_data );
	}

	/**
	 * @return string
	 */
	public static function getBackendClassNamePrefix()
	{
		return static::$backend_class_name_prefix;
	}

	/**
	 * @param string $backend_class_name_prefix
	 */
	public static function setBackendClassNamePrefix( $backend_class_name_prefix )
	{
		static::$backend_class_name_prefix = $backend_class_name_prefix;
	}

	/**
	 *
	 * @param string $type
	 * @param bool   $soft_mode @see Config
	 *
	 * @return DataModel_Backend_Config
	 */
	public static function getBackendConfigInstance( $type, $soft_mode = false )
	{
		$class_name = static::getBackendClassNamePrefix().$type.'_Config';

		return new $class_name( $soft_mode );
	}

	/**
	 * Returns instance of DataModel Backend class
	 *
	 * @param string                            $type
	 * @param DataModel_Backend_Config $backend_config
	 *
	 * @return DataModel_Backend
	 */
	public static function getBackendInstance( $type, DataModel_Backend_Config $backend_config )
	{
		$class_name = static::getBackendClassNamePrefix().$type;

		return new $class_name( $backend_config );
	}


}