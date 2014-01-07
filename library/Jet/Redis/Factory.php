<?php
/**
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Redis
 */
namespace Jet;

class Redis_Factory extends Factory {

	/**
	 * @var string
	 */
	protected static $connection_class_prefix = "Jet\\Redis_Connection_";

	/**
	 * @var string
	 */
	protected static $connection_adapter = "Default";

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
	 * @param Redis_Config $config (optional)
	 *
	 * @return Redis_Connection_Config_Abstract
	 */
	public static function getConnectionConfigInstance(array $config_data=array(), Redis_Config $config=null ){
		$default_class_name = static::$connection_class_prefix."Config_".static::$connection_adapter;

		$config_class = static::getClassName( $default_class_name );

		$instance = new $config_class( $config_data, $config );
		static::checkInstance( $default_class_name, $instance);
		return $instance;
	}

	/**
	 *
	 * @param Redis_Connection_Config_Abstract $connection_config
	 *
	 * @throws Factory_Exception
	 *
	 * @return Redis_Connection_Abstract
	 */
	public static function getConnectionInstance( Redis_Connection_Config_Abstract $connection_config ){
		$default_class_name = static::$connection_class_prefix.static::$connection_adapter;

		$adapter_class = static::getClassName( $default_class_name );
		$instance = new $adapter_class( $connection_config );

		static::checkInstance($default_class_name, $instance);

		return $instance;
	}
}