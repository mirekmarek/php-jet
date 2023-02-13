<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Factory_DataModel
{

	protected static array $backend_class_names = [
		DataModel_Backend::TYPE_MYSQL  => DataModel_Backend_MySQL::class,
		DataModel_Backend::TYPE_SQLITE => DataModel_Backend_SQLite::class,
		DataModel_Backend::TYPE_PGSQL  => DataModel_Backend_PgSQL::class,
	];

	protected static array $backend_config_class_names = [
		DataModel_Backend::TYPE_MYSQL  => DataModel_Backend_MySQL_Config::class,
		DataModel_Backend::TYPE_SQLITE => DataModel_Backend_SQLite_Config::class,
		DataModel_Backend::TYPE_PGSQL  => DataModel_Backend_PgSQL_Config::class,
	];

	protected static array $model_definition_class_names = [
		DataModel::MODEL_TYPE_MAIN         => DataModel_Definition_Model_Main::class,
		DataModel::MODEL_TYPE_RELATED_1TO1 => DataModel_Definition_Model_Related_1to1::class,
		DataModel::MODEL_TYPE_RELATED_1TON => DataModel_Definition_Model_Related_1toN::class,
	];

	protected static array $property_definition_class_names = [
		DataModel::TYPE_ID               => DataModel_Definition_Property_Id::class,
		DataModel::TYPE_ID_AUTOINCREMENT => DataModel_Definition_Property_IdAutoIncrement::class,
		DataModel::TYPE_STRING           => DataModel_Definition_Property_String::class,
		DataModel::TYPE_BOOL             => DataModel_Definition_Property_Bool::class,
		DataModel::TYPE_INT              => DataModel_Definition_Property_Int::class,
		DataModel::TYPE_FLOAT            => DataModel_Definition_Property_Float::class,
		DataModel::TYPE_LOCALE           => DataModel_Definition_Property_Locale::class,
		DataModel::TYPE_DATE             => DataModel_Definition_Property_Date::class,
		DataModel::TYPE_DATE_TIME        => DataModel_Definition_Property_DateTime::class,
		DataModel::TYPE_CUSTOM_DATA      => DataModel_Definition_Property_CustomData::class,
		DataModel::TYPE_DATA_MODEL       => DataModel_Definition_Property_DataModel::class,
	];


	/**
	 * @param string $type
	 * @return string
	 * @throws DataModel_Exception
	 */
	public static function getPropertyDefinitionClassName( string $type ) : string
	{
		if(!isset(static::$property_definition_class_names[$type])) {
			throw new DataModel_Exception(
				'Unknown property type \''.$type.'\'',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		return static::$property_definition_class_names[$type];
	}

	/**
	 * @param string $type
	 * @param string $class_name
	 */
	public static function setPropertyDefinitionClassName( string $type, string $class_name ) : void
	{
		static::$property_definition_class_names[$type] = $class_name;
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

		$class_name = static::getPropertyDefinitionClassName( $definition_data['type'] );

		return new $class_name( $data_model_class_name, $name, $definition_data );
	}



	/**
	 * @param string $type
	 * @return string
	 * @throws DataModel_Exception
	 */
	public static function getModelDefinitionClassName( string $type ) : string
	{
		if(!isset(static::$model_definition_class_names[$type])) {
			throw new DataModel_Exception(
				'Unknown model type \''.$type.'\'',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		return static::$model_definition_class_names[$type];
	}

	/**
	 * @param string $type
	 * @param string $class_name
	 */
	public static function setModelDefinitionClassName( string $type, string $class_name ) : void
	{
		static::$model_definition_class_names[$type] = $class_name;
	}


	/**
	 * @param string $type
	 * @param string $class_name
	 *
	 * @return DataModel_Definition_Model|DataModel_Definition_Model_Related
	 * @throws DataModel_Exception
	 */
	public static function getModelDefinitionInstance( string $type, string $class_name ) : DataModel_Definition_Model|DataModel_Definition_Model_Related
	{
		$cn = static::getModelDefinitionClassName( $type );

		return new $cn( $class_name );
	}


	/**
	 * @param string $type
	 * @return string
	 */
	public static function getBackendConfigClassName( string $type ) : string
	{
		if(!isset(static::$backend_config_class_names[$type])) {
			throw new DataModel_Exception(
				'Unknown backend type \''.$type.'\'',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		return static::$backend_config_class_names[$type];
	}

	/**
	 * @param string $type
	 * @param string $class_name
	 */
	public static function setBackendConfigClassName( string $type, string $class_name ) : void
	{
		static::$backend_config_class_names[$type] = $class_name;
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
		$class_name = static::getBackendConfigClassName( $type );

		return new $class_name( $data );
	}


	/**
	 * @param string $type
	 * @return string
	 */
	public static function getBackendClassName( string $type ) : string
	{
		if(!isset(static::$backend_class_names[$type])) {
			throw new DataModel_Exception(
				'Unknown backend type \''.$type.'\'',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		return static::$backend_class_names[$type];
	}

	/**
	 * @param string $type
	 * @param string $class_name
	 */
	public static function setBackendClassName( string $type, string $class_name ) : void
	{
		static::$backend_class_names[$type] = $class_name;
	}


	/**
	 * Returns instance of DataModel Backend class
	 *
	 * @param string $type
	 * @param ?DataModel_Backend_Config $backend_config
	 *
	 * @return DataModel_Backend
	 */
	public static function getBackendInstance( string $type, ?DataModel_Backend_Config $backend_config=null ): DataModel_Backend
	{
		$class_name = static::getBackendClassName( $type );

		return new $class_name( $backend_config );
	}
}