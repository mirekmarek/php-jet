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

class Application_Config extends Config_Application {
	/**
	 * @var string
	 */
	protected static $__config_data_path = "main";

	/**
	 * @var array
	 */
	protected static $__config_properties_definition = array(

		"IO_dirs_chmod" => array(
			"type" => Config::TYPE_STRING,
			"is_required" => true,
			"description" => "default chmod() mask for dirs",
			"default_value" => "0777",
			"form_field_label" => "I/O dirs mode (Unix access): "
		),

		"IO_files_chmod" => array(
			"type" => Config::TYPE_STRING,
			"is_required" => true,
			"description" => "default chmod() mask for files",
			"default_value" => "0666",
			"form_field_label" => "I/O files mode (Unix access): "
		),

		"default_time_zone" => array(
			"type" => Config::TYPE_STRING,
			"is_required" => true,
			"default_value" => "Europe/Prague",
			"description" => "Default timezone for PHP",
			"form_field_label" => "Default timezone: "
		),

		"hide_PHP_request_data" => array(
			"type" => Config::TYPE_BOOL,
			"is_required" => false,
			"default_value" => true,
			"form_field_label" => "Hide \$_GET, \$_POST and \$_REQUEST:"
		),

		"error_handlers" => array(
			"type" => Config::TYPE_ARRAY,
			"item_type" => Config::TYPE_STRING,
			"is_required" => true,
			"form_field_get_default_value_callback" => array("\\Jet\\Debug_ErrorHandler", "getDefaultErrorHandlers"),
			"form_field_get_select_options_callback" => array("\\Jet\\Debug_ErrorHandler", "getHandlersList")
		)

	);

	/**
	 * @var string
	 */
	protected $IO_dirs_chmod;

	/**
	 * @var string
	 */
	protected $IO_files_chmod;

	/**
	 * @var string
	 */
	protected $default_time_zone;

	/**
	 * @var bool
	 */
	protected $hide_PHP_request_data;

	/**
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