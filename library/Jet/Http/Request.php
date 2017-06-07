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
class Http_Request extends BaseObject
{

	/**
	 * PHP super global $_POST original value
	 *
	 * @var array
	 */
	protected static $_POST;


	/**
	 * PHP super global $_GET original value
	 *
	 * @var array
	 */
	protected static $_GET;


	/**
	 * @var bool
	 */
	protected static $is_initialized = false;


	/**
	 *
	 * @var Data_Array
	 */
	protected static $GET = null;

	/**
	 *
	 * @var Data_Array
	 */
	protected static $POST = null;


	/**
	 * @var string
	 */
	protected static $raw_post_data = null;

	/**
	 * @var array
	 */
	protected static $headers = null;


	/**
	 *
	 * @param bool|null $hide_PHP_request_data (optional, default: false)
	 *
	 */
	public static function initialize( $hide_PHP_request_data = false )
	{

		if( static::$is_initialized ) {
			return;
		}

		static::$_POST = $_POST;
		static::$_GET = $_GET;
		static::$is_initialized = true;

		if( $hide_PHP_request_data ) {
			Http_Request::hidePHPRequestData();
		}
	}

	/**
	 *
	 */
	public static function hidePHPRequestData()
	{
		$_GET = new Http_Request_Trap();
		$_POST = new Http_Request_Trap();
		$_REQUEST = new Http_Request_Trap();
	}


	/**
	 *
	 * @param array       $set_GET_params (optional)
	 * @param array       $unset_GET_params (optional)
	 * @param null|string $set_anchor (optional, default: do not change current state)
	 *
	 * @return string
	 */
	public static function currentURI( array $set_GET_params = [], array $unset_GET_params = [], $set_anchor = null )
	{
		if(
			$set_GET_params ||
			$unset_GET_params
		) {
			list( $URI ) = explode( '?', $_SERVER['REQUEST_URI'] );

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
				$URI .= '?'.http_build_query( $GET );
			}
		} else {
			$URI = $_SERVER['REQUEST_URI'];
		}

		if( $set_anchor!==null ) {
			list( $URI ) = explode( '#', $URI );

			if( $set_anchor ) {
				$URI .= '#'.$set_anchor;
			}

		}

		return $URI;
	}

	/**
	 *
	 * @param array       $set_GET_params (optional)
	 * @param array       $unset_GET_params (optional)
	 * @param null|string $set_anchor (optional, default: do not change current state)
	 *
	 * @return string
	 */
	public static function currentURL( array $set_GET_params = [], array $unset_GET_params = [], $set_anchor = null )
	{
		return static::baseURL() . static::currentURI( $set_GET_params, $unset_GET_params, $set_anchor );
	}

	/**
	 *
	 * @param bool $include_query_string (optional, default: true)
	 *
	 * @return string
	 */
	public static function URL( $include_query_string = true )
	{
		$request_URI = $_SERVER['REQUEST_URI'];

		if( !$include_query_string ) {
			list( $request_URI ) = explode( '?', $request_URI );
		}

		return static::baseURL().$request_URI;
	}

	/**
	 *
	 *
	 * @return string
	 */
	public static function baseURL( )
	{
		$scheme = 'http';
		$host = $_SERVER['HTTP_HOST'];
		$port = '';

		if( static::isHttps() ) {
			$scheme = 'https';
			if( $_SERVER['SERVER_PORT']!='443' ) {
				$port = ':'.$_SERVER['SERVER_PORT'];
			}
		} else {
			if( $_SERVER['SERVER_PORT']!='80' ) {
				$port = ':'.$_SERVER['SERVER_PORT'];
			}
		}

		return $scheme.'://'.$host.$port;
	}


	/**
	 * @return string
	 */
	public static function schema()
	{
		return static::isHttps() ? 'https' : 'http';
	}


	/**
	 *
	 * @return Data_Array
	 */
	public static function GET()
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
	public static function POST()
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
	public static function rawPostData()
	{
		if( static::$raw_post_data===null ) {
			static::$raw_post_data = file_get_contents( 'php://input' );
		}

		return static::$raw_post_data;
	}


	/**
	 *
	 * @return string
	 */
	public static function requestMethod()
	{
		return isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( $_SERVER['REQUEST_METHOD'] ) : 'GET';
	}


	/**
	 * Is HTTP?
	 *
	 * @return bool
	 */
	public static function isHttp()
	{
		return !static::isHttps();
	}

	/**
	 * Is HTTPS?
	 *
	 * @return bool
	 */
	public static function isHttps()
	{
		return (
			isset( $_SERVER['HTTPS'] ) &&
			$_SERVER['HTTPS'] == 'on'
		) || (
			isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
			$_SERVER['HTTP_X_FORWARDED_PROTO']=='https'
		);
	}

	/**
	 * Get client's IP address
	 *
	 * @return string
	 */
	public static function clientIP()
	{

		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}

		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		}

		if(isset( $_SERVER['REMOTE_ADDR'] )) {
			return $_SERVER['REMOTE_ADDR'];
		}

		return 'unknown';
	}

	/**
	 * Get client's user agent
	 *
	 * @return string
	 */
	public static function clientUserAgent()
	{
		if(isset($_SERVER['HTTP_USER_AGENT'])) {
			return $_SERVER['HTTP_USER_AGENT'];
		}

		return 'unknown';
	}



	/**
	 *
	 * @return array
	 */
	public static function headers()
	{
		if( static::$headers!==null ) {
			return static::$headers;
		}

		if( function_exists( 'apache_request_headers' ) ) {
			$headers = apache_request_headers();
		} else {

			$headers = [];
			foreach( $_SERVER as $key => $value ) {
				if( substr( $key, 0, 5 )!='HTTP_' ) {
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