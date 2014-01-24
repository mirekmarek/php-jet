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

/**
 * Class ConfigListTestMainMock_Config_Abstract
 *
 * @JetFactory:class = null
 * @JetFactory:method = null
 * @JetFactory:mandatory_parent_class = 'Jet\\ConfigListTestMainMock_Config_Abstract'
 */
abstract class ConfigListTestMainMock_Config_Abstract extends Config_Section {


	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_type = false
	 *
	 * @var string
	 */
	protected $adapter = '';


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