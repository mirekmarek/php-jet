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
 * @package Mvc
 * @subpackage Mvc_Router
 */
namespace Jet;

class Mvc_Router_Cache_Backend_Redis_Config extends Mvc_Router_Cache_Backend_Config_Abstract {

	/**
	 * @var array
	 */
	protected static $__config_properties_definition = array(
		"connection" => array(
			"type" => self::TYPE_STRING,
			"is_required" => true,
			"form_field_label" => "Connection: ",
			"form_field_type" => "Select",
			"form_field_get_select_options_callback" => array("Jet\\DataModel_Cache_Backend_Redis_Config", "getRedisConnectionsList")
		),
		"key_prefix" => array(
			"type" => self::TYPE_STRING,
			"is_required" => true,
			"default_value" => "mvc_c",
			"form_field_label" => "Cache key prefix: ",
		),
	);

	/**
	 * @var string
	 */
	protected $connection = "";

	/**
	 * @var string
	 */
	protected $key_prefix = "mvc_c";

	/**
	 * @return string
	 */
	public function getConnection() {
		return $this->connection;
	}

	/**
	 * @return string
	 */
	public function getKeyPrefix() {
		return $this->key_prefix;
	}

	/**
	 * @static
	 * @return array
	 */
	public static function getRedisConnectionsList() {
		return Redis_Config::getConnectionsList();
	}

}