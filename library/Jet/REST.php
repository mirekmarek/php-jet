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
class REST extends BaseObject
{
	const REQUEST_METHOD_GET = 'GET';
	const REQUEST_METHOD_POST = 'POST';
	const REQUEST_METHOD_PUT = 'PUT';
	const REQUEST_METHOD_DELETE = 'DELETE';

	const ERR_CODE_AUTHORIZATION_REQUIRED = 'authorization_required';
	const ERR_CODE_ACCESS_DENIED = 'access_denied';
	const ERR_CODE_VALIDATION_ERROR = 'validation_error';
	const ERR_CODE_REQUEST_ERROR = 'bad_request';
	const ERR_CODE_UNKNOWN_ITEM = 'unknown_item';
	const ERR_CODE_COMMON = 'common_error';


	/**
	 * @var string
	 */
	protected static string $sort_get_param = 'sort';

	/**
	 * @var string
	 */
	protected static string $page_get_param = 'page';

	/**
	 * @var string
	 */
	protected static string $items_per_page_get_param = 'items_per_page';

	/**
	 * @var int
	 */
	protected static int $default_items_per_page = 20;

	/**
	 * @var int
	 */
	protected static int $max_items_per_page = 100;

	/**
	 *
	 * Format:
	 *
	 * error_code => array(http_error_code, error_message)
	 *
	 * Example:
	 *
	 * <code>
	 * const ERROR_CODE_OBJECT_NOT_FOUND = 'ObjectNotFound';
	 *
	 * protected static $errors = array(
	 *      self::ERROR_CODE_OBJECT_NOT_FOUND => array(self::HTTP_NOT_FOUND, 'Object not found')
	 * );
	 * </code>
	 *
	 * @var array
	 */
	protected static array $errors = [
		self::ERR_CODE_AUTHORIZATION_REQUIRED => [
			Http_Headers::CODE_401_UNAUTHORIZED,
			'Access denied! Authorization required! ',
		],
		self::ERR_CODE_ACCESS_DENIED          => [
			Http_Headers::CODE_401_UNAUTHORIZED,
			'Access denied! Insufficient permissions! ',
		],
		self::ERR_CODE_VALIDATION_ERROR       => [
			Http_Headers::CODE_400_BAD_REQUEST,
			'Validation error',
		],
		self::ERR_CODE_REQUEST_ERROR          => [
			Http_Headers::CODE_400_BAD_REQUEST,
			'Bad request'
		],
		self::ERR_CODE_UNKNOWN_ITEM           => [
			Http_Headers::CODE_404_NOT_FOUND,
			'Unknown item'
		],
		self::ERR_CODE_COMMON                 => [
			Http_Headers::CODE_400_BAD_REQUEST,
			'Common error'
		],
	];

	/**
	 * @var array|null
	 */
	protected array|null $http_headers = null;

	/**
	 * @var ?string
	 */
	protected static ?string $request_method = null;

	/**
	 * @var ?array
	 */
	protected static ?array $request_data = null;

	/**
	 * @param string $code
	 * @param int $http_code
	 * @param string $message
	 */
	public static function defineError( string $code, int $http_code, string $message )
	{
		static::$errors[$code] = [
			$http_code,
			$message
		];
	}

	/**
	 * @return string
	 */
	public static function getSortGetParam(): string
	{
		return static::$sort_get_param;
	}

	/**
	 * @param string $sort_get_param
	 */
	public static function setSortGetParam( string $sort_get_param ): void
	{
		static::$sort_get_param = $sort_get_param;
	}

	/**
	 * @return string
	 */
	public static function getPageGetParam(): string
	{
		return static::$page_get_param;
	}

	/**
	 * @param string $page_get_param
	 */
	public static function setPageGetParam( string $page_get_param ): void
	{
		static::$page_get_param = $page_get_param;
	}

	/**
	 * @return string
	 */
	public static function getItemsPerPageGetParam(): string
	{
		return static::$items_per_page_get_param;
	}

	/**
	 * @param string $items_per_page_get_param
	 */
	public static function setItemsPerPageGetParam( string $items_per_page_get_param ): void
	{
		static::$items_per_page_get_param = $items_per_page_get_param;
	}

	/**
	 * @return int
	 */
	public static function getDefaultItemsPerPage(): int
	{
		return static::$default_items_per_page;
	}

	/**
	 * @param int $default_items_per_page
	 */
	public static function setDefaultItemsPerPage( int $default_items_per_page ): void
	{
		static::$default_items_per_page = $default_items_per_page;
	}

	/**
	 * @return int
	 */
	public static function getMaxItemsPerPage(): int
	{
		return static::$max_items_per_page;
	}

	/**
	 * @param int $max_items_per_page
	 */
	public static function setMaxItemsPerPage( int $max_items_per_page ): void
	{
		static::$max_items_per_page = $max_items_per_page;
	}

	/**
	 *
	 */
	public static function init(): void
	{
		Debug::setOutputIsJSON( true );

		ErrorPages::setHandler( 401, [
			static::class,
			'handleNotAuthorized'
		] );
		ErrorPages::setHandler( 404, [
			static::class,
			'responseBadRequest'
		] );
	}

	/**
	 * @return string
	 */
	public static function getRequestMethod(): string
	{
		if( !static::$request_method ) {
			static::$request_method = strtoupper( Http_Request::requestMethod() );
		}

		return static::$request_method;
	}

