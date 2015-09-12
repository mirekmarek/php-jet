<?php
/**
 *
 *
 *
 * Default router config class
 *
 * @see Mvc/readme.txt
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Router
 */
namespace Jet;

/**
 * Class Mvc_Router_Config_Default
 *
 * @JetConfig:data_path = 'mvc_router'
 */
class Mvc_Router_Config_Default extends Mvc_Router_Config_Abstract {

	/**
	 * @JetConfig:type = Jet\Config::TYPE_BOOL
	 * @JetConfig:default_value = true
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_label = 'Enable cache: '
	 * 
	 * @var bool
	 */
	protected $cache_enabled;
	
	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:default_value = 'MySQL'
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_label = 'Cache backend type: '
	 * @JetConfig:form_field_type = 'Select'
	 * @JetConfig:form_field_get_select_options_callback = ['Jet\Mvc_Router_Config_Default', 'getCacheBackendTypesList']
	 * 
	 * @var string
	 */
	protected $cache_backend_type;
	
	/**
	 * @var array
	 */
	protected $cache_backend_options;


	/**
	 * @return boolean
	 */
	public function getCacheEnabled() {
		return $this->cache_enabled;
	}

	/**
	 * @return array
	 */
	public function getCacheBackendOptions() {
		return $this->cache_backend_options;
	}

	/**
	 * @return string
	 */
	public function getCacheBackendType() {
		return $this->cache_backend_type;
	}

	/**
	 * @return array
	 */
	public static function getCacheBackendTypesList() {
		return static::getAvailableHandlersList( JET_LIBRARY_PATH.'Jet/Mvc/Router/Cache/Backend/' );
	}

}