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

class Db_Factory {

	/**
	 *
	 * @param array $config_data (optional)
	 * @param Db_Config $config (optional)
	 *
	 * @return Db_Connection_Config_Abstract
	 */
	public static function getConnectionConfigInstance(array $config_data= [], Db_Config $config=null ){
        $config_class = JET_DB_CONNECTION_CLASS_PREFIX.JET_DB_CONNECTION_ADAPTER.'_Config';


		return new $config_class( $config_data, $config );
	}

	/**
	 *
	 * @param Db_Connection_Config_Abstract $connection_config
	 *
	 * @return Db_Connection_Abstract
	 */
	public static function getConnectionInstance( Db_Connection_Config_Abstract $connection_config ){
        $adapter_class = JET_DB_CONNECTION_CLASS_PREFIX.JET_DB_CONNECTION_ADAPTER;


		return new $adapter_class( $connection_config );
	}
}