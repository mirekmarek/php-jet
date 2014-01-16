<?php
/**
 *
 *
 *
 * @see Factory
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Factory
 */
namespace Jet;

class DataModel_Factory extends Factory {
	/**
	 * @var string
	 */
	protected static $backend_class_name_prefix = 'Jet\\DataModel_Backend_';

	/**
	 * @var string
	 */
	protected static $histiory_backend_class_name_prefix = 'Jet\\DataModel_History_Backend_';

	/**
	 * @var string
	 */
	protected static $cache_backend_class_name_prefix = 'Jet\\DataModel_Cache_Backend_';

	/**
	 * @var string
	 */
	protected static $property_definition_class_name_prefix = 'Jet\\DataModel_Definition_Property_';

	/**
	 * @param string $backend_class_name_prefix
	 */
	public static function setBackendClassNamePrefix($backend_class_name_prefix) {
		static::$backend_class_name_prefix = $backend_class_name_prefix;
	}

	/**
	 * @return string
	 */
	public static function getBackendClassNamePrefix() {
		return static::$backend_class_name_prefix;
	}

	/**
	 * @param string $cache_backend_class_name_prefix
	 */
	public static function setCacheBackendClassNamePrefix($cache_backend_class_name_prefix) {
		static::$cache_backend_class_name_prefix = $cache_backend_class_name_prefix;
	}

	/**
	 * @return string
	 */
	public static function getCacheBackendClassNamePrefix() {
		return static::$cache_backend_class_name_prefix;
	}

	/**
	 * @param string $histiory_backend_class_name_prefix
	 */
	public static function setHistioryBackendClassNamePrefix($histiory_backend_class_name_prefix) {
		static::$histiory_backend_class_name_prefix = $histiory_backend_class_name_prefix;
	}

	/**
	 * @return string
	 */
	public static function getHistioryBackendClassNamePrefix() {
		return static::$histiory_backend_class_name_prefix;
	}

	/**
	 * @param string $property_definition_class_name_prefix
	 */
	public static function setPropertyDefinitionClassNamePrefix($property_definition_class_name_prefix) {
		static::$property_definition_class_name_prefix = $property_definition_class_name_prefix;
	}

	/**
	 * @return string
	 */
	public static function getPropertyDefinitionClassNamePrefix() {
		return static::$property_definition_class_name_prefix;
	}


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
			!isset($definition_data['type']) ||
			!$definition_data['type']
		) {
			throw new DataModel_Exception(
				'Property '.$data_model->getClassName().'::'.$name.': \'type\' parameter is not defined ... ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$default_class_name = static::$property_definition_class_name_prefix.$definition_data['type'];

		$class_name =  static::getClassName( $default_class_name );
		$instance = new $class_name( $data_model, $name, $definition_data );
		static::checkInstance( $default_class_name, $instance );
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
		$default_class_name = static::$backend_class_name_prefix.$type.'_Config';

		$class_name =  static::getClassName( $default_class_name );
		$instance = new $class_name($soft_mode);
		static::checkInstance( $default_class_name, $instance );
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
		$default_class_name = static::$backend_class_name_prefix.$type;

		$class_name =  static::getClassName( $default_class_name );
		$instance = new $class_name( $backend_config );
		static::checkInstance( $default_class_name, $instance );
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
		$default_class_name = static::$histiory_backend_class_name_prefix.$type.'_Config';

		$class_name =  static::getClassName( $default_class_name );
		$instance = new $class_name( $soft_mode );
		static::checkInstance( $default_class_name, $instance );
		return $instance;
	}

	/**
	 * Returns instance of DataModel History Backend class @see Factory
	 *
	 * @param string $type
	 * @param DataModel_History_Backend_Config_Abstract $config
	 *
	 * @return DataModel_History_Backend_Abstract
	 */
	public static function getHistoryBackendInstance( $type, DataModel_History_Backend_Config_Abstract $config ) {
		$default_class_name = static::$histiory_backend_class_name_prefix.$type;

		$class_name =  static::getClassName( $default_class_name );

		/**
		 * @var DataModel_History_Backend_Abstract $instance
		 */
		$instance = new $class_name( $config );
		static::checkInstance( $default_class_name, $instance );
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
		$default_class_name = static::$cache_backend_class_name_prefix.$type.'_Config';

		$class_name =  static::getClassName( $default_class_name );
		$instance = new $class_name( $soft_mode );
		static::checkInstance( $default_class_name, $instance );
		return $instance;
	}

	/**
	 * Returns instance of DataModel Class Backend class @see Factory
	 *
	 * @param string $type
	 * @param DataModel_Cache_Backend_Config_Abstract $config
	 *
	 * @return DataModel_History_Backend_Abstract
	 */
	public static function getCacheBackendInstance( $type, DataModel_Cache_Backend_Config_Abstract $config ) {

		$default_class_name = static::$cache_backend_class_name_prefix.$type;

		$class_name =  static::getClassName( $default_class_name );
		/**
		 * @var DataModel_History_Backend_Abstract $instance
		 */
		$instance = new $class_name( $config );
		static::checkInstance( $default_class_name, $instance );

		return $instance;
	}

}