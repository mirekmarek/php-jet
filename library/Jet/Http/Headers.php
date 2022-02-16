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
class Http_Headers
{

	const CODE_200_OK = 200;
	const CODE_201_CREATED = 201;
	const CODE_202_ACCEPTED = 202;
	const CODE_204_NO_CONTENT = 204;
	const CODE_205_RESET_CONTENT = 205;
	const CODE_206_PARTIAL_CONTENT = 206;

	const CODE_301_MOVED_PERMANENTLY = 301;
	const CODE_302_FOUND = 302;
	const CODE_302_MOVED_TEMPORARY = 302;
	const CODE_303_SEE_OTHER = 303;
	const CODE_304_NOT_MODIFIED = 304;
	const CODE_307_TEMPORARY_REDIRECT = 307;
	const CODE_308_PERMANENT_REDIRECT = 308;

	const CODE_400_BAD_REQUEST = 400;
	const CODE_401_UNAUTHORIZED = 401;
	const CODE_402_PAYMENT_REQUIRED = 402;
	const CODE_403_FORBIDDEN = 403;
	const CODE_404_NOT_FOUND = 404;
	const CODE_405_METHOD_NOT_ALLOWED = 405;
	const CODE_406_NOT_ACCEPTABLE = 406;
	const CODE_407_PROXY_AUTHENTICATION_REQUIRED = 407;
	const CODE_408_REQUEST_TIMEOUT = 408;
	const CODE_409_CONFLICT = 409;
	const CODE_410_GONE = 410;
	const CODE_411_LENGTH_REQUIRED = 411;
	const CODE_412_PRECONDITION_FAILED = 412;
	const CODE_413_REQUEST_ENTITY_TOO_LARGE = 413;
	const CODE_414_REQUEST_URI_TOO_LONG = 414;
	const CODE_415_UNSUPPORTED_MEDIA_TYPE = 415;
	const CODE_416_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
	const CODE_417_EXPECTATION_FAILED = 417;
	const CODE_425_UNORDERED_COLLECTION = 425;
	const CODE_426_UPGRADE_REQUIRED = 426;
	const CODE_428_PRECONDITION_REQUIRED = 428;
	const CODE_429_TOO_MANY_REQUESTS = 429;
	const CODE_431_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
	const CODE_444_NO_RESPONSE = 444;
	const CODE_451_UNAVAILABLE_FOR_LEGAL_REASONS = 451;

	const CODE_500_INTERNAL_SERVER_ERROR = 500;
	const CODE_501_NOT_IMPLEMENTED = 501;
	const CODE_502_BAD_GATEWAY = 502;
	const CODE_503_SERVICE_UNAVAILABLE = 503;
	const CODE_504_GATEWAY_TIMEOUT = 504;
	const CODE_505_HTTP_VERSION_NOT_SUPPORTED = 505;
	const CODE_506_VARIANT_ALSO_NEGOTIATES = 506;
	const CODE_509_BANDWIDTH_LIMIT_EXCEEDED = 509;
	const CODE_510_NOT_EXTENDED = 510;
	const CODE_511_NETWORK_AUTHENTICATION_REQUIRED = 511;
	const CODE_598_NETWORK_READ_TIMEOUT_ERROR = 598;
	const CODE_599_NETWORK_CONNECT_TIMEOUT_ERROR = 599;


	/**
	 * @param int $code
	 * @param array $headers
	 * @param string $custom_response_message
	 *
	 */
	public static function response( int $code, array $headers = [], string $custom_response_message='' ): void
	{
		$header = static::createResponseHeader( $code, $custom_response_message );

		static::sendHeader( $header, true, $code );

		foreach( $headers as $header => $value ) {

			if( is_int( $header ) ) {
				static::sendHeader( $value );
			} else {
				if( is_array( $value ) ) {
					$value = implode( '; ', $value );
				}

				static::sendHeader( $header . ': ' . $value );
			}

		}
	}