	/**
	 * @return array
	 */
	public static function getRequestData(): array
	{

		if( static::$request_data === null ) {

			$data = Http_Request::rawPostData();

			if( !$data ) {
				static::responseValidationError( ['Input is missing'] );
			}

			$data = json_decode( $data, true );

			if( $data === null ) {
				//$error = json_last_error();
				static::responseValidationError( ['Input is not valid JSON'] );
			}

			static::$request_data = $data;

		}

		return static::$request_data;

	}

	/**
	 * Get request HTTP header or default value if does not exist
	 *
	 * @param string $header
	 * @param string $default_value
	 *
	 * @return string
	 */
	public static function getHttpRequestHeader( string $header,
	                                             string $default_value = '' ): string
	{
		$headers = Http_Request::headers();

		return $headers[$header] ?? $default_value;
	}


	/**
	 * @param string $message
	 */
	public static function responseOK( string $message = '' ): void
	{
		$response = [
			'result' => 'OK',
		];

		if( $message ) {
			$response['message'] = $message;
		}

		static::_response( json_encode( $response ) );

	}

	/**
	 * @param mixed $data
	 */
	public static function responseData( mixed $data ): void
	{
		static::_response( json_encode( $data ) );
	}


	/**
	 * @param string|int $code
	 * @param mixed $data
	 *
	 * @throws Mvc_Controller_Exception
	 */
	public static function responseError( string|int $code, mixed $data = null ): void
	{
		if( !isset( static::$errors[$code] ) ) {
			throw new Mvc_Controller_Exception(
				'REST Error (code:' . $code . ') is not specified! Please enter the error. Add ' . static::class . '::$errors[' . $code . '] entry.  ',
				Mvc_Controller_Exception::CODE_INVALID_RESPONSE_CODE
			);
		}

		[
			$http_code,
			$error_message
		] = static::$errors[$code];

		$error_code = $code;

		$error = [
			'result' => 'error',
			'error_code' => $error_code,
			'error_msg' => $error_message,
		];

		if( $data ) {
			$error['error_data'] = $data;
		}


		static::_response( json_encode( $error ), [], $http_code, $error_message );

	}


	/**
	 *
	 */
	public static function handleNotAuthorized(): void
	{
		if( !Auth::getCurrentUser() ) {
			static::responseError( self::ERR_CODE_AUTHORIZATION_REQUIRED );
		} else {
			static::responseError( self::ERR_CODE_ACCESS_DENIED );
		}
	}

	/**
	 * @param array $errors
	 */
	public static function responseValidationError( array $errors ): void
	{
		static::responseError( self::ERR_CODE_VALIDATION_ERROR, $errors );
	}


	/**
	 * @param string|array $id
	 */
	public static function responseUnknownItem( string|array $id ): void
	{
		if( !is_array( $id ) ) {
			$id = ['id' => $id];
		}

		static::responseError( self::ERR_CODE_UNKNOWN_ITEM, $id );
	}

	/**
	 *
	 */
	public static function responseBadRequest(): void
	{
		static::responseError( self::ERR_CODE_REQUEST_ERROR, [] );
	}


	/**
	 * @param string $response_text
	 * @param array $http_headers
	 * @param int $http_code
	 * @param string $http_message
	 */
	protected static function _response( string $response_text,
	                                     array $http_headers = [],
	                                     int $http_code = 200,
	                                     string $http_message = 'OK' )
	{

		Http_Headers::sendHeader( 'HTTP/1.1 ' . $http_code . ' ' . $http_message, true, $http_code );

		Http_Headers::sendHeader( 'Content-type:application/json;charset=' . SysConf_Jet::getCharset() );

		foreach( $http_headers as $header => $header_value ) {
			Http_Headers::sendHeader( $header . ': ' . $header_value );
		}

		echo $response_text;
		Application::end();

	}


	/**
	 *
	 * @param DataModel_Fetch_Instances $data
	 *
	 * @return Data_Paginator
	 */
	public static function handleDataPagination( DataModel_Fetch_Instances $data ): Data_Paginator
	{
		$GET = Http_Request::GET();

		$current_page_no = $GET->getInt( static::getPageGetParam(), 1 );
		$items_per_page = $GET->getInt( static::getItemsPerPageGetParam(), static::getDefaultItemsPerPage() );

		if( $items_per_page > static::getMaxItemsPerPage() ) {
			$items_per_page = static::getMaxItemsPerPage();
		}

		$page_get_param = static::getPageGetParam();

		$paginator = new Data_Paginator( $current_page_no, $items_per_page, function( $page_no ) use ( $page_get_param ) {
			return Http_Request::currentURL( [$page_get_param => $page_no] );
		} );

		$paginator->setDataSource( $data );

		return $paginator;
	}

	/**
	 *
	 * @param DataModel_Fetch_Instances $data
	 * @param array $sort_items_map
	 *
	 * @return DataModel_Fetch_Instances
	 */
	public static function handleOrderBy( DataModel_Fetch_Instances $data,
	                                      array $sort_items_map ): DataModel_Fetch_Instances
	{
		$GET = Http_Request::GET();

		if( ($order_by_param = $GET->getString( static::getSortGetParam() )) ) {

			$order_by = [];

			foreach( explode( ',', $order_by_param ) as $oi ) {
				$direction = $oi[0];

				if( $direction != '+' && $direction != '-' ) {
					$direction = '+';
					$item = $oi;
				} else {
					$item = substr( $oi, 1 );
				}

				if( !isset( $sort_items_map[$item] ) ) {
					static::responseError( self::ERR_CODE_REQUEST_ERROR, ['Unknown sort item: ' . $oi] );
				}

				$order_by[] = $direction . $sort_items_map[$item];
			}

			$data->getQuery()->setOrderBy( $order_by );


		}

		return $data;
	}

}