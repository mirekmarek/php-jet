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

require_once '_mock/Jet/Config/ConfigListTestMainMock/Config/Abstract.php';


class ConfigListTestMainMock_AdapterA_Config extends ConfigListTestMainMock_Config_Abstract {

	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_type = false
	 * @JetConfig:default_value = 'AdapterA'
	 * 
	 * @var string
	 */
	protected $adapter = '';

	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:default_value = ''
	 * @JetConfig:is_required = true
	 * 
	 * @var string
	 */
	protected $adapter_config_value = '';

}