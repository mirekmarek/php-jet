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
 * Class Mvc_Page_Content_Abstract
 *
 * @JetFactory:class = 'Mvc_Factory'
 * @JetFactory:method = 'getPageContentInstance'
 * @JetFactory:mandatory_parent_class = 'Mvc_Page_Content_Abstract'
 *
 * @JetDataModel:name = 'page_content'
 * @JetDataModel:parent_model_class_name = 'Mvc_Page_Abstract'
 * @JetDataModel:ID_class_name = 'DataModel_ID_UniqueString'
 */
abstract class Mvc_Page_Content_Abstract extends DataModel_Related_1toN {

    /**
     * @param mixed $ID
     *
     */
    abstract public function setID( $ID );

    /**
     * @param array $data
     * @return void
     */
    abstract public function setData( array $data );

    /**
     * @param bool $is_dynamic
     */
    abstract public function setIsDynamic($is_dynamic);

    /**
     * @return bool
     */
    abstract public function getIsDynamic();


    /**
     * @param string $custom_service_type
     */
    abstract public function setCustomServiceType($custom_service_type);

    /**
     * @return string
     */
    abstract public function getCustomServiceType();

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
	abstract public function getControllerAction();

	/**
	 * @param string $controller_action
	 */
	abstract public function setControllerAction( $controller_action );

    /**
     * @return array
     */
    abstract public function getControllerActionParameters();

    /**
     * @param array $controller_action_parameters
     */
    abstract public function setControllerActionParameters( array $controller_action_parameters );

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
     * @param string $parser_URL_method_name
     */
    abstract function setParserURLMethodName($parser_URL_method_name);

    /**
     * @return string
     */
    abstract function getParserURLMethodName();


    /**
     * @param array|Mvc_Layout_OutputPart[] $output_parts
     */
    abstract public function setOutputParts( array $output_parts);

    /**
     * @return Mvc_Layout_OutputPart[]|null
     */
    abstract public function getOutputParts();

    /**
     * @param Mvc_Page_Abstract $page
     */
    abstract public function dispatch( Mvc_Page_Abstract $page );

}