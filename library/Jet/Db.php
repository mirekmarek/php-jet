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
 */
class Db extends BaseObject
{
	const DRIVER_MYSQL = 'mysql';
	const DRIVER_SQLITE = 'sqlite';
	const DRIVER_OCI = 'oci';

	/**
	 * @var Db_Config
	 */
	protected static $config = null;

	/**
	 * @var Db_BackendInterface[]
	 */
	protected static $connections = [];

	/**
	 *
	 * @param string|null $connection_name (optional)
	 *
	 * @return Db_BackendInterface
	 * @throws Db_Exception
	 */
	public static function get( $connection_name = null )
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
				'Connection \''.$connection_name.'\' does not exist',
				Db_Exception::CODE_UNKNOWN_CONNECTION
			);
		}

		static::$connections[$connection_name] = Db_Factory::getBackendInstance( $connection_config );

		return static::$connections[$connection_name];
	}

	/**
	 * Get DB config instance
	 *
	 * @return Db_Config
	 */
	public static function getConfig()
	{
		if( !static::$config ) {
			static::$config = new Db_Config();
		}

		return static::$config;
	}

	/**
	 * @param string $connection_name
	 * @param array  $connection_config_data
	 *
	 * @return Db_BackendInterface
	 */
	public static function create( $connection_name, array $connection_config_data )
	{
		if( isset( static::$connections[$connection_name] ) ) {
			return static::$connections[$connection_name];
		}

		$config = Db_Factory::getBackendConfigInstance( $connection_config_data );
		$connection = Db_Factory::getBackendInstance( $config );

		static::$connections[$connection_name] = $connection;

		return $connection;

	}
}