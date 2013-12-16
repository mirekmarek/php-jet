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
	protected static $adapter_class_prefix = "Jet\\Db_Adapter_";

	/**
	 * @param string $adapter_class_prefix
	 */
	public static function setAdapterClassPrefix($adapter_class_prefix) {
		self::$adapter_class_prefix = $adapter_class_prefix;
	}

	/**
	 * @return string
	 */
	public static function getAdapterClassPrefix() {
		return self::$adapter_class_prefix;
	}


	/**
	 *
	 * @param Db_Config $config
	 * @param string $adapter_name
	 *
	 * @param array $config_data (optional)
	 *
	 * @return Db_Adapter_Config_Abstract
	 */
	public static function getAdapterConfigInstance(Db_Config $config, $adapter_name, array $config_data=array() ){
		$default_class_name = static::$adapter_class_prefix.$adapter_name."_Config";

		$config_class = static::getClassName( $default_class_name );
		$instance = new $config_class( $config, $config_data );
		static::checkInstance( $default_class_name, $instance);
		return $instance;
	}

	/**
	 *
	 * @param string $adapter_name
	 * @param Db_Adapter_Config_Abstract $adapter_config
	 *
	 * @return Db_Adapter_Abstract
	 */
	public static function getAdapterInstance($adapter_name, Db_Adapter_Config_Abstract $adapter_config ){
		$default_class_name = static::$adapter_class_prefix.$adapter_name;

		$adapter_class = static::getClassName( $default_class_name );
		$instance = new $adapter_class( $adapter_config );
		static::checkInstance($default_class_name, $instance);
		return $instance;
	}
}