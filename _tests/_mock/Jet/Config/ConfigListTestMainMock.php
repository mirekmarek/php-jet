<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package Config
 */
namespace Jet;

require_once "_mock/Jet/Config/ConfigListTestMainMock/AdapterA/Config.php";
require_once "_mock/Jet/Config/ConfigListTestMainMock/AdapterB/Config.php";

/**
 * Class ConfigListTestMainMock
 *
 * @JetConfig:data_path = '/section/subsection'
 */
class ConfigListTestMainMock extends Config {

	/**
	 * Database adapters and configurations for general usage
	 *
	 * @JetConfig:type = Jet\Config::TYPE_CONFIG_LIST
	 * @JetConfig:data_path = 'connections'
	 * @JetConfig:config_factory_class_name = 'ConfigListTestMainMock'
	 * @JetConfig:config_factory_method_name = 'getAdapterConfigInstance'
	 *
	 * @var Config_Definition_Property_ConfigList
	 */
	protected $connections = array();

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
	 * @return ConfigListTestMainMock_Config_Abstract
	 */
	public function getConfigurationListItem($connection_name){
		return $this->connections->getConfigurationListItem( $connection_name );
	}

	/**
	 * @return ConfigListTestMainMock_Config_Abstract[]
	 */
	public function getConnections() {
		return $this->connections->getAllConfigurationItems();
	}

	/**
	 * @param $connection_name
	 * @param ConfigListTestMainMock_Config_Abstract $connection_configuragion
	 *
	 */
	public function addConnection( $connection_name, ConfigListTestMainMock_Config_Abstract $connection_configuragion ) {
		$this->connections->addConfigurationItem( $connection_name, $connection_configuragion );
	}

	/**
	 * @param $connection_name
	 *
	 */
	public function deleteConnection( $connection_name ) {
		$this->connections->deleteConfigurationItem( $connection_name );
	}

	public function toArray() {
		return $this->connections->toArray();
	}

	/**
	 *
	 * @param array $config_data (optional)
	 * @param ConfigListTestMainMock $config (optional)
	 *
	 * @return ConfigListTestMainMock_Config_Abstract
	 */
	public static function getAdapterConfigInstance(array $config_data=array(), ConfigListTestMainMock $config ){
		$adapter_name = $config_data["adapter"];

		$config_class = "Jet\\ConfigListTestMainMock_".$adapter_name."_Config";

		$adapter_config = new $config_class( $config_data, $config );

		return $adapter_config;
	}


}