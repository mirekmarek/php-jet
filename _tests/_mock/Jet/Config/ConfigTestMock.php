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

/**
 * Class ConfigTestMock
 *
 * @JetConfig:data_path = '/section/subsection'
 */
class ConfigTestMock extends Config {

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:description = 'String property'
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'default value'
	 * @JetConfig:form_field_label = 'String property:'
	 * 
	 * @var string
	 */
	protected $string_property = '';
	
	/**
	 * @JetConfig:type = Config::TYPE_INT
	 * @JetConfig:description = 'Int property'
	 * @JetConfig:is_required = false
	 * @JetConfig:default_value = 123
	 * @JetConfig:form_field_label = 'Int property:'
	 * 
	 * @var int
	 */
	protected $int_property = 0;
	
	/**
	 * @JetConfig:type = Config::TYPE_FLOAT
	 * @JetConfig:description = ''
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 123.45
	 * @JetConfig:form_field_label = 'Float property:'
	 * 
	 * @var float
	 */
	protected $float_property = 0.0;
	
	/**
	 * @JetConfig:type = Config::TYPE_BOOL
	 * @JetConfig:description = 'Bool property:'
	 * @JetConfig:is_required = false
	 * @JetConfig:default_value = true
	 * @JetConfig:form_field_label = 'Bool property:'
	 * 
	 * @var bool
	 */
	protected $bool_property = false;

	/**
	 *
	 * @var string
	 */
	protected static $application_config_file_path = '';

	/**
	 */
	/** @noinspection PhpMissingParentConstructorInspection */
	public function __construct() {
	}

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

	public function testInit( $config_file_path, $soft_mode=false ) {
		$this->config_file_path = $config_file_path;
		$this->soft_mode = (bool)$soft_mode;

		$this->setData( $this->readConfigData( $config_file_path ) );
	}


	public function testSetConfigFilePath($config_file_path) {
		$this->config_file_path = $config_file_path;
	}

}