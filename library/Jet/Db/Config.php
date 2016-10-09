<?php
/**
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Db
 * @subpackage Db_Config
 */
namespace Jet;

/**
 * Class Db_Config
 *
 * @JetConfig:data_path = 'database'
 */
class Db_Config extends Application_Config {


	/**
	 * @JetConfig:form_field_label = 'Default connection:'
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:description = 'Connection name default value for Db::get() / Db::getConnection()'
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'default'
	 * @JetConfig:form_field_type = Form::TYPE_SELECT
	 * @JetConfig:form_field_get_select_options_callback = ['Db_Config', 'getConnectionsList']
     * @JetConfig:form_field_error_messages = [Form_Field_Abstract::ERROR_CODE_EMPTY=>'Please select default connection', Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE=>'Please select default connection']
	 *
	 * @var string
	 */
	protected $default_connection_name = 'default';


	/**
	 * @JetConfig:type = Config::TYPE_CONFIG_LIST
	 * @JetConfig:data_path = 'connections'
	 * @JetConfig:config_factory_class_name = 'Db_Factory'
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
	 * @throws Db_Exception
	 * @return Db_Connection_Config_Abstract
	 */
	public function getConnection($connection_name){
		return $this->connections->getConfigurationListItem( $connection_name );
	}

	/**
	 * @return Db_Connection_Config_Abstract[]
	 */
	public function getConnections() {
		/**
		 * @var Db_Connection_Config_Abstract[] $c_cfg
		 */
		$c_cfg = $this->connections->getAllConfigurationItems();
		return $c_cfg;
	}

	/**
	 * Returns connection name for Db::get() / Db::getConnection() if connection name is not specified (one of the keys in 'connections')
	 *
	 * @return string
	 */
	public function getDefaultConnectionName() {
		return $this->default_connection_name;
	}

	/**
	 * @param $connection_name
	 * @param Db_Connection_Config_Abstract $connection_configuration
	 *
	 */
	public function addConnection( $connection_name, Db_Connection_Config_Abstract $connection_configuration ) {
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
	 * @param string $driver_type_filter (optional)
	 *
	 * @return array
	 */
	public static function getConnectionsList( $driver_type_filter='' ) {
		$i = new self(true);

		$connections = [];

		foreach( $i->getConnections() as $name=>$connection) {
			if(
				$driver_type_filter &&
				$driver_type_filter!=$connection->getDriver()
			) {
				continue;
			}

			$connections[$name] = $name;
		}

		return array_combine($connections, $connections);
	}
}