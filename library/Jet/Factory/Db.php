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
class Factory_Db
{

	/**
	 * @var string
	 */
	protected static string $default_backend = Db_Backend_PDO::class;

	/**
	 * @var string
	 */
	protected static string $default_backend_config = Db_Backend_PDO_Config::class;

	/**
	 * @return string
	 */
	public static function getDefaultBackend(): string
	{
		return static::$default_backend;
	}

	/**
	 * @param string $default_backend
	 */
	public static function setDefaultBackend( string $default_backend )
	{
		static::$default_backend = $default_backend;
	}

	/**
	 * @return string
	 */
	public static function getDefaultBackendConfig(): string
	{
		return static::$default_backend_config;
	}

	/**
	 * @param string $default_backend_config
	 */
	public static function setDefaultBackendConfig( string $default_backend_config ): void
	{
		static::$default_backend_config = $default_backend_config;
	}


	/**
	 *
	 * @param array $config_data
	 *
	 * @return Db_Backend_Config
	 */
	public static function getBackendConfigInstance( array $config_data = [] ): Db_Backend_Config
	{
		$config_class = static::$default_backend_config;

		return new $config_class( $config_data );
	}

	/**
	 *
	 * @param Db_Backend_Config $connection_config
	 *
	 * @return Db_Backend_Interface
	 */
	public static function getBackendInstance( Db_Backend_Config $connection_config ): Db_Backend_Interface
	{
		$adapter_class = static::$default_backend;

		return new $adapter_class( $connection_config );
	}
}