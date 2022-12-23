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
class Factory_Db
{

	/**
	 * @var string
	 */
	protected static string $default_backend_class_name = Db_Backend_PDO::class;

	/**
	 * @var string
	 */
	protected static string $default_backend_config_class_name = Db_Backend_PDO_Config::class;

	/**
	 * @return string
	 */
	public static function getDefaultBackendClassname(): string
	{
		return static::$default_backend_class_name;
	}

	/**
	 * @param string $default_backend_class_name
	 */
	public static function setDefaultBackendClassname( string $default_backend_class_name ) : void
	{
		static::$default_backend_class_name = $default_backend_class_name;
	}

	/**
	 * @return string
	 */
	public static function getDefaultBackendConfigClassname(): string
	{
		return static::$default_backend_config_class_name;
	}

	/**
	 * @param string $default_backend_config_class_name
	 */
	public static function setDefaultBackendConfigClassname( string $default_backend_config_class_name ): void
	{
		static::$default_backend_config_class_name = $default_backend_config_class_name;
	}


	/**
	 *
	 * @param array $config_data
	 *
	 * @return Db_Backend_Config
	 */
	public static function getBackendConfigInstance( array $config_data = [] ): Db_Backend_Config
	{
		$config_class = static::$default_backend_config_class_name;

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
		$adapter_class = static::$default_backend_class_name;

		return new $adapter_class( $connection_config );
	}
}