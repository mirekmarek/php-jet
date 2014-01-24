<?php 
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Application
 * @subpackage Application_Config
 */
namespace Jet;

/**
 * Class Application_Config
 *
 * @JetConfig:data_path = 'main'
 */
class Application_Config extends Config_Application {

	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:description = 'default chmod() mask for dirs'
	 * @JetConfig:default_value = '0777'
	 * @JetConfig:form_field_label = 'I/O dirs mode (Unix access): '
	 *
	 * @var string
	 */
	protected $IO_dirs_chmod;

	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:description = 'default chmod() mask for files'
	 * @JetConfig:default_value = '0666'
	 * @JetConfig:form_field_label = 'I/O files mode (Unix access): '
	 *
	 * @var string
	 */
	protected $IO_files_chmod;

	/**
	 *
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'Europe/Prague'
	 * @JetConfig:description = 'Default timezone for PHP'
	 * @JetConfig:form_field_label = 'Default timezone: '
	 *
	 * @var string
	 */
	protected $default_time_zone;

	/**
	 * @JetConfig:type = Jet\Config::TYPE_BOOL
	 * @JetConfig:is_required = false
	 * @JetConfig:default_value = true
	 * @JetConfig:form_field_label = 'Hide $_GET, $_POST and $_REQUEST:'
	 *
	 * @var bool
	 */
	protected $hide_PHP_request_data;

	/**
	 * @JetConfig:type = Jet\Config::TYPE_ARRAY
	 * @JetConfig:item_type = Jet\Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_get_default_value_callback = array('\Jet\Debug_ErrorHandler', 'getDefaultErrorHandlers')
	 * @JetConfig:form_field_get_select_options_callback = array('\Jet\Debug_ErrorHandler', 'getHandlersList')
	 * 
	 * @var array
	 */
	protected $error_handlers;


	/**
	 * @return int
	 */
	public function getIODirsChmod() {
		return octdec($this->IO_dirs_chmod);
	}

	/**
	 * @return int
	 */
	public function getIOFilesChmod() {
		return octdec($this->IO_files_chmod);
	}

	/**
	 * @return boolean
	 */
	public function getHidePHPRequestData() {
		return $this->hide_PHP_request_data;
	}

	/**
	 * @return string
	 */
	public function getDefaultTimeZone() {
		return $this->default_time_zone;
	}

	/**
	 * @return array
	 */
	public function getErrorHandlers() {
		return $this->error_handlers;
	}

	/**
	 *
	 * @return array
	 */
	public function toArray() {

		$error_handlers = array();

		foreach($this->error_handlers as $i=>$eh) {
			if(is_int($i) || !is_array($eh)) {
				$error_handlers[$eh] = array();
			} else {
				$error_handlers[$i] = $eh;
			}

		}

		$this->error_handlers = $error_handlers;

		return parent::toArray();
	}

}