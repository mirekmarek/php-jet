<?php
/**
 *
 *
 *
 * Common database adapter config
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Translator
 * @subpackage Translator_Backend
 */

namespace Jet;

abstract class Translator_Backend_Config_Abstract extends Config_Application {
	/**
	 * @var string|null
	 */
	protected static $__factory_class_name = null;
	/**
	 * @var string|null
	 */
	protected static $__factory_method_name = null;
	/**
	 * @var string
	 */
	protected static $__factory_must_be_instance_of_class_name = 'Jet\\Translator_Backend_Config_Abstract';

	/**
	 * @var bool
	 */
	protected static $__config_section_is_obligatory = false;
	/**
	 * @var string
	 */
	protected static $__config_data_path = '/translator/backend_options';

	/**
	 * @var array
	 */
	protected static $__config_properties_definition = array(
	);

}