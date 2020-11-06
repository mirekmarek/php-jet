<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @var Db_Backend_Interface[]
	 */
	protected static $connections = [];

	/**
	 *
	 * @param string|null $connection_name (optional)
	 *
	 * @return Db_Backend_Interface
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
	 * @param Db_Config $config
	 */
	public static function setConfig( Db_Config $config )
	{
		static::$config = $config;
	}

	/**
	 * @param string $connection_name
	 * @param array  $connection_config_data
	 *
	 * @return Db_Backend_Interface
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