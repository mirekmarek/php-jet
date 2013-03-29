<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Pages
 */
namespace Jet;

abstract class Mvc_Pages_Page_Content_Abstract extends DataModel_Related_1toN {
	/**
	 * @var string
	 */
	protected static $__factory_class_name = "Jet\\Mvc_Factory";
	/**
	 * @var string
	 */
	protected static $__factory_class_method = "getPageContentInstance";
	/**
	 * @var string
	 */
	protected static $__factory_must_be_instance_of_class_name = "Jet\\Mvc_Pages_Page_Content_Abstract";

	/**
	 * @param string $module_name (optional)
	 * @param string $controller_class_suffix (optional)
	 * @param string $controller_action (optional)
	 * @param array $controller_action_parameters (optional)
	 * @param string $output_position (optional)
	 * @param bool $output_position_required (optional)
	 * @param int $output_position_order (optional)
	 */
	abstract public function __construct(
					$module_name="",
					$controller_class_suffix = "",
					$controller_action="",
					$controller_action_parameters=array(),
					$output_position="",
					$output_position_required=true,
					$output_position_order=0
		);

	/**
	 * @return string
	 */
	abstract public function getModuleName();

	/**
	 * @param string $module_name
	 */
	abstract public function setModuleName( $module_name );

	/**
	 * @return string
	 */
	abstract public function getControllerClassSuffix();

	/**
	 * @param string $controller_class_suffix
	 */
	abstract public function setControllerClassSuffix($controller_class_suffix);

	/**
	 * @return string
	 */
	abstract public function getControllerAction();

	/**
	 * @param string $controller_action
	 */
	abstract public function setControllerAction( $controller_action );

	/**
	 * @return string
	 */
	abstract public function getOutputPosition();

	/**
	 * @param string $output_position
	 */
	abstract public function setOutputPosition( $output_position );

	/**
	 * @return bool
	 */
	abstract public function getOutputPositionRequired();

	/**
	 * @param bool $output_position_required
	 */
	abstract public function setOutputPositionRequired( $output_position_required );

	/**
	 * @return int
	 */
	abstract public function getOutputPositionOrder();

	/**
	 * @param int $output_position_order
	 */
	abstract public function setOutputPositionOrder( $output_position_order );

	/**
	 * @return array
	 */
	abstract public function getControllerActionParameters();

	/**
	 * @param array $controller_action_parameters
	 */
	abstract public function setControllerActionParameters( array $controller_action_parameters );
}