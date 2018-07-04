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
class AJAX
{

	/**
	 * @param mixed  $response_data (will be encoded by json_encode)
	 * @param array  $http_headers
	 * @param int    $http_code
	 * @param string $http_message
	 */
	public static function response( $response_data, array $http_headers = [], $http_code = 200, $http_message = 'OK' )
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
	 * @param bool  $success
	 * @param array $snippets
	 * @param array $data
	 */
	public static function formResponse( $success, array $snippets = [], $data = [] )
	{

		$response = [
			'result' => $success ? 'ok' : 'error',
			'data' => $data,
			'snippets' => $snippets
		];


		static::response( $response );
	}

}