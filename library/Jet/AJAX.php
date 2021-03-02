<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class AJAX
{

	/**
	 * @param mixed $response_data (will be encoded by json_encode)
	 * @param array $http_headers
	 * @param int $http_code
	 * @param string $http_message
	 */
	public static function response( mixed $response_data, array $http_headers = [], int $http_code = 200, string $http_message = 'OK' ): void
	{
		ob_end_clean();
		Debug::setOutputIsJSON( true );

		header( 'HTTP/1.1 ' . $http_code . ' ' . $http_message );
		header( 'Content-type:text/json;charset=UTF-8' );

		foreach( $http_headers as $header => $header_value ) {
			header( $header . ': ' . $header_value );
		}

		echo json_encode( $response_data );
		Application::end();

	}

	/**
	 * @param bool $success
	 * @param array $snippets
	 * @param array $data
	 */
	public static function formResponse( bool $success, array $snippets = [], array $data = [] ): void
	{

		$response = [
			'result'   => $success ? 'ok' : 'error',
			'data'     => $data,
			'snippets' => $snippets
		];


		static::response( $response );
	}

}