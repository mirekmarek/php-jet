<?php
/**
 *
 *
 *
 * DataModel handle exception
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Translator
 * @subpackage Translator_Config
 */
namespace Jet;

class Translator_Config extends Config_Application {
	/**
	 * @var bool
	 */
	protected static $__config_section_is_obligatory = false;
	/**
	 * @var string
	 */
	protected static $__config_data_path = "translator";
	/**
	 * @var array
	 */
	protected static $__config_properties_definition = array(
		"backend_type" => array(
			"type" => self::TYPE_STRING,
			"is_required" => false,
			"default_value" => "PHPFiles",
			"form_field_label" => "Default backend type: ",
			"form_field_type" => "Select",
			"form_field_get_select_options_callback" => array("Jet\\Translator_Config", "getBackendTypesList")
		),
		"auto_append_unknown_phrase" => array(
			"type" => self::TYPE_BOOL,
			"default_value" => true,
			"form_field_label" => "Auto append unknown phrase: ",
		)
	);

	/**
	 * @var string
	 */
	protected $backend_type;

	/**
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
		return static::getAvailableHandlersList( JET_LIBRARY_PATH."Jet/Translator/Backend/" );
	}

}