<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
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
		type: Config::TYPE_STRING,
		is_required: true,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_SELECT,
		help_text: 'Connection name default value for Db::get() / Db::getConnection()',
		is_required: true,
		
		label: 'Default connection:',
		select_options_callback_creator: [
			Db_Config::class,
			'getConnectionsList'
		],
		error_messages: [
			Form_Field::ERROR_CODE_EMPTY => 'Please select default connection',
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select default connection'
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

		return $connections;
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
	 * @param Db_Backend_Config $connection_configuration
	 *
	 */
	public function addConnection( Db_Backend_Config $connection_configuration ): void
	{
		$this->connections[$connection_configuration->getName()] = $connection_configuration;
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
	 * @return Db_Backend_Config
	 */
	public function connectionConfigCreator( array $data ): Db_Backend_Config
	{
		return Factory_Db::getBackendConfigInstance( $data );
	}
}