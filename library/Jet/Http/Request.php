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
class Http_Request extends BaseObject
{

	/**
	 * PHP super global $_POST original value
	 *
	 * @var ?array
	 */
	protected static ?array $_POST = null;


	/**
	 * PHP super global $_GET original value
	 *
	 * @var ?array
	 */
	protected static ?array $_GET = null;


	/**
	 * @var bool
	 */
	protected static bool $is_initialized = false;


	/**
	 *
	 * @var ?Data_Array
	 */
	protected static ?Data_Array $GET = null;

	/**
	 *
	 * @var ?Data_Array
	 */
	protected static ?Data_Array $POST = null;


	/**
	 * @var ?string|null
	 */
	protected static ?string $raw_post_data = null;

	/**
	 * @var ?array
	 */
	protected static ?array $headers = null;

	/**
	 * @var bool
	 */
	protected static bool $post_max_size_exceeded = false;


	/**
	 *
	 * @param bool $hide_PHP_request_data
	 *
	 */
	public static function initialize( ?bool $hide_PHP_request_data = null ): void
	{

		if( static::$is_initialized ) {
			return;
		}

		static::$_POST = $_POST;
		static::$_GET = $_GET;
		static::$is_initialized = true;

		if(
			isset( $_SERVER['REQUEST_METHOD'] ) &&
			strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' &&
			empty( $_FILES ) &&
			empty( $_POST ) ) {

			static::$post_max_size_exceeded = true;
		}

		if($hide_PHP_request_data===null) {
			$hide_PHP_request_data = SysConf_Jet_Http::getHideRequest();
		}

		if( $hide_PHP_request_data ) {
			Http_Request::hidePHPRequestData();
		}
	}

	/**
	 *
	 */
	protected static function hidePHPRequestData(): void
	{
		$_GET = new Http_Request_Trap();
		$_POST = new Http_Request_Trap();
		$_REQUEST = new Http_Request_Trap();

	}

	/**
	 * @return bool
	 */
	public static function postMaxSizeExceeded(): bool
	{
		return self::$post_max_size_exceeded;
	}


	/**
	 *
	 * @param array $set_GET_params (optional)
	 * @param array $unset_GET_params (optional)
	 * @param null|string $set_anchor (optional, default: do not change current state)
	 *
	 * @return string
	 */
	public static function currentURI( array   $set_GET_params = [],
	                                   array   $unset_GET_params = [],
	                                   ?string $set_anchor = null ): string
	{
		if(
			$set_GET_params ||
			$unset_GET_params
		) {
			[$URI] = explode( '?', $_SERVER['REQUEST_URI'] );

			$GET = Http_Request::GET()->getRawData();

			foreach( $set_GET_params as $k => $v ) {
				$GET[$k] = $v;
			}

			foreach( $unset_GET_params as $k ) {
				if( isset( $GET[$k] ) ) {
					unset( $GET[$k] );
				}
			}

			if( $GET ) {
				$URI .= '?' . http_build_query( $GET );
			}
		} else {
			$URI = $_SERVER['REQUEST_URI'];
		}

		if( $set_anchor !== null ) {
			[$URI] = explode( '#', $URI );

			if( $set_anchor ) {
				$URI .= '#' . $set_anchor;
			}

		}

		return $URI;
	}

	/**
	 *
	 * @param array $set_GET_params (optional)
	 * @param array $unset_GET_params (optional)
	 * @param ?string $set_anchor (optional, default: do not change current state)
	 *
	 * @return string
	 */
	public static function currentURL( array $set_GET_params = [],
	                                   array $unset_GET_params = [],
	                                   ?string $set_anchor = null ): string
	{
		return static::baseURL() . static::currentURI( $set_GET_params, $unset_GET_params, $set_anchor );
	}

	/**
	 *
	 * @param bool $include_query_string (optional, default: true)
	 *
	 * @param bool $force_SSL
	 *
	 * @return string
	 */
	public static function URL( bool $include_query_string = true, bool $force_SSL = false ): string
	{
		$request_URI = $_SERVER['REQUEST_URI'];

		if( !$include_query_string ) {
			[$request_URI] = explode( '?', $request_URI );
		}

		return static::baseURL($force_SSL) . $request_URI;
	}

	/**
	 * @param bool $force_SSL
	 * @return string
	 */
	public static function baseURL( bool $force_SSL = false ): string
	{
		$scheme = 'http';
		$host = $_SERVER['HTTP_HOST'];
		$port = '';

		if(
			static::isHttps() ||
			$force_SSL
		) {
			$scheme = 'https';
			if(
				$_SERVER['SERVER_PORT'] != '443' &&
				!$force_SSL
			) {
				$port = ':' . $_SERVER['SERVER_PORT'];
			}
		} else {
			if( $_SERVER['SERVER_PORT'] != '80' ) {
				$port = ':' . $_SERVER['SERVER_PORT'];
			}
		}

		return $scheme . '://' . $host . $port;
	}



	/**
	 * @return string
	 */
	public static function schema(): string
	{
		return static::isHttps() ? 'https' : 'http';
	}


	/**
	 *
	 * @return Data_Array
	 */
	public static function GET(): Data_Array
	{
		if( !static::$GET ) {
			static::$GET = new Data_Array( static::$_GET );
		}

		return static::$GET;
	}

	/**
	 *
	 * @return Data_Array
	 */
	public static function POST(): Data_Array
	{
		if( !static::$POST ) {
			static::$POST = new Data_Array( static::$_POST );
		}

		return static::$POST;
	}


	/**
	 * @see http://php.net/manual/en/wrappers.php  php://input
	 *
	 * @return string
	 */
	public static function rawPostData(): string
	{
		if( static::$raw_post_data === null ) {
			static::$raw_post_data = file_get_contents( 'php://input' );
		}

		return static::$raw_post_data;
	}


	/**
	 *
	 * @return string
	 */
	public static function requestMethod(): string
	{
		return isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( $_SERVER['REQUEST_METHOD'] ) : 'GET';
	}


	/**
	 *
	 * @return bool
	 */
	public static function isHttp(): bool
	{
		return !static::isHttps();
	}

	/**
	 *
	 * @return bool
	 */
	public static function isHttps(): bool
	{
		return (
				isset( $_SERVER['HTTPS'] ) &&
				$_SERVER['HTTPS'] == 'on'
			) || (
				isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) &&
				$_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
			);
	}

	/**
	 *
	 * @return string
	 */
	public static function clientIP(): string
	{

		if( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}

		if( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			return $_SERVER['HTTP_CLIENT_IP'];
		}

		if( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			return $_SERVER['REMOTE_ADDR'];
		}

		return 'unknown';
	}

	/**
	 *
	 * @return string
	 */
	public static function clientUserAgent(): string
	{
		if( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			return $_SERVER['HTTP_USER_AGENT'];
		}

		return 'unknown';
	}


	/**
	 *
	 * @return array
	 */
	public static function headers(): array
	{
		if( static::$headers !== null ) {
			return static::$headers;
		}

		if( function_exists( 'apache_request_headers' ) ) {
			$headers = apache_request_headers();
		} else {

			$headers = [];
			foreach( $_SERVER as $key => $value ) {
				if( !str_starts_with( $key, 'HTTP_' ) ) {
					continue;
				}

				$header = str_replace(
					' ', '-', ucwords(
						str_replace(
							'_', ' ', strtolower( substr( $key, 5 ) )
						)
					)
				);

				$headers[$header] = $value;
			}
		}

		static::$headers = $headers;

		return static::$headers;
	}
}