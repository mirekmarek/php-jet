<?php
/**
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Redis
 */
namespace Jet;

class Redis extends Object {

	/**
	 * @var Redis_Config
	 */
	protected static $config = null;

	/**
	 * @var Redis_Connection_Abstract[]
	 */
	protected static $connections = array();

	/**
	 * Get connection instance (configured Redis client)
	 *
	 * @param $connection_name (optional)
	 *
	 * @return Redis_Connection_Abstract
	 * @throws Redis_Exception
	 */
	public static function getConnection( $connection_name=null ){
		return self::get($connection_name);
	}

	/**
	 * @param string $connection_name
	 * @param array $connection_config_data
	 *
	 * @return Redis_Connection_Abstract
	 */
	public static function create( $connection_name, array $connection_config_data ) {
		if(isset(static::$connections[$connection_name])){
			return static::$connections[$connection_name];
		}

		$config = Redis_Factory::getConnectionConfigInstance( $connection_config_data );
		$connection = Redis_Factory::getConnectionInstance( $config );

		static::$connections[$connection_name] = $connection;

		return $connection;

	}

	/**
	 * Get connection instance (configured Redis client)
	 *
	 * @param $connection_name (optional)
	 *
	 * @return Redis_Connection_Abstract
	 * @throws Redis_Exception
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
			throw new Redis_Exception(
				'Connection \''.$connection_name.'\' doesn\'t exist',
				Db_Exception::CODE_UNKNOWN_CONNECTION
			);
		}

		static::$connections[$connection_name] = Redis_Factory::getConnectionInstance( $connection_config );

		return static::$connections[$connection_name];
	}

	/**
	 * Get Redis config instance
	 *
	 * @return Redis_Config
	 */
	public static function getConfig(){
		if(!static::$config) {
			static::$config = new Redis_Config();
		}
		return static::$config;
	}
}