	/**
	 * @param int $http_code
	 * @param string $custom_response_message
	 *
	 * @return string
	 */
	protected static function createResponseHeader( int $http_code, string $custom_response_message='' ): string
	{
		if($custom_response_message) {
			$message = $custom_response_message;
		} else {
			$message = static::getResponseMessage( $http_code );
		}

		return 'HTTP/' . SysConf_Jet_Http::getHttpVersion() . ' ' . $http_code . ' ' . $message;
	}

	/**
	 * Returns HTTP text message (or false if unknown HTTP code)
	 *
	 * @param int $http_code
	 *
	 * @return string
	 */
	protected static function getResponseMessage( int $http_code ): string
	{
		$response_messages = SysConf_Jet_Http::getResponseMessages();

		if( !isset( $response_messages[$http_code] ) ) {
			throw new Http_Request_Exception('Unknown HTTP response code '.$http_code.' - response message is not defined');
		}

		return $response_messages[$http_code];
	}

	/**
	 * PHP header function replacement
	 * CLI Unit test support
	 *
	 * @param string $header
	 * @param bool $replace
	 * @param int $http_response_code
	 *
	 */
	protected static function sendHeader( string $header, bool $replace = true, int $http_response_code = 0 ): void
	{
		$f_name = SysConf_Jet_Http::getHeaderFunctionName();

		$f_name( $header, $replace, $http_response_code );
	}



	/**
	 * @param int $http_code
	 * @param string $target_URL
	 * @param array $headers
	 * @param bool $application_end
	 */
	public static function redirect( int $http_code, string $target_URL, array $headers = [], bool $application_end = true ): void
	{
		$headers['Location'] = $target_URL;
		static::response(
			$http_code,
			$headers
		);

		if( $application_end ) {
			Application::end();
		}

	}

	/**
	 *
	 * @param string $target_URL target URL
	 * @param array $headers (optional, default: none)
	 * @param bool $application_end (optional, default: true)
	 */
	public static function movedPermanently( string $target_URL, array $headers = [], bool $application_end = true ): void
	{
		static::redirect(
			static::CODE_301_MOVED_PERMANENTLY,
			$target_URL,
			$headers,
			$application_end
		);
	}

	/**
	 * Temporary redirection - 302
	 *
	 * @param string $target_URL target URL
	 * @param array $headers (optional, default: none)
	 * @param bool $application_end (optional, default: true)
	 */
	public static function movedTemporary( string $target_URL, array $headers = [], bool $application_end = true ): void
	{
		static::redirect(
			static::CODE_302_MOVED_TEMPORARY,
			$target_URL,
			$headers,
			$application_end
		);
	}



	/**
	 * Reload current page
	 *
	 * @param array $set_GET_params (optional)
	 * @param array $unset_GET_params (optional)
	 * @param null|string $set_anchor (optional, default: do not change current state)
	 *
	 * @param bool $application_end (optional, default: true)
	 */
	public static function reload( array $set_GET_params = [], array $unset_GET_params = [], ?string $set_anchor = null, bool $application_end = true ): void
	{
		static::sendHeader(
			'Location: ' . Http_Request::currentURI( $set_GET_params, $unset_GET_params, $set_anchor )
		);
		if( $application_end ) {
			Application::end();
		}
	}


	/**
	 * Send headers for file download
	 *
	 * @param string $file_name
	 * @param string $file_mime
	 * @param int $file_size
	 * @param bool $force_download (optional, force download header, default: false)
	 */
	public static function sendDownloadFileHeaders( string $file_name, string $file_mime, int $file_size, bool $force_download = false ): void
	{
		$headers = [
			'Content-Type' => $file_mime,
			'Content-Length' => $file_size,
			'Cache-Control' => 'public, must-revalidate, max-age=0',
			'Pragma' => 'public',
			'Expires' => 'Tue, 01 February 2011 07:00:00 GMT',
			'Last-Modified' => gmdate( 'D, d M Y H:i:s' ) . ' GMT' ,
		];

		if( $force_download ) {
			$headers['Content-Disposition'] = 'attachment; filename="' . $file_name . '";';
			$headers['Content-Transfer-Encoding'] = 'binary';
		} else {
			$headers['Content-Transfer-Encoding'] = 'inline; filename="' . $file_name . '";';
		}

		static::response(static::CODE_200_OK, $headers);
	}

}