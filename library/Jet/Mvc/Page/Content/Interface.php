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
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Pages
 */
namespace Jet;

/**
 * Class Mvc_Page_Content_Interface
 *
 */
interface Mvc_Page_Content_Interface {

    /**
     * @param mixed $ID
     *
     */
    public function setID( $ID );

    /**
     * @param array $data
     * @return void
     */
    public function setData( array $data );

    /**
     * @param bool $is_dynamic
     */
    public function setIsDynamic($is_dynamic);

    /**
     * @return bool
     */
    public function getIsDynamic();


    /**
     * @param string $custom_service_type
     */
    public function setCustomServiceType($custom_service_type);

    /**
     * @return string
     */
    public function getCustomServiceType();

	/**
	 * @return string
	 */
	public function getModuleName();

	/**
	 * @param string $module_name
	 */
	public function setModuleName( $module_name );

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
    public function getControllerActionParameters();

    /**
     * @param array $controller_action_parameters
     */
    public function setControllerActionParameters( array $controller_action_parameters );

	/**
	 * @return string
	 */
	public function getOutputPosition();

	/**
	 * @param string $output_position
	 */
	public function setOutputPosition( $output_position );

	/**
	 * @return bool
	 */
	public function getOutputPositionRequired();

	/**
	 * @param bool $output_position_required
	 */
	public function setOutputPositionRequired( $output_position_required );

	/**
	 * @return int
	 */
	public function getOutputPositionOrder();

	/**
	 * @param int $output_position_order
	 */
	public function setOutputPositionOrder( $output_position_order );


    /**
     * @param string $parser_URL_method_name
     */
    public function setParserURLMethodName($parser_URL_method_name);

    /**
     * @return string
     */
    public function getParserURLMethodName();


    /**
     * @param array|Mvc_Layout_OutputPart[] $output_parts
     */
    public function setOutputParts( array $output_parts);

    /**
     * @return Mvc_Layout_OutputPart[]|null
     */
    public function getOutputParts();

    /**
     * @param Mvc_Page_Interface $page
     */
    public function dispatch( Mvc_Page_Interface $page );

}