<?php
/**
 *
 *
 *
 * Common database adapter config
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
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
	 * @var array
	 */
	protected static $__config_properties_definition = array(
		"connection_read" => array(
			"type" => self::TYPE_STRING,
			"is_required" => true,
			"form_field_label" => "Connection - read: ",
			"form_field_type" => "Select",
			"form_field_get_select_options_callback" => array("Jet\\Mvc_Router_Cache_Backend_MySQL_Config", "getDbConnectionsList")
		),
		"connection_write" => array(
			"type" => self::TYPE_STRING,
			"is_required" => true,
			"form_field_label" => "Connection - write: ",
			"form_field_type" => "Select",
			"form_field_get_select_options_callback" => array("Jet\\Mvc_Router_Cache_Backend_MySQL_Config", "getDbConnectionsList")
		),
		"table_name" => array(
			"type" => self::TYPE_STRING,
			"is_required" => false,
			"default_value" => "jet_mvc_router_cache",
			"form_field_label" => "Table name: ",
		),
		"engine" => array(
			"type" => self::TYPE_STRING,
			"is_required" => false,
			"default_value" => "InnoDB",
			"form_field_label" => "Engine: ",
		)

	);

	/**
	 * @var string
	 */
	protected $connection_read = "";
	/**
	 * @var string
	 */
	protected $connection_write= "";

	/**
	 * @var string
	 */
	protected $engine = "";

	/**
	 * @var string
	 */
	protected $table_name = "";

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
		return Db_Config::getConnectionsList();
	}
}