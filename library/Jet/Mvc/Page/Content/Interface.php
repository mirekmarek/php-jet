<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Mvc_Page_Content_Interface
 *
 */
interface Mvc_Page_Content_Interface
{

	/**
	 * @param Mvc_Page_Interface $page
	 * @param array              $data
	 *
	 * @return Mvc_Page_Content_Interface
	 */
	public static function createByData( Mvc_Page_Interface $page, array $data );

	/**
	 * @param Mvc_Page_Interface $page
	 */
	public function setPage( Mvc_Page_Interface $page );

	/**
	 * @return Mvc_Page_Interface
	 */
	public function getPage();


	/**
	 * @param string $controller_class
	 */
	public function setControllerClass( $controller_class );

	/**
	 * @return string
	 */
	public function getControllerClass();


	/**
	 * @param string $controller_name
	 */
	public function setControllerName( $controller_name );

	/**
	 * @return string
	 */
	public function getControllerName();

	/**
	 * @return string
	 */
	public function getModuleName();

	/**
	 * @param string $module_name
	 */
	public function setModuleName( $module_name );

	/**
	 * @return Application_Module|bool
	 */
	public function getModuleInstance();

	/**
	 * @return string
	 */
	public function getControllerAction();

	/**
	 * @param string $controller_action
	 */
	public function setControllerAction( $controller_action );

	/**
	 * @return array
	 */
	public function getParameters();

	/**
	 * @param array $parameters
	 */
	public function setParameters( array $parameters );

	/**
	 * @param string $key
	 * @param mixed  $default_value
	 *
	 * @return mixed
	 */
	public function getParameter( $key, $default_value = null );

	/**
	 * @param string $key
	 * @param mixed  $value
	 */
	public function setParameter( $key, $value );

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function parameterExists( $key );

	/**
	 * @return string|callable
	 */
	public function getOutput();

	/**
	 * @param string|callable $output
	 */
	public function setOutput( $output );

	/**
	 * @return string
	 */
	public function getOutputPosition();

	/**
	 * @param string $output_position
	 */
	public function setOutputPosition( $output_position );

	/**
	 * @return int
	 */
	public function getOutputPositionOrder();

	/**
	 * @param int $output_position_order
	 */
	public function setOutputPositionOrder( $output_position_order );

	/**
	 *
	 * @return Mvc_Controller|bool
	 */
	public function getControllerInstance();

	/**
	 *
	 */
	public function dispatch();

	/**
	 * @return array
	 */
	public function toArray();

}