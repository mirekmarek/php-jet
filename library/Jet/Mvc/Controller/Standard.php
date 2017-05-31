<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
abstract class Mvc_Controller_Standard extends Mvc_Controller
{
	/**
	 * @param string $module_action
	 * @param string $controller_action
	 * @param array  $action_parameters
	 *
	 */
	public function responseAccessDenied( $module_action, $controller_action, $action_parameters )
	{
		ErrorPages::handleUnauthorized();
		Application::end();
	}

	/**
	 * @param mixed  $response_data (will be encoded by json_encode)
	 * @param array  $http_headers
	 * @param int    $http_code
	 * @param string $http_message
	 */
	protected function ajaxResponse( $response_data, array $http_headers = [], $http_code = 200, $http_message = 'OK' )
	{
		Debug::setOutputIsJSON( true );

		header( 'HTTP/1.1 '.$http_code.' '.$http_message );
		header( 'Content-type:text/json;charset=UTF-8' );

		foreach( $http_headers as $header => $header_value ) {
			header( $header.': '.$header_value );
		}

		echo json_encode( $response_data );
		Application::end();

	}

	/**
	 * @param Form  $form
	 * @param bool  $success
	 * @param array $snippets
	 * @param array $data
	 */
	protected function ajaxFormResponse( Form $form, $success, array $snippets = [], $data = [] )
	{

		$response = [
			'form_id' => $form->getId(),
			'result' => $success ? 'ok' : 'error',
			'data' => $data,
			'snippets' => $snippets
		];


		$this->ajaxResponse( $response );
	}

}