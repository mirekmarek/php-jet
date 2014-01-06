<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Db
 */
namespace Jet;

class Db_Factory extends Factory {

	/**
	 * @var string
	 */
	protected static $connection_class_prefix = "Jet\\Db_Connection_";

	/**
	 * @var string
	 */
	protected static $connection_adapter = "PDO";

	/**
	 * @param string $adapter_class_prefix
	 */
	public static function setConnectionClassPrefix($adapter_class_prefix) {
		self::$connection_class_prefix = $adapter_class_prefix;
	}

	/**
	 * @return string
	 */
	public static function getConnectionClassPrefix() {
		return self::$connection_class_prefix;
	}

	/**
	 * @param string $connection_adapter
	 */
	public static function setConnectionAdapter($connection_adapter) {
		self::$connection_adapter = $connection_adapter;
	}

	/**
	 * @return string
	 */
	public static function getConnectionAdapter() {
		return self::$connection_adapter;
	}


	/**
	 *
	 * @param array $config_data (optional)
	 * @param Db_Config $config (optional)
	 *
	 * @return Db_Connection_Config_Abstract
	 */
	public static function getConnectionConfigInstance(array $config_data=array(), Db_Config $config=null ){
		$default_class_name = static::$connection_class_prefix."Config_".static::$connection_adapter;

		$config_class = static::getClassName( $default_class_name );

		$instance = new $config_class( $config_data, $config );
		static::checkInstance( $default_class_name, $instance);
		return $instance;
	}

	/**
	 *
	 * @param Db_Connection_Config_Abstract $connection_config
	 *
	 * @throws Factory_Exception
	 *
	 * @return Db_Connection_Abstract
	 */
	public static function getConnectionInstance( Db_Connection_Config_Abstract $connection_config ){
		$default_class_name = static::$connection_class_prefix.static::$connection_adapter;

		$adapter_class = static::getClassName( $default_class_name );
		$instance = new $adapter_class( $connection_config );

		//HACK: We do not have multiple inheritance in PHP :-(
		//static::checkInstance($default_class_name, $instance);
		/**
		 * @var Object $default_class
		 */
		$required_class = $default_class_name::getFactoryMustBeInstanceOfClassName();
		if(!$required_class){
			throw new Factory_Exception(
				$default_class_name."::\$__factory_must_be_instance_of_class_name must be set for Jet\\Factory::checkInstance().",
				Factory_Exception::CODE_MISSING_INSTANCEOF_CLASS_NAME
			);
		}

		if(!($instance instanceof $required_class) ) {
			throw new Factory_Exception(
				"Class " . get_class($instance) . " must be descendant of {$required_class}.",
				Factory_Exception::CODE_INVALID_CLASS_INSTANCE
			);
		}

		return $instance;
	}
}