<?php
/**
 *
 *
 *
 * Common database adapter config
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Router
 */
namespace Jet;

class Mvc_Router_Cache_Backend_MySQL_Config extends Mvc_Router_Cache_Backend_Config_Abstract {

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_label = 'Connection - read: '
	 * @JetConfig:form_field_type = 'Select'
	 * @JetConfig:form_field_get_select_options_callback = ['DataModel_Backend_MySQL_Config', 'getDbConnectionsList']
	 *
	 * @var string
	 */
	protected $connection_read = '';

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_label = 'Connection - write: '
	 * @JetConfig:form_field_type = 'Select'
	 * @JetConfig:form_field_get_select_options_callback = ['DataModel_Backend_MySQL_Config', 'getDbConnectionsList']
	 *
	 * @var string
	 */
	protected $connection_write= '';

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = false
	 * @JetConfig:default_value = 'InnoDB'
	 * @JetConfig:form_field_label = 'Engine: '
	 *
	 * @var string
	 */
	protected $engine= '';

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = false
	 * @JetConfig:default_value = 'jet_mvc_router_cache'
	 * @JetConfig:form_field_label = 'Table name: '
	 *
	 * @var string
	 */
	protected $table_name = '';

	/**
	 * @return string
	 */
	public function getConnectionRead() {
		return $this->connection_read;
	}

	/**
	 * @return string
	 */
	public function getConnectionWrite() {
		return $this->connection_write;
	}

	/**
	 * @return string
	 */
	public function getEngine() {
		return $this->engine;
	}

	/**
	 * @return string
	 */
	public function getTableName() {
		return $this->table_name;
	}

	/**
	 * @static
	 * @return array
	 */
	public static function getDbConnectionsList() {
		return Db_Config::getConnectionsList(Db::DRIVER_MYSQL);
	}
}