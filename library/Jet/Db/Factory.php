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
	 * @param Db_Config $config
	 * @param string $connection_name
	 * @param array $config_data (optional)
	 *
	 * @return Db_Connection_Config_Abstract
	 */
	public static function getConnectionConfigInstance(Db_Config $config, $connection_name="", array $config_data=array() ){
		$default_class_name = static::$connection_class_prefix."Config_".static::$connection_adapter;

		$config_class = static::getClassName( $default_class_name );
		$instance = new $config_class( $config, $config_data );
		static::checkInstance( $default_class_name, $instance);
		return $instance;
	}

	/**
	 *
	 * @param Db_Connection_Config_Abstract $adapter_config
	 *
	 * @return Db_Connection_Abstract
	 */
	public static function getConnectionInstance( Db_Connection_Config_Abstract $adapter_config ){
		$default_class_name = static::$connection_class_prefix.static::$connection_adapter;

		$adapter_class = static::getClassName( $default_class_name );
		$instance = new $adapter_class( $adapter_config );

		//TODO: static::checkInstance($default_class_name, $instance);
		return $instance;
	}
}