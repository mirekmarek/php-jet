<?php
/**
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Db
 */
namespace Jet;

class Db_Factory extends Factory {

	const DEFAULT_CLASS_PREFIX = "Jet\\Db_Adapter_";

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
		$config_class = self::getClassName(static::DEFAULT_CLASS_PREFIX.$adapter_name."_Config");

		$instance = new $config_class( $config, $config_data );
		self::checkInstance($config_class, $instance);

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
		$adapter_class = self::getClassName(static::DEFAULT_CLASS_PREFIX.$adapter_name);

		$instance = new $adapter_class($adapter_config);
		self::checkInstance($adapter_class, $instance);

		return $instance;
	}
}