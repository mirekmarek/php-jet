<?php
/**
 *
 *
 *
 * DataModel handle exception
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Translator
 * @subpackage Translator_Config
 */
namespace Jet;

/**
 * Class Translator_Config
 *
 * @JetConfig:data_path = 'translator'
 * @JetConfig:section_is_obligatory = false
 */
class Translator_Config extends Application_Config {

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = false
	 * @JetConfig:default_value = 'PHPFiles'
	 * @JetConfig:form_field_label = 'Default backend type: '
	 * @JetConfig:form_field_type = 'Select'
	 * @JetConfig:form_field_get_select_options_callback = ['Translator_Config', 'getBackendTypesList']
	 * 
	 * @var string
	 */
	protected $backend_type;

	/**
	 * @JetConfig:type = Config::TYPE_BOOL
	 * @JetConfig:default_value = true
	 * @JetConfig:form_field_label = 'Auto append unknown phrase: '
	 * 
	 * @var bool
	 */
	protected $auto_append_unknown_phrase;

	/**
	 * @var Translator_Backend_Abstract
	 */
	protected $backend_config;


	/**
	 * @return string
	 */
	public function getBackendType() {
		return $this->backend_type;
	}

	/**
	 * @return boolean
	 */
	public function getAutoAppendUnknownPhrase() {
		return $this->auto_append_unknown_phrase;
	}

	/**
	 * @param bool $soft_mode
	 *
	 * @return Translator_Backend_Config_Abstract
	 */
	public function getBackendConfig( $soft_mode=false ) {
		if($this->backend_config===null) {
			$this->backend_config = Translator_Factory::getBackendConfigInstance($this->backend_type, $soft_mode);
		}

		return $this->backend_config;
	}


	/**
	 * @return array
	 */
	public static function getBackendTypesList() {
		return static::getAvailableHandlersList( JET_LIBRARY_PATH.'Jet/Translator/Backend/' );
	}

}