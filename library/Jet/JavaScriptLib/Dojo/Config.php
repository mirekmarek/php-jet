<?php
/**
 *
 *
 *
 * Global dojo configuration
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package JavasSriptLib
 */
namespace Jet;

/**
 * Class JavaScriptLib_Dojo_Config
 *
 */
class JavaScriptLib_Dojo_Config extends BaseObject {

	/**
	 *
	 * @var string
	 */
	protected $version = '1.9.2';

	/**
	 * @var string
	 */
	protected $default_theme = 'claro';

	/**
	 * @var string
	 */
	protected $dojo_js_URI = '//ajax.googleapis.com/ajax/libs/dojo/%VERSION%/dojo/dojo.js';

	/**
	 * @var string
	 */
	protected $theme_URI = '//ajax.googleapis.com/ajax/libs/dojo/%VERSION%/dijit/themes/%THEME%/%THEME%.css';


	/**
	 * @var bool
	 */
	protected $package_enabled = true;

	/**
	 * @var bool
	 */
	protected $parse_on_load = true;

	/**
	 * @var bool
	 */
	protected $is_debug = false;


	public function __construct() {
		if(defined('DOJO_VERSION')) {
			$this->version = DOJO_VERSION;
		}

		if(defined('DOJO_DEFAULT_THEME')) {
			$this->default_theme = DOJO_DEFAULT_THEME;
		}
		if(defined('DOJO_JS_URI')) {
			$this->dojo_js_URI = DOJO_JS_URI;
		}
		if(defined('DOJO_THEME_URI')) {
			$this->theme_URI = DOJO_THEME_URI;
		}
		if(defined('DOJO_PACKAGE_ENABLED')) {
			$this->package_enabled = DOJO_PACKAGE_ENABLED;
		}
		if(defined('DOJO_PARSE_ON_LOAD')) {
			$this->parse_on_load = DOJO_PARSE_ON_LOAD;
		}
		if(defined('DOJO_IS_DEBUG')) {
			$this->is_debug = DOJO_IS_DEBUG;
		}

	}


	/**
	 * @return string
	 */
	public function getDefaultTheme() {
		return $this->default_theme;
	}

	/**
	 * @param string $default_theme
	 */
	public function setDefaultTheme($default_theme)
	{
		$this->default_theme = $default_theme;
	}

	/**
	 * @return bool
	 */
	public function getIsDebug() {
		return $this->is_debug;
	}

	/**
	 * @param bool $is_debug
	 */
	public function setIsDebug($is_debug)
	{
		$this->is_debug = $is_debug;
	}

	/**
	 * @return bool
	 */
	public function getParseOnLoad() {
		return $this->parse_on_load;
	}

	/**
	 * @param bool $parse_on_load
	 */
	public function setParseOnLoad($parse_on_load)
	{
		$this->parse_on_load = $parse_on_load;
	}

	/**
	 * @return string
	 */
	public function getVersion() {
		return $this->version;
	}

	/**
	 * @param string $version
	 */
	public function setVersion($version)
	{
		$this->version = $version;
	}


	/**
	 * @return string
	 */
	public function getThemeURI() {
		return $this->theme_URI;
	}

	/**
	 * @param string $theme_URI
	 */
	public function setThemeURI($theme_URI)
	{
		$this->theme_URI = $theme_URI;
	}

	/**
	 * @return string
	 */
	public function getBaseURI() {
		$dojo_js_URI = $this->getDojoJsURI();
		return dirname(dirname($dojo_js_URI)) . '/';
	}


	/**
	 *
	 * @return string
	 */
	public function getDojoJsURI(){

		return $this->dojo_js_URI;
	}

	/**
	 * @param string $dojo_js_URI
	 */
	public function setDojoJsURI($dojo_js_URI)
	{
		$this->dojo_js_URI = $dojo_js_URI;
	}

	/**
	 * @return bool
	 */
	public function getPackageEnabled() {
		return $this->package_enabled;
	}

	/**
	 * @param bool $package_enabled
	 */
	public function setPackageEnabled($package_enabled)
	{
		$this->package_enabled = $package_enabled;
	}



}