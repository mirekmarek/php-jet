<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package Config
 */
namespace Jet;

require_once "_mock/Jet/Config/ConfigTestAdapterMainMock/AdapterA/Config.php";
require_once "_mock/Jet/Config/ConfigTestAdapterMainMock/AdapterB/Config.php";

class ConfigTestAdapterMainMock extends Config {
	protected static $__config_data_path = "/section/subsection";


	protected static $__config_properties_definition = array(
		"connections" => array(
			"type" => self::TYPE_ADAPTER_CONFIG,
			"data_path" => "connections",
			"adapter_type_key" => "adapter",
			"config_factory_class_name" => "Jet\\ConfigTestAdapterMainMock",
			"config_factory_method_name" => "getAdapterConfigInstance"
		)
	);

	/**
	 * Database adapters and configurations for general usage
	 *
	 * @var Config_Definition_Property_AdapterConfig
	 */
	protected $connections;

	/**
	 */
	public function __construct() {
	}

	public function testInit( $config_file_path, $soft_mode=false ) {
		$this->config_file_path = $config_file_path;
		$this->soft_mode = (bool)$soft_mode;

		$this->setData( $this->readConfigData($config_file_path) );
	}


	public function testSetConfigFilePath($config_file_path) {
		$this->config_file_path = $config_file_path;
	}


	/**
	 * Get connection configuration
	 *
	 * @param $connection_name
	 *
	 * @throws Db_Exception
	 * @return ConfigTestAdapterMainMock_Config_Abstract
	 */
	public function getConnection($connection_name){
		return $this->connections->getAdapterConfiguration( $connection_name );
	}

	/**
	 * @return ConfigTestAdapterMainMock_Config_Abstract[]
	 */
	public function getConnections() {
		return $this->connections->getAllAdaptersConfiguration();
	}

	/**
	 * @param $connection_name
	 * @param ConfigTestAdapterMainMock_Config_Abstract $connection_configuragion
	 *
	 */
	public function addConnection( $connection_name, ConfigTestAdapterMainMock_Config_Abstract $connection_configuragion ) {
		$this->connections->addAdapterConfiguration( $connection_name, $connection_configuragion );
	}

	/**
	 * @param $connection_name
	 *
	 */
	public function deleteConnection( $connection_name ) {
		$this->connections->deleteAdapterConfiguration( $connection_name );
	}

	public function toArray() {
		return $this->connections->toArray();
	}

	/**
	 *
	 * @param ConfigTestAdapterMainMock $config
	 * @param string $adapter_name
	 *
	 * @param array $config_data (optional)
	 *
	 * @return ConfigTestAdapterMainMock_Config_Abstract
	 */
	public static function getAdapterConfigInstance(ConfigTestAdapterMainMock $config, $adapter_name, array $config_data=array() ){
		$config_class = "Jet\\ConfigTestAdapterMainMock_".$adapter_name."_Config";

		$adapter_config = new $config_class( $config, $config_data );

		return $adapter_config;
	}


}