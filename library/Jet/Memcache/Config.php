<?php
/**
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Memcache
 * @subpackage Memcache_Config
 */
namespace Jet;

/**
 * Class Memcache_Config
 *
 * @JetConfig:data_path = 'memcache'
 */
class Memcache_Config extends Application_Config {

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:description = 'Default connection name for Memcache::get() / Memcache::getConnection()'
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'default'
	 * @JetConfig:form_field_label = 'Default connection:'
     * @JetConfig:form_field_type = Form::TYPE_SELECT
	 * @JetConfig:form_field_get_select_options_callback = ['Memcache_Config', 'getConnectionsList']
     * @JetConfig:form_field_error_messages = [Form_Field_Abstract::ERROR_CODE_EMPTY=>'Please select default connection', Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE=>'Please select default connection']
	 *
	 * @var string
	 */
	protected $default_connection_name = 'default';


	/**
	 * @JetConfig:type = Config::TYPE_CONFIG_LIST
	 * @JetConfig:data_path = 'connections'
	 * @JetConfig:config_factory_class_name = 'Memcache_Config'
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
	 * @throws Memcache_Exception
	 * @return Memcache_Connection_Config
	 */
	public function getConnection($connection_name){
		return $this->connections->getConfigurationListItem( $connection_name );
	}

	/**
	 * @return Memcache_Connection_Config[]
	 */
	public function getConnections() {
		/**
		 * @var Memcache_Connection_Config[] $c_cfg
		 */
		$c_cfg = $this->connections->getAllConfigurationItems();
		return $c_cfg;
	}

	/**
	 * Returns connection name for Memcache::get() / Memcache::getConnection() if connection name is not specified (one of the keys in 'connections')
	 *
	 * @return string
	 */
	public function getDefaultConnectionName() {
		return $this->default_connection_name;
	}

	/**
	 * @param $connection_name
	 * @param Memcache_Connection_Config $connection_configuration
	 *
	 */
	public function addConnection( $connection_name, Memcache_Connection_Config $connection_configuration ) {
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


    /**
     * @param array $data
     *
     * @return Memcache_Connection_Config
     */
    public static function getConnectionConfigInstance( $data ) {
        $config = new Memcache_Connection_Config( $data );

        return $config;
    }
}