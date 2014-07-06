<?php
/**
 *
 *
 *
 * Global dojo configuration
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Javascript
 * @subpackage Javascript_Lib
 */
namespace Jet;

/**
 * Class Javascript_Lib_Dojo_Config
 *
 * @JetConfig:data_path = '/js_libs/Dojo'
 * @JetConfig:section_is_obligatory = false
 */
class Javascript_Lib_Dojo_Config extends Config_Application {

	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:default_value = '1.9.2'
	 * @JetConfig:is_required = false
	 * @JetConfig:form_field_label = 'Dojo version'
	 *
	 * @var string
	 */
	protected $version;

	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:validation_regexp = '/^[a-zA-Z0-9_\-]+$/'
	 * @JetConfig:default_value = 'claro'
	 * @JetConfig:is_required = false
	 * @JetConfig:form_field_label = 'Dijit theme'
	 *
	 * @var string
	 */
	protected $default_theme;

	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @//JetConfig:default_value = '%JET_PUBLIC_SCRIPTS_URI%dojo/%VERSION%/dojo/dojo.js'
	 * @JetConfig:default_value = '//ajax.googleapis.com/ajax/libs/dojo/%VERSION%/dojo/dojo.js'
	 * @JetConfig:is_required = false
	 * @JetConfig:form_field_label = 'dojo.js URI'
	 *
	 * @var string
	 */
	protected $dojo_js_URI = '';

	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @//JetConfig:default_value = '%JET_PUBLIC_SCRIPTS_URI%dojo/%VERSION%/dijit/themes/%THEME%/%THEME%.css'
	 * @JetConfig:default_value = '//ajax.googleapis.com/ajax/libs/dojo/%VERSION%/dijit/themes/%THEME%/%THEME%.css'
	 * @JetConfig:is_required = false
	 * @JetConfig:form_field_label = 'Dijit theme URI'
	 *
	 * @var string
	 */
	protected $theme_URI = '';


	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:default_value = '%JET_PUBLIC_SCRIPTS_PATH%dojo/%VERSION%/'
	 * @JetConfig:is_required = false
	 * @JetConfig:form_field_label = 'Base path'
	 *
	 * @var string
	 */
	protected $base_path = '';

	/**
	 * @JetConfig:type = Jet\Config::TYPE_BOOL
	 * @JetConfig:default_value = true
	 * @JetConfig:form_field_label = 'Create package'
	 * @JetConfig:is_required = false
	 *
	 */
	protected $package_enabled = true;

	/**
	 * @JetConfig:type = Jet\Config::TYPE_BOOL
	 * @JetConfig:default_value = true
	 * @JetConfig:form_field_label = 'Parse on load'
	 * @JetConfig:is_required = false
	 *
	 * @var bool
	 */
	protected $parse_on_load;

	/**
	 * @JetConfig:type = Jet\Config::TYPE_BOOL
	 * @JetConfig:default_value = false
	 * @JetConfig:form_field_label = 'Dojo debug'
	 * @JetConfig:is_required = false
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
		return $this->theme_URI;
	}

	/**
	 * @return string
	 */
	public function getBaseURI() {
		$dojo_js_URI = $this->getDojoJsURI();
		return dirname(dirname($dojo_js_URI)) . '/';
	}

	/**
	 * @return string
	 */
	public function getBasePath() {
		return $this->base_path;
	}

	/**
	 * Get URI/URL where Dojo Toolkit dojo.js (dojo.xd.js) file is placed
	 *
	 * @return string
	 */
	public function getDojoJsURI(){

		return $this->dojo_js_URI;
	}

	/**
	 * @return bool
	 */
	public function getPackageEnabled() {
		return $this->package_enabled;
	}



}