<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 * @JetConfig:name = 'db'
 */
class Db_Config extends Config
{


	/**
	 * @JetConfig:form_field_label = 'Default connection:'
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:description = 'Connection name default value for Db::get() / Db::getConnection()'
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'default'
	 * @JetConfig:form_field_type = Form::TYPE_SELECT
	 * @JetConfig:form_field_get_select_options_callback = ['Db_Config', 'getConnectionsList']
	 * @JetConfig:form_field_error_messages = [Form_Field::ERROR_CODE_EMPTY=>'Please select default connection', Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE=>'Please select default connection']
	 *
	 * @var string
	 */
	protected $default_connection_name = 'default';


	/**
	 * @JetConfig:type = Config::TYPE_SECTIONS
	 * @JetConfig:section_creator_method_name = 'connectionConfigCreator'
	 *
	 * @var Db_Backend_Config[]
	 */
	protected $connections;

	/**
	 *
	 * @param string $driver_type_filter (optional)
	 *
	 * @return array
	 */
	public static function getConnectionsList( $driver_type_filter = '' )
	{
		$i = new self();

		$connections = [];

		foreach( $i->getConnections() as $name => $connection ) {
			if(
				$driver_type_filter &&
				$driver_type_filter!=$connection->getDriver()
			) {
				continue;
			}

			$connections[$name] = $name;
		}

		return array_combine( $connections, $connections );
	}

	/**
	 * @return Db_Backend_Config[]
	 */
	public function getConnections()
	{
		return $this->connections;
	}

	/**
	 *
	 * @param string $connection_name
	 *
	 * @throws Db_Exception
	 * @return Db_Backend_Config
	 */
	public function getConnection( $connection_name )
	{
		if(!isset($this->connections[$connection_name])) {
			return null;
		}

		return $this->connections[$connection_name];
	}

	/**
	 *
	 * @return string
	 */
	public function getDefaultConnectionName()
	{
		return $this->default_connection_name;
	}

	/**
	 * @param string $default_connection_name
	 */
	public function setDefaultConnectionName( $default_connection_name )
	{
		$this->default_connection_name = $default_connection_name;
	}



	/**
	 * @param string               $connection_name
	 * @param Db_Backend_Config $connection_configuration
	 *
	 */
	public function addConnection( $connection_name, Db_Backend_Config $connection_configuration )
	{
		$this->connections[$connection_name] = $connection_configuration;
	}

	/**
	 * @param string $connection_name
	 *
	 */
	public function deleteConnection( $connection_name )
	{
		if(isset($this->connections[$connection_name])) {
			unset($this->connections[$connection_name]);
		}
	}

	/**
	 * @param array $data
	 *
	 * @return Db_Backend_Config|Db_Backend_PDO_Config
	 */
	public function connectionConfigCreator( array $data )
	{
		return Db_Factory::getBackendConfigInstance( $data );
	}
}