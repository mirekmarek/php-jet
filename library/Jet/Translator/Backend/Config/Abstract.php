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

/**
 * Class Translator_Backend_Config_Abstract
 *
 * @JetFactory:class = null
 * @JetFactory:method = null
 * @JetFactory:mandatory_parent_class = 'Jet\\Translator_Backend_Config_Abstract'
 *
 * @JetConfig:data_path = '/translator/backend_options'
 * @JetConfig:section_is_obligatory = false
 */
abstract class Translator_Backend_Config_Abstract extends Config_Application {

}