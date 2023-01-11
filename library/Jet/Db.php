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
class Db extends BaseObject
{
	const DRIVER_MYSQL = 'mysql';
	const DRIVER_SQLITE = 'sqlite';
	const DRIVER_OCI = 'oci';
	const DRIVER_PGSQL = 'pgsql';

	/**
	 * @var ?Db_Config
	 */
	protected static ?Db_Config $config = null;

	/**
	 * @var Db_Backend_Interface[]
	 */
	protected static array $connections = [];

	/**
	 *
	 * @param ?string $connection_name (optional)
	 *
	 * @return Db_Backend_Interface
	 * @throws Db_Exception
	 */
	public static function get( ?string $connection_name = null ): Db_Backend_Interface
	{
		if( !$connection_name ) {
			$connection_name = static::getConfig()->getDefaultConnectionName();
		}

		if( isset( static::$connections[$connection_name] ) ) {
			return static::$connections[$connection_name];
		}

		$config = static::getConfig();

		$connection_config = $config->getConnection( $connection_name );

		if( !$connection_config ) {
			throw new Db_Exception(
				'Connection \'' . $connection_name . '\' does not exist',
				Db_Exception::CODE_UNKNOWN_CONNECTION
			);
		}

		static::$connections[$connection_name] = Factory_Db::getBackendInstance( $connection_config );

		return static::$connections[$connection_name];
	}

	/**
	 *
	 * @return Db_Config
	 */
	public static function getConfig(): Db_Config
	{
		if( !static::$config ) {
			static::$config = new Db_Config();
		}

		return static::$config;
	}

	/**
	 * @param Db_Config $config
	 */
	public static function setConfig( Db_Config $config ): void
	{
		static::$config = $config;
	}

	/**
	 * @param string $connection_name
	 * @param array $connection_config_data
	 *
	 * @return Db_Backend_Interface
	 */
	public static function createConnection( string $connection_name, array $connection_config_data ): Db_Backend_Interface
	{
		if( isset( static::$connections[$connection_name] ) ) {
			return static::$connections[$connection_name];
		}

		$config = Factory_Db::getBackendConfigInstance( $connection_config_data );
		$connection = Factory_Db::getBackendInstance( $config );

		static::$connections[$connection_name] = $connection;

		return $connection;

	}
}