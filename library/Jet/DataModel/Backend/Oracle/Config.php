<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Backend
 */
namespace Jet;

class DataModel_Backend_Oracle_Config extends DataModel_Backend_Config_Abstract {

	/**
	 * @var array
	 */
	protected static $__config_properties_definition = array(
		"connection_read" => array(
			"type" => self::TYPE_STRING,
			"is_required" => true,
			"form_field_label" => "Connection - read: ",
			"form_field_type" => "Select",
			"form_field_get_select_options_callback" => array("Jet\\DataModel_Backend_Oracle_Config", "getDbConnectionsList")
		),
		"connection_write" => array(
			"type" => self::TYPE_STRING,
			"is_required" => true,
			"form_field_label" => "Connection - write: ",
			"form_field_type" => "Select",
			"form_field_get_select_options_callback" => array("Jet\\DataModel_Backend_Oracle_Config", "getDbConnectionsList")
		),
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
	 * @return array
	 */
	public static function getDbConnectionsList() {
		return Db_Config::getConnectionsList(Db::DRIVER_OCI);
	}
}