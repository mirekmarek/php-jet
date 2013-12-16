<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet/tests
 * @package Config
 */
namespace Jet;

abstract class ConfigTestAdapterMainMock_Config_Abstract extends Config_Section {
	protected static $__factory_class_name = null;
	protected static $__factory_class_method = null;
	protected static $__factory_must_be_instance_of_class_name = "Jet\\ConfigTestAdapterMainMock_Config_Abstract";

	protected static $__config_properties_definition = array(
		"adapter" => array(
			"type" => self::TYPE_STRING,
			"is_required" => true,
			"form_field_type" => false
		)
	);

	/**
	 *
	 * @var string
	 */
	protected $adapter = "";


	protected $adapter_config_value;

	public function getAdapterConfigValue() {
		return $this->adapter_config_value;
	}


	/**
	 * @return string
	 */
	public function getAdapter() {
		return $this->adapter;
	}
}