<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Pages
 */
namespace Jet;

class Mvc_Pages_Page_Content_Default extends Mvc_Pages_Page_Content_Abstract {

	/**
	 * @var string
	 */
	protected static $__data_model_model_name = "Jet_Mvc_Pages_Page_Content";
	/**
	 * @var string
	 */
	protected static $__data_model_parent_model_class_name = "Jet\\Mvc_Pages_Page_Default";
	/**
	 * @var array
	 */
	protected static $__data_model_properties_definition = array(
		"ID" => array(
			"type" => self::TYPE_ID,
			"is_ID" => true
		),
		"module_name" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 50
		),
		"controller_class_suffix" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 255
		),
		"controller_action" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 50
		),
		"controller_action_parameters" => array(
			"type" => self::TYPE_ARRAY,
			"item_type" => self::TYPE_STRING
		),
		"output_position" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 50
		),
		"output_position_required" => array(
			"type" => self::TYPE_BOOL
		),
		"output_position_order" => array(
			"type" => self::TYPE_INT
		)
	);

	/**
	 * @var string
	 */
	protected $Jet_Mvc_Pages_Page_ID = "";
	/**
	 * @var string
	 */
	protected $Jet_Mvc_Pages_Page_locale = "";
	/**
	 * @var string
	 */
	protected $Jet_Mvc_Pages_Page_site_ID = "";

	/**
	 * @var string
	 */
	protected $ID  = "";

	/**
	 * @var string
	 */
	protected $module_name = "";

	/**
	 * @var string
	 */
	protected $controller_class_suffix = "";
	/**
	 * @var string
	 */
	protected $controller_action = "";

	/**
	 * @var array
	 */
	protected $controller_action_parameters = array();

	/**
	 * @var string
	 */
	protected $output_position = "";
	/**
	 * @var bool
	 */
	protected $output_position_required = true;
	/**
	 * @var int
	 */
	protected $output_position_order = 0;

	/**
	 * @param string $module_name (optional)
	 * @param string $controller_class_suffix (optional)
	 * @param string $controller_action (optional)
	 * @param array $controller_action_parameters (optional)
	 * @param string $output_position (optional)
	 * @param bool $output_position_required (optional)
	 * @param int $output_position_order (optional)
	 */
	public function __construct(
		$module_name="",
		$controller_class_suffix = "",
		$controller_action="",
		$controller_action_parameters=array(),
		$output_position="",
		$output_position_required=true,
		$output_position_order=0
	) {

		if($module_name) {
			$this->generateID();

			$this->module_name = $module_name;
			$this->controller_class_suffix = $controller_class_suffix;
			$this->controller_action = $controller_action;
			$this->controller_action_parameters = $controller_action_parameters;

			$this->output_position = $output_position;
			$this->output_position_required = (bool)$output_position_required;
			$this->output_position_order = (int)$output_position_order;
		}
	}

	/**
	 * @return mixed|null
	 */
	public function getArrayKeyValue() {
		return $this->ID;
	}


	/**
	 * @return string
	 */
	public function getModuleName() {
		return $this->module_name;
	}

	/**
	 * @param string $module_name
	 */
	public function setModuleName( $module_name ) {
		$this->module_name = $module_name;
	}

	/**
	 * @return string
	 */
	public function getControllerClassSuffix() {
		return $this->controller_class_suffix;
	}

	/**
	 * @param string $controller_class_suffix
	 */
	public function setControllerClassSuffix($controller_class_suffix) {
		$this->controller_class_suffix = $controller_class_suffix;
	}

	/**
	 * @return string
	 */
	public function getControllerAction() {
		return $this->controller_action;
	}

	/**
	 * @param string $controller_action
	 */
	public function setControllerAction( $controller_action ) {
		$this->controller_action = $controller_action;
	}

	/**
	 * @return string
	 */
	public function getOutputPosition() {
		return $this->output_position;
	}

	/**
	 * @param string $output_position
	 */
	public function setOutputPosition( $output_position ) {
		$this->output_position = $output_position;
	}

	/**
	 * @return bool
	 */
	public function getOutputPositionRequired() {
		return $this->output_position_required;
	}

	/**
	 * @param bool $output_position_required
	 */
	public function setOutputPositionRequired( $output_position_required ) {
		$this->output_position_required = (bool)$output_position_required;
	}

	/**
	 * @return int
	 */
	public function getOutputPositionOrder() {
		return $this->output_position_order;
	}

	/**
	 * @param int $output_position_order
	 */
	public function setOutputPositionOrder( $output_position_order ) {
		$this->output_position_order = (int)$output_position_order;
	}

	/**
	 * @return array
	 */
	public function getControllerActionParameters() {
		return $this->controller_action_parameters;
	}

	/**
	 * @param array $controller_action_parameters
	 */
	public function setControllerActionParameters( array $controller_action_parameters ) {
		$this->controller_action_parameters = $controller_action_parameters;
	}
}