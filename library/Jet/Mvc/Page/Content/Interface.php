<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	public static function createByData( Mvc_Page_Interface $page, array $data ) : Mvc_Page_Content_Interface;

	/**
	 * @param Mvc_Page_Interface $page
	 */
	public function setPage( Mvc_Page_Interface $page ) : void;

	/**
	 * @return Mvc_Page_Interface
	 */
	public function getPage() : Mvc_Page_Interface;


	/**
	 * @param string $controller_class
	 */
	public function setControllerClass( string $controller_class ) : void;

	/**
	 * @return string
	 */
	public function getControllerClass() : string;


	/**
	 * @param string $controller_name
	 */
	public function setControllerName( string $controller_name ) : void;

	/**
	 * @return string
	 */
	public function getControllerName() : string;

	/**
	 * @return string
	 */
	public function getModuleName() : string;

	/**
	 * @param string $module_name
	 */
	public function setModuleName( string $module_name ) : void;

	/**
	 * @return Application_Module|bool
	 */
	public function getModuleInstance() : Application_Module|bool;

	/**
	 * @return string
	 */
	public function getControllerAction() : string;

	/**
	 * @param string $controller_action
	 */
	public function setControllerAction( string $controller_action ) : void;

	/**
	 * @return array
	 */
	public function getParameters() : array;

	/**
	 * @param array $parameters
	 */
	public function setParameters( array $parameters ) : void;

	/**
	 * @param string $key
	 * @param mixed  $default_value
	 *
	 * @return mixed
	 */
	public function getParameter( string $key, mixed $default_value = null ) : mixed;

	/**
	 * @param string $key
	 * @param mixed  $value
	 */
	public function setParameter( string $key, mixed $value ) : void;

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function parameterExists( string $key ) : bool;

	/**
	 * @return string|callable
	 */
	public function getOutput() : string|callable;

	/**
	 * @param string|callable $output
	 */
	public function setOutput( string|callable $output ) : void;

	/**
	 * @return string
	 */
	public function getOutputPosition() : string;

	/**
	 * @param string $output_position
	 */
	public function setOutputPosition( string $output_position ) : void;

	/**
	 * @return int
	 */
	public function getOutputPositionOrder() : int;

	/**
	 * @param int $output_position_order
	 */
	public function setOutputPositionOrder( int $output_position_order ) : void;

	/**
	 *
	 * @return Mvc_Controller|bool
	 */
	public function getControllerInstance() : Mvc_Controller|bool;

	/**
	 *
	 */
	public function dispatch() : void;

	/**
	 * @return array
	 */
	public function toArray() : array;

}