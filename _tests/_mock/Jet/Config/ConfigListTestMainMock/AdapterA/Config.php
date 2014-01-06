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

require_once "_mock/Jet/Config/ConfigListTestMainMock/Config/Abstract.php";


class ConfigListTestMainMock_AdapterA_Config extends ConfigListTestMainMock_Config_Abstract {

	protected static $__config_properties_definition = array(
		"adapter" => array(
			"type" => self::TYPE_STRING,
			"is_required" => true,
			"form_field_type" => false,
			"default_value" => "AdapterA"
		),

		"adapter_config_value" => array(
			"type" => self::TYPE_STRING,
			"default_value" => "",
			"is_required" => true,
		),

	);


}