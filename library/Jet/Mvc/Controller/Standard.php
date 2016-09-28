<?php
/**
 *
 *
 *
 * Default controller
 * Applied for the standard mode.
 *
 * @see Mvc/readme.txt
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
 * @subpackage Mvc_Controller
 */
namespace Jet;

abstract class Mvc_Controller_Standard extends Mvc_Controller_Abstract {
	/**
	 * @param string $module_action
	 * @param string $controller_action
	 * @param array $action_parameters
	 *
	 */
	public function responseAclAccessDenied( $module_action, $controller_action, $action_parameters ) {
        Mvc::getCurrentSite()->handleAccessDenied();
	}

	/**
	 * @param mixed $response_data (will be encoded by json_encode)
	 * @param array $http_headers
	 * @param int $http_code
	 * @param string $http_message
	 */
	protected function ajaxResponse($response_data, array $http_headers= [], $http_code = 200, $http_message='OK' ) {
		header( 'HTTP/1.1 '.$http_code.' '.$http_message );
		header('Content-type:text/json;charset=UTF-8');

		foreach( $http_headers as $header=>$header_value ) {
			header( $header.': '.$header_value );
		}

		Debug_Profiler::setOutputIsJSON(true);

		echo json_encode( $response_data );
		Application::end();

	}

}