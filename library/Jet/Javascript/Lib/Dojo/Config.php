<?php
/**
 *
 *
 *
 * Global dojo configuration
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Javascript
 * @subpackage Javascript_Lib
 */
namespace Jet;

class Javascript_Lib_Dojo_Config extends Config_Application {

	/**
	 * @var string
	 */
	protected static $__config_data_path = "/js_libs/Dojo";
	/**
	 * @var bool
	 */
	protected static $__config_section_is_obligatory = false;

	/**
	 * @var array
	 */
	protected static $__config_properties_definition = array(
		"version" => array(
			"form_field_label" => "Dojo version",
			"type" => self::TYPE_STRING,
			"default_value" => "1.8.0rc1",
			"is_required" => false
		),

		"default_theme" => array(
			"form_field_label" => "Dijit theme",
			"type" => self::TYPE_STRING,
			"validation_regexp" => "/^[a-zA-Z0-9_\-]+$/",
			"default_value" => "claro",
			"is_required" => false
		),

		"dojo_js_URI" => array(
			"form_field_label" => "dojo.js URI",
			"type" => self::TYPE_STRING,
			"default_value" => "%JET_PUBLIC_SCRIPTS_URI%dojo/%VERSION%/dojo/dojo.js",
			"is_required" => false
		),

		"dojo_package_URI" => array(
			"form_field_label" => "Dojo package URI",
			"type" => self::TYPE_STRING,
			"is_required" => false
		),

		"theme_URI" => array(
			"form_field_label" => "Dijit theme URI",
			"type" => self::TYPE_STRING,
			"default_value" => "%JET_PUBLIC_SCRIPTS_URI%dojo/%VERSION%/dijit/themes/%THEME%/%THEME%.css",
			"is_required" => false
		),

		"parse_on_load" => array(
			"form_field_label" => "Parse on load",
			"type" => self::TYPE_BOOL,
			"default_value" => true
		),

		"is_debug" => array(
			"form_field_label" => "Dojo debug",
			"type" => self::TYPE_BOOL,
			"default_value" => false
		),

	);


	/**
	 *
	 * @var string
	 */
	protected $version;

	/**
	 *
	 * @var string
	 */
	protected $default_theme;

	/**
	 *
	 * @var string
	 */
	protected $dojo_js_URI = "%JET_BASE_URI%public/libs/dojo/%VERSION%/dojo/dojo.js";

	/**
	 *
	 * @var string
	 */
	protected $dojo_package_URI = "";

	/**
	 *
	 * @var string
	 */
	protected $theme_URI = "%JET_BASE_URI%public/libs/dojo/%VERSION%/dijit/themes/%THEME%/%THEME%.css";

	/**
	 *
	 * @var bool
	 */
	protected $parse_on_load;

	/**
	 * Dojo debug
	 *
	 * @var bool
	 */
	protected $is_debug;


	/**
	 * @return string
	 */
	public function getDefaultTheme() {
		return $this->default_theme;
	}

	/**
	 * @return boolean
	 */
	public function getIsDebug() {
		return $this->is_debug;
	}

	/**
	 * @return boolean
	 */
	public function getParseOnLoad() {
		return $this->parse_on_load;
	}

	/**
	 * @return string
	 */
	public function getVersion() {
		return $this->version;
	}


	/**
	 * @return string
	 */
	public function getThemeURI() {
		return $this->replaceConstants($this->theme_URI);
	}

	/**
	 * @return string
	 */
	public function getURI() {
		$dojo_js_URI = $this->getDojoJsURI();
		return dirname(dirname($dojo_js_URI)) . "/";
	}

	/**
	 * Get URI/URL where Dojo Toolkit dojo.js (dojo.xd.js) file is placed
	 *
	 * @return string
	 */
	public function getDojoJsURI(){

		return $this->replaceConstants($this->dojo_js_URI);
	}

	/**
	 * Replace constants in values
	 *
	 * @param $value
	 *
	 * @return string
	 */
	public function replaceConstants($value){

		$replacements = array(
			"VERSION" => $this->version,
			"THEME" => $this->default_theme
		);

		return Data_Text::replaceSystemConstants($value, $replacements);
	}

	/**
	 * Get URI/URL where dojo package file is placed
	 *
	 * @return string
	 */
	public function getDojoPackageURI()
	{
		return $this->replaceConstants($this->dojo_package_URI);
	}

}