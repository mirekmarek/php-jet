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
 */
namespace Jet;

class Memcache extends BaseObject {

	/**
	 * @var Memcache_Config
	 */
	protected static $config = null;

	/**
	 * @var Memcache_Connection[]
	 */
	protected static $connections = [];

	/**
	 * Get connection instance (configured Memcache client)
	 *
	 * @param $connection_name (optional)
	 *
	 * @return Memcache_Connection
	 * @throws Memcache_Exception
	 */
	public static function getConnection( $connection_name=null ){
		return self::get($connection_name);
	}

	/**
	 * @param string $connection_name
	 * @param array $connection_config_data
	 *
	 * @return Memcache_Connection
	 */
	public static function create( $connection_name, array $connection_config_data ) {
		if(isset(static::$connections[$connection_name])){
			return static::$connections[$connection_name];
		}

		$config = new Memcache_Connection_Config( $connection_config_data );
		$connection = new Memcache_Connection( $config );

		static::$connections[$connection_name] = $connection;

		return $connection;

	}

	/**
	 * Get connection instance (configured Memcache client)
	 *
	 * @param $connection_name (optional)
	 *
	 * @return Memcache_Connection
	 * @throws Memcache_Exception
	 */
	public static function get( $connection_name=null ){
		if(!$connection_name) {
			$connection_name = static::getConfig()->getDefaultConnectionName();
		}

		if(isset(static::$connections[$connection_name])){
			return static::$connections[$connection_name];
		}

		$config = static::getConfig();

		$connection_config = $config->getConnection($connection_name);

		if( !$connection_config ){
			throw new Memcache_Exception(
				'Connection \''.$connection_name.'\' does not exist',
				Db_Exception::CODE_UNKNOWN_CONNECTION
			);
		}

		static::$connections[$connection_name] = new Memcache_Connection( $connection_config );

		return static::$connections[$connection_name];
	}

	/**
	 * Get Memcache config instance
	 *
	 * @return Memcache_Config
	 */
	public static function getConfig(){
		if(!static::$config) {
			static::$config = new Memcache_Config();
		}
		return static::$config;
	}
}