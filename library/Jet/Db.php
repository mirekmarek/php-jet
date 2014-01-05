<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Db
 */
namespace Jet;

class Db extends Object {

	/**
	 * @var Db_Config
	 */
	protected static $config = null;

	/**
	 * @var Db_Connection_Abstract[]
	 */
	protected static $connections = array();

	/**
	 * Get connection instance (configured PDO database adapter)
	 *
	 * @param $connection_name (optional)
	 *
	 * @return Db_Connection_Abstract
	 * @throws Db_Exception
	 */
	public static function getConnection( $connection_name=null ){
		return self::get($connection_name);
	}

	/**
	 * Get connection instance (configured PDO database adapter)
	 *
	 * @param $connection_name (optional)
	 *
	 * @return Db_Connection_Abstract
	 * @throws Db_Exception
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
			throw new Db_Exception(
				"Connection '{$connection_name}' doesn't exist",
				Db_Exception::CODE_UNKNOWN_CONNECTION
			);
		}

		static::$connections[$connection_name] = Db_Factory::getConnectionInstance( $connection_config );

		return static::$connections[$connection_name];
	}

	/**
	 * Get DB config instance
	 * 
	 * @return Db_Config
	 */
	public static function getConfig(){
		if(!static::$config) {
			static::$config = new Db_Config();
		}
		return static::$config;
	}
}