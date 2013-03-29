<?php
/**
 *
 *
 *
 * @see Factory
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Factory
 */
namespace Jet;

class DataModel_Factory extends Factory {
	const BASE_DATA_MODEL_BACKEND_CLASS_NAME_PREFIX = "Jet\\DataModel_Backend_";
	const BASE_DATA_MODEL_HISTORY_BACKEND_CLASS_NAME_PREFIX = "Jet\\DataModel_History_Backend_";
	const BASE_DATA_MODEL_CACHE_BACKEND_CLASS_NAME_PREFIX = "Jet\\DataModel_Cache_Backend_";
	const BASE_PROPERTY_DEFINITION_CLASS_NAME_PREFIX = "Jet\\DataModel_Definition_Property_";

	/**
	 * Returns instance of Property class
	 * @see Factory
	 *
	 * @param DataModel_Definition_Model_Abstract $data_model
	 * @param string $name
	 * @param array $definition_data
	 *
	 * @throws DataModel_Exception
	 * @return DataModel_Definition_Property_Abstract
	 */
	public static function getPropertyDefinitionInstance( DataModel_Definition_Model_Abstract $data_model, $name, $definition_data ) {
		if(
			!isset($definition_data["type"]) ||
			!$definition_data["type"]
		) {
			throw new DataModel_Exception(
				"Property {$data_model->getClassName()}::{$name}: 'type' parameter is not defined ... ",
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$class_name =  static::getClassName( static::BASE_PROPERTY_DEFINITION_CLASS_NAME_PREFIX.$definition_data["type"] );
		$instance = new $class_name( $data_model, $name, $definition_data );
		static::checkInstance( static::BASE_PROPERTY_DEFINITION_CLASS_NAME_PREFIX.$definition_data["type"], $instance );
		return $instance;
	}


	/**
	 * Returns instance of DataModel Backend Config class
	 *
	 * @see Factory
	 *
	 * @param string $type
	 * @param bool $soft_mode @see Config
	 *
	 * @return DataModel_Backend_Config_Abstract
	 */
	public static function getBackendConfigInstance( $type, $soft_mode=false ) {
		$class_name =  Factory::getClassName( self::BASE_DATA_MODEL_BACKEND_CLASS_NAME_PREFIX.$type."_Config" );
		$instance = new $class_name($soft_mode);
		self::checkInstance(self::BASE_DATA_MODEL_BACKEND_CLASS_NAME_PREFIX.$type."_Config", $instance);
		return $instance;
	}

	/**
	 * Returns instance of DataModel Backend class
	 * @see Factory
	 *
	 * @param string $type
	 * @param DataModel_Backend_Config_Abstract $backend_config
	 *
	 * @return DataModel_Backend_Abstract
	 */
	public static function getBackendInstance( $type, DataModel_Backend_Config_Abstract $backend_config ) {
		$class_name =  Factory::getClassName( self::BASE_DATA_MODEL_BACKEND_CLASS_NAME_PREFIX.$type );
		$instance = new $class_name( $backend_config );
		self::checkInstance(self::BASE_DATA_MODEL_BACKEND_CLASS_NAME_PREFIX.$type, $instance);
		return $instance;
	}

	/**
	 * Returns instance of DataModel History Backend Config class
	 *
	 * @see Factory
	 *
	 * @param string $type
	 * @param bool $soft_mode @see Config
	 *
	 * @return DataModel_History_Backend_Config_Abstract
	 */
	public static function getHistoryBackendConfigInstance( $type, $soft_mode=false ) {
		$class_name =  Factory::getClassName( self::BASE_DATA_MODEL_HISTORY_BACKEND_CLASS_NAME_PREFIX.$type."_Config" );
		$instance = new $class_name($soft_mode);
		self::checkInstance( self::BASE_DATA_MODEL_HISTORY_BACKEND_CLASS_NAME_PREFIX.$type."_Config", $instance );
		return $instance;
	}

	/**
	 * Returns instance of DataModel History Backend class @see Factory
	 *
	 * @param string $type
	 * @param DataModel $data_model
	 *
	 * @return DataModel_History_Backend_Abstract
	 */
	public static function getHistoryBackendInstance( $type, DataModel $data_model ) {
		$class_name =  Factory::getClassName( self::BASE_DATA_MODEL_HISTORY_BACKEND_CLASS_NAME_PREFIX.$type );
		$instance = new $class_name( $data_model );
		self::checkInstance(self::BASE_DATA_MODEL_HISTORY_BACKEND_CLASS_NAME_PREFIX.$type, $instance);
		return $instance;
	}


	/**
	 * Returns instance of DataModel Cache Backend Config class
	 *
	 * @see Factory
	 *
	 * @param string $type
	 * @param bool $soft_mode @see Config
	 *
	 * @return DataModel_Cache_Backend_Config_Abstract
	 */
	public static function getCacheBackendConfigInstance( $type, $soft_mode=false ) {
		$class_name =  Factory::getClassName( self::BASE_DATA_MODEL_CACHE_BACKEND_CLASS_NAME_PREFIX.$type."_Config" );
		$instance = new $class_name($soft_mode);
		self::checkInstance(self::BASE_DATA_MODEL_CACHE_BACKEND_CLASS_NAME_PREFIX.$type."_Config", $instance);
		return $instance;
	}

	/**
	 * Returns instance of DataModel Class Backend class @see Factory
	 *
	 * @param string $type
	 * @param DataModel $data_model
	 *
	 * @return DataModel_History_Backend_Abstract
	 */
	public static function getCacheBackendInstance( $type, DataModel $data_model ) {
		$class_name =  Factory::getClassName( self::BASE_DATA_MODEL_CACHE_BACKEND_CLASS_NAME_PREFIX.$type );
		$instance = new $class_name( $data_model );
		self::checkInstance( self::BASE_DATA_MODEL_CACHE_BACKEND_CLASS_NAME_PREFIX.$type, $instance );
		return $instance;
	}

}