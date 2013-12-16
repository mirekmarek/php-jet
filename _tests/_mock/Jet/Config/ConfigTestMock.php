<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package Config
 */
namespace Jet;

class ConfigTestMock extends Config {
	protected static $__config_data_path = "/section/subsection";

	protected $string_property;
	protected $int_property;
	protected $float_property;
	protected $bool_property;


	protected static $__config_properties_definition = array(
		"string_property" => array(
			"type" => self::TYPE_STRING,
			"description" => "String property",
			"is_required" => true,
			"default_value" => "default value",
			"form_field_label" => "String property:"
		),
		"int_property" => array(
			"type" => self::TYPE_INT,
			"description" => "Int property",
			"is_required" => false,
			"default_value" => 123,
			"form_field_label" => "Int property:"
		),
		"float_property" => array(
			"type" => self::TYPE_FLOAT,
			"description" => "",
			"is_required" => true,
			"default_value" => 123.45,
			"form_field_label" => "Float property:"
		),
		"bool_property" => array(
			"type" => self::TYPE_BOOL,
			"description" => "Bool property:",
			"is_required" => false,
			"default_value" => true,
			"form_field_label" => "Bool property:"
		),

	);

	public function getBoolProperty() {
		return $this->bool_property;
	}

	public function getFloatProperty() {
		return $this->float_property;
	}

	public function getIntProperty() {
		return $this->int_property;
	}

	public function getStringProperty() {
		return $this->string_property;
	}

	/**
	 */
	public function __construct() {
	}

	public function testInit( $config_file_path, $soft_mode=false ) {
		$this->config_file_path = $config_file_path;
		$this->soft_mode = (bool)$soft_mode;

		$this->setData( $this->readConfigData($config_file_path) );
	}


	public function testSetConfigFilePath($config_file_path) {
		$this->config_file_path = $config_file_path;
	}

}