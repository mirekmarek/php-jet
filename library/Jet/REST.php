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
	 * @throws MVC_Controller_Exception
	 */
	public static function responseError( string|int $code, mixed $data = null ): void
	{
		$errors = SysConf_Jet_REST::getErrors();

		if( !isset( $errors[$code] ) ) {
			throw new MVC_Controller_Exception(
				'REST Error (code:' . $code . ') is not specified!',
				MVC_Controller_Exception::CODE_INVALID_RESPONSE_CODE
			);
		}

		[
			$http_code,
			$error_message
		] = $errors[$code];

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

		Http_Headers::sendHeader( 'Content-type:application/json;charset=' . SysConf_Jet_Main::getCharset() );

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

		$current_page_no = $GET->getInt( SysConf_Jet_REST::getPageGetParam(), 1 );
		$items_per_page = $GET->getInt( SysConf_Jet_REST::getItemsPerPageGetParam(), SysConf_Jet_REST::getDefaultItemsPerPage() );

		if( $items_per_page > SysConf_Jet_REST::getMaxItemsPerPage() ) {
			$items_per_page = SysConf_Jet_REST::getMaxItemsPerPage();
		}

		$page_get_param = SysConf_Jet_REST::getPageGetParam();

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

		if( ($order_by_param = $GET->getString( SysConf_Jet_REST::getSortGetParam() )) ) {

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