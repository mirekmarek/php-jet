<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
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
	 */
	public static function commonResponse( mixed $response_data, array $http_headers = [], int $http_code = 200 ): void
	{
		if( ob_get_level() ) {
			ob_end_clean();
		}
		Debug::setOutputIsJSON( true );

		Http_Headers::response($http_code, $http_headers);

		echo json_encode( $response_data );
		Application::end();

	}

	/**
	 * @param bool $success
	 * @param array $snippets
	 * @param array $data
	 */
	public static function operationResponse( bool $success, array $snippets = [], array $data = [] ): void
	{

		$response = [
			'result'   => $success ? 'ok' : 'error',
			'data'     => $data,
			'snippets' => $snippets
		];


		static::commonResponse( $response );
	}

}