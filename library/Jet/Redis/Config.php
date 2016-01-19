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
 * @subpackage Redis_Config
 */
namespace Jet;

/**
 * Class Redis_Config
 *
 * @JetConfig:data_path = 'redis'
 */
class Redis_Config extends Application_Config {

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:description = 'Default connection name for Redis::get() / Redis::getConnection()'
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'default'
	 * @JetConfig:form_field_label = 'Default connection:'
	 * @JetConfig:form_field_type = 'Select'
	 * @JetConfig:form_field_get_select_options_callback = ['Redis_Config', 'getConnectionsList']
	 *
	 * @var string
	 */
	protected $default_connection_name = 'default';


	/**
	 * @JetConfig:type = Config::TYPE_CONFIG_LIST
	 * @JetConfig:data_path = 'connections'
	 * @JetConfig:config_factory_class_name = 'Redis_Factory'
	 * @JetConfig:config_factory_method_name = 'getConnectionConfigInstance'
	 *
	 * @var Config_Definition_Property_ConfigList
	 */
	protected $connections;


	/**
	 * Get connection configuration
	 *
	 * @param $connection_name
	 *
	 * @throws Redis_Exception
	 * @return Redis_Connection_Config
	 */
	public function getConnection($connection_name){
		return $this->connections->getConfigurationListItem( $connection_name );
	}

	/**
	 * @return Redis_Connection_Config[]
	 */
	public function getConnections() {
		return $this->connections->getAllConfigurationItems();
	}

	/**
	 * Returns connection name for Redis::get() / Redis::getConnection() if connection name is not specified
	 *
	 * @return string
	 */
	public function getDefaultConnectionName() {
		return $this->default_connection_name;
	}

	/**
	 * @param $connection_name
	 * @param Redis_Connection_Config $connection_configuration
	 *
	 */
	public function addConnection( $connection_name, Redis_Connection_Config $connection_configuration ) {
		$this->connections->addConfigurationItem( $connection_name, $connection_configuration );
	}

	/**
	 * @param $connection_name
	 *
	 */
	public function deleteConnection( $connection_name ) {
		$this->connections->deleteConfigurationItem( $connection_name );
	}

	/**
	 *
	 * @return array
	 */
	public static function getConnectionsList() {
		$i = new self(true);

		$connections = [];

		foreach( $i->getConnections() as $name=>$connection) {

			$connections[$name] = $name;
		}

		return array_combine($connections, $connections);
	}
}