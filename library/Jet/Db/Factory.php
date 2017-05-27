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
class Db_Factory
{

	/**
	 * @var string
	 */
	protected static $default_backend = __NAMESPACE__.'\\Db_Backend_PDO';

	/**
	 * @return string
	 */
	public static function getDefaultBackend()
	{
		return static::$default_backend;
	}

	/**
	 * @param string $default_backend
	 */
	public static function setDefaultBackend( $default_backend )
	{
		static::$default_backend = $default_backend;
	}



	/**
	 *
	 * @param array     $config_data (optional)
	 * @param Db_Config $config (optional)
	 *
	 * @return Db_Backend_Config|Db_Backend_PDO_Config
	 */
	public static function getBackendConfigInstance( array $config_data = [], Db_Config $config = null )
	{
		$config_class = static::$default_backend.'_Config';


		return new $config_class( $config_data, $config );
	}

	/**
	 *
	 * @param Db_Backend_Config $connection_config
	 *
	 * @return Db_BackendInterface
	 */
	public static function getBackendInstance( Db_Backend_Config $connection_config )
	{
		$adapter_class = static::$default_backend;


		return new $adapter_class( $connection_config );
	}
}