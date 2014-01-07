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

abstract class Redis_Connection_Config_Abstract extends Config_Section {
	/**
	 * @var null|string
	 */
	protected static $__factory_class_name = null;
	/**
	 * @var null|string
	 */
	protected static $__factory_class_method = null;
	/**
	 * @var null|string
	 */
	protected static $__factory_must_be_instance_of_class_name = "Jet\\Redis_Connection_Config_Abstract";

	/**
	 * @var array
	 */
	protected static $__config_properties_definition = array(
		"name" => array(
			"form_field_label" => "Connection name",
			"type" => self::TYPE_STRING,
			"default_value" => "default",
			"is_required" => true
		),
		"host" => array(
			"form_field_label" => "Host or socket",
			"type" => self::TYPE_STRING,
			"default_value" => "127.0.0.1",
			"is_required" => true
		),
		"port" => array(
			"form_field_label" => "Port",
			"type" => self::TYPE_STRING,
			"default_value" => 6379,
			"is_required" => false
		),
	);

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $host;

	/**
	 * @var string
	 */
	protected $port;


	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getHost() {
		return $this->host;
	}

	/**
	 *
	 * @return string
	 */
	public function getPort() {
		return $this->port;
	}
}