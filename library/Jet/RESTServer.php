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
class RESTServer extends BaseObject
{
	public const REQUEST_METHOD_GET = 'GET';
	public const REQUEST_METHOD_POST = 'POST';
	public const REQUEST_METHOD_PUT = 'PUT';
	public const REQUEST_METHOD_DELETE = 'DELETE';

	public const ERR_CODE_AUTHORIZATION_REQUIRED = 'authorization_required';
	public const ERR_CODE_ACCESS_DENIED = 'access_denied';
	public const ERR_CODE_VALIDATION_ERROR = 'validation_error';
	public const ERR_CODE_REQUEST_ERROR = 'bad_request';
	public const ERR_CODE_UNKNOWN_ITEM = 'unknown_item';
	public const ERR_CODE_COMMON = 'common_error';
	
	
	
	protected static ?RESTServer_Backend $backend = null;
	
	/**
	 * @return RESTServer_Backend
	 */
	public static function getBackend() : RESTServer_Backend
	{
		if(!static::$backend) {
			static::$backend = new RESTServer_Backend_Default();
		}
		
		return static::$backend;
	}
	
	/**
	 * @param RESTServer_Backend $backend
	 */
	public static function setBackend( RESTServer_Backend $backend ) : void
	{
		static::$backend = $backend;
	}
	
	
	/**
	 *
	 */
	public static function init(): void
	{
		static::getBackend()->init();
	}

	/**
	 * @return string
	 */
	public static function getRequestMethod(): string
	{
		return static::getBackend()->getRequestMethod();
	}

	/**
	 * @return array<string,mixed>
	 */
	public static function getRequestData(): array
	{
		return static::getBackend()->getRequestData();
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
		return static::getBackend()->getHttpRequestHeader( $header, $default_value );
	}


	/**
	 * @param string $message
	 */
	public static function responseOK( string $message = '' ): void
	{
		static::getBackend()->responseOK( $message );
	}

	/**
	 * @param mixed $data
	 */
	public static function responseData( mixed $data ): void
	{
		static::getBackend()->responseData( $data );
	}


	/**
	 * @param string|int $code
	 * @param mixed $data
	 */
	public static function responseError( string|int $code, mixed $data = null ): void
	{
		static::getBackend()->responseError( $code, $data );
	}


	/**
	 *
	 */
	public static function handleNotAuthorized(): void
	{
		static::getBackend()->handleNotAuthorized();
	}

	/**
	 * @param array<string> $errors
	 */
	public static function responseValidationError( array $errors ): void
	{
		static::getBackend()->responseValidationError( $errors );
	}


	/**
	 * @param string|array<string,string|int> $id
	 */
	public static function responseUnknownItem( string|array $id ): void
	{
		static::getBackend()->responseUnknownItem( $id );
	}

	/**
	 *
	 */
	public static function responseBadRequest(): void
	{
		static::getBackend()->responseBadRequest();
	}
	

	/**
	 *
	 * @param DataModel_Fetch_Instances $data
	 *
	 * @return Data_Paginator
	 */
	public static function handleDataPagination( DataModel_Fetch_Instances $data ): Data_Paginator
	{
		return static::getBackend()->handleDataPagination( $data );
	}

	/**
	 *
	 * @param DataModel_Fetch_Instances $data
	 * @param array<string,string> $sort_items_map
	 *
	 * @return DataModel_Fetch_Instances
	 */
	public static function handleOrderBy( DataModel_Fetch_Instances $data, array $sort_items_map ): DataModel_Fetch_Instances
	{
		return static::getBackend()->handleOrderBy( $data, $sort_items_map );
	}

}