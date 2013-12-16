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

class Db_Adapter_PDO_Config extends Db_Adapter_Config_Abstract {

	/**
	 * @var array
	 */
	protected static $__config_properties_definition = array(
		"adapter" => array(
			"type" => self::TYPE_STRING,
			"is_required" => true,
			"form_field_type" => false,
			"default_value" => "PDO"
		),

		"driver" => array(
			"form_field_label" => "Driver",
			"type" => self::TYPE_STRING,
			"description" => "PDO driver",
			"default_value" => "mysql",
			"is_required" => true,
			"form_field_type" => "Select",
			"form_field_get_select_options_callback" => array("Jet\\Db_Adapter_PDO_Config", "getPDODrivers"),
		),

		"charset" => array(
			"form_field_label" => "Default character set",
			"type" => self::TYPE_STRING,
			"validation_regexp" => "/^[a-z0-9_]+$/",
			"description" => "Connection default character set",
			"default_value" => "utf8",
			"is_required" => false
		),

		"host" => array(
			"form_field_label" => "DB server host",
			"type" => self::TYPE_STRING,
			"default_value" => "127.0.0.1",
			"is_required" => true
		),

		"port" => array(
			"form_field_label" => "DB server port",
			"type" => self::TYPE_INT,
			"default_value" => 0,
			"is_required" => false
		),

		"database_name" => array(
			"form_field_label" => "Database name",
			"type" => self::TYPE_STRING,
			"validation_regexp" => "/^[a-zA-Z0-9_\-]+$/",
			"default_value" => null,
			"is_required" => true
		),

		"username" => array(
			"form_field_label" => "Username",
			"type" => self::TYPE_STRING,
			"default_value" => null,
			"is_required" => false
		),

		"password" => array(
			"form_field_label" => "Password",
			"type" => self::TYPE_STRING,
			"default_value" => null,
			"is_required" => false
		)

	);

	/**
	 * @var string
	 */
	protected $driver;

	/**
	 *
	 * @var string
	 */
	protected $charset;


	/**
	 *
	 * @var string
	 */
	protected $host;

	/**
	 *
	 * @var int
	 */
	protected $port;

	/**
	 *
	 * @var string
	 */
	protected $database_name;

	/**
	 *
	 * @var string
	 */
	protected $username;

	/**
	 * @var string
	 */
	protected $password;

	/**
	 * @return array
	 */
	public static function getPDODrivers() {
		return array(
			"mysql" => "MySQL"
		);
	}

	/**
	 * Get database name
	 *
	 * @return string
	 */
	public function getDatabaseName() {
		return $this->database_name;
	}

	/**
	 * Get connection default character set
	 *
	 * @return string
	 */
	public function getCharset() {
		return $this->charset;
	}

	/**
	 * Get server host
	 *
	 * @return string
	 */
	public function getHost() {
		return $this->host;
	}

	/**
	 * Get authorization password
	 *
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * Get server port
	 *
	 * @return int
	 */
	public function getPort() {
		return $this->port;
	}

	/**
	 * Get authorization user name
	 *
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @return string
	 */
	public function getDsn() {
		$dsn = array();
		$dsn[] = "host=".$this->host;
		$dsn[] = "dbname=".$this->database_name;
		$dsn[] = "charset=".$this->charset;
		$dsn = implode(";", $dsn);
		return $this->driver.":".$dsn;
	}
}