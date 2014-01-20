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

	/**
	 * @JetC:type = Jet\Config::TYPE_STRING
	 * @JetC:description = 'String property'
	 * @JetC:is_required = true
	 * @JetC:default_value = 'default value'
	 * @JetC:form_field_label = 'String property:'
	 * 
	 * @var string
	 */
	protected $string_property = '';
	
	/**
	 * @JetC:type = Jet\Config::TYPE_INT
	 * @JetC:description = 'Int property'
	 * @JetC:is_required = false
	 * @JetC:default_value = 123
	 * @JetC:form_field_label = 'Int property:'
	 * 
	 * @var int
	 */
	protected $int_property = 0;
	
	/**
	 * @JetC:type = Jet\Config::TYPE_FLOAT
	 * @JetC:description = ''
	 * @JetC:is_required = true
	 * @JetC:default_value = 123.45
	 * @JetC:form_field_label = 'Float property:'
	 * 
	 * @var float
	 */
	protected $float_property = 0.0;
	
	/**
	 * @JetC:type = Jet\Config::TYPE_BOOL
	 * @JetC:description = 'Bool property:'
	 * @JetC:is_required = false
	 * @JetC:default_value = true
	 * @JetC:form_field_label = 'Bool property:'
	 * 
	 * @var bool
	 */
	protected $bool_property = false;

	/**
	 *
	 * @var string
	 */
	protected static $application_config_file_path = "";

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