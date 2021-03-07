<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
#[Config_Definition(name: 'db')]
class Db_Config extends Config
{


	/**
	 *
	 * @var string
	 */
	#[Config_Definition(
		form_field_label: 'Default connection:',
		type: Config::TYPE_STRING,
		description: 'Connection name default value for Db::get() / Db::getConnection()',
		is_required: true,
		default_value: 'default',
		form_field_type: Form::TYPE_SELECT,
		form_field_get_select_options_callback: [
			'Db_Config',
			'getConnectionsList'
		]
	)]
	#[Config_Definition(
		form_field_error_messages: [
			Form_Field::ERROR_CODE_EMPTY => 'Please select default connection',
			Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE => 'Please select default connection'
		]
	)]
	protected string $default_connection_name = 'default';


	/**
	 *
	 * @var Db_Backend_Config[]|null
	 */
	#[Config_Definition(
		type: Config::TYPE_SECTIONS,
		section_creator_method_name: 'connectionConfigCreator'
	)]
	protected ?array $connections = null;

	/**
	 *
	 * @param string $driver_type_filter (optional)
	 *
	 * @return array
	 */
	public static function getConnectionsList( string $driver_type_filter = '' ): array
	{
		$i = new self();

		$connections = [];

		foreach( $i->getConnections() as $name => $connection ) {
			if(
				$driver_type_filter &&
				$driver_type_filter != $connection->getDriver()
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
	public function getConnections(): array
	{
		return $this->connections;
	}

	/**
	 *
	 * @param string $connection_name
	 *
	 * @return Db_Backend_Config|null
	 */
	public function getConnection( string $connection_name ): Db_Backend_Config|null
	{
		if( !isset( $this->connections[$connection_name] ) ) {
			return null;
		}

		return $this->connections[$connection_name];
	}

	/**
	 *
	 * @return string
	 */
	public function getDefaultConnectionName(): string
	{
		return $this->default_connection_name;
	}

	/**
	 * @param string $default_connection_name
	 */
	public function setDefaultConnectionName( string $default_connection_name ): void
	{
		$this->default_connection_name = $default_connection_name;
	}


	/**
	 * @param string $connection_name
	 * @param Db_Backend_Config $connection_configuration
	 *
	 */
	public function addConnection( string $connection_name, Db_Backend_Config $connection_configuration ): void
	{
		$this->connections[$connection_name] = $connection_configuration;
	}

	/**
	 * @param string $connection_name
	 *
	 */
	public function deleteConnection( string $connection_name ): void
	{
		if( isset( $this->connections[$connection_name] ) ) {
			unset( $this->connections[$connection_name] );
		}
	}

	/**
	 * @param array $data
	 *
	 * @return Db_Backend_Config|Db_Backend_PDO_Config
	 */
	public function connectionConfigCreator( array $data ): Db_Backend_Config|Db_Backend_PDO_Config
	{
		return Db_Factory::getBackendConfigInstance( $data );
	}
}