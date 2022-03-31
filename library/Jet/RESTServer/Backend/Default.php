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
class RESTServer_Backend_Default extends BaseObject implements RESTServer_Backend
{
	
	
	/**
	 * @var ?string
	 */
	protected ?string $request_method = null;
	
	/**
	 * @var ?array
	 */
	protected ?array $request_data = null;
	
	
	/**
	 *
	 */
	public function init(): void
	{
		Debug::setOutputIsJSON( true );
		
		ErrorPages::setHandler( 401, [
			$this,
			'handleNotAuthorized'
		] );
		ErrorPages::setHandler( 404, [
			$this,
			'responseBadRequest'
		] );
	}
	
	/**
	 * @return string
	 */
	public function getRequestMethod(): string
	{
		if( !$this->request_method ) {
			$this->request_method = strtoupper( Http_Request::requestMethod() );
		}
		
		return $this->request_method;
	}
	
	/**
	 * @return array
	 */
	public function getRequestData(): array
	{
		
		if( $this->request_data === null ) {
			
			$data = Http_Request::rawPostData();
			
			if( !$data ) {
				$this->responseValidationError( ['Input is missing'] );
			}
			
			$data = json_decode( $data, true );
			
			if( $data === null ) {
				//$error = json_last_error();
				$this->responseValidationError( ['Input is not valid JSON'] );
			}
			
			$this->request_data = $data;
			
		}
		
		return $this->request_data;
		
	}
	
	/**
	 *
	 * @param string $header
	 * @param string $default_value
	 *
	 * @return string
	 */
	public function getHttpRequestHeader( string $header, string $default_value = '' ): string
	{
		$headers = Http_Request::headers();
		
		return $headers[$header] ?? $default_value;
	}
	
	
	/**
	 * @param string $message
	 */
	public function responseOK( string $message = '' ): void
	{
		$response = [
			'result' => 'OK',
		];
		
		if( $message ) {
			$response['message'] = $message;
		}
		
		$this->_response( json_encode( $response ) );
		
	}
	
	/**
	 * @param mixed $data
	 */
	public function responseData( mixed $data ): void
	{
		$this->_response( json_encode( $data ) );
	}
	
	
	/**
	 * @param string|int $code
	 * @param mixed $data
	 *
	 * @throws MVC_Controller_Exception
	 */
	public function responseError( string|int $code, mixed $data = null ): void
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
		
		
		$this->_response( json_encode( $error ), [], $http_code, $error_message );
		
	}
	
	
	/**
	 *
	 */
	public function handleNotAuthorized(): void
	{
		if( !Auth::getCurrentUser() ) {
			$this->responseError( RESTServer::ERR_CODE_AUTHORIZATION_REQUIRED );
		} else {
			$this->responseError( RESTServer::ERR_CODE_ACCESS_DENIED );
		}
	}
	
	/**
	 * @param array $errors
	 */
	public function responseValidationError( array $errors ): void
	{
		$this->responseError( RESTServer::ERR_CODE_VALIDATION_ERROR, $errors );
	}
	
	
	/**
	 * @param string|array $id
	 */
	public function responseUnknownItem( string|array $id ): void
	{
		if( !is_array( $id ) ) {
			$id = ['id' => $id];
		}
		
		$this->responseError( RESTServer::ERR_CODE_UNKNOWN_ITEM, $id );
	}
	
	/**
	 *
	 */
	public function responseBadRequest(): void
	{
		$this->responseError( RESTServer::ERR_CODE_REQUEST_ERROR, [] );
	}
	
	
	/**
	 * @param string $response_text
	 * @param array $http_headers
	 * @param int $http_code
	 * @param string $http_message
	 */
	protected function _response( string $response_text,
								  array $http_headers = [],
								  int $http_code = 200,
								  string $http_message = 'OK' )
	{
		
		$http_headers['Content-Type'] = 'application/json;charset=' . SysConf_Jet_Main::getCharset();
		
		Http_Headers::response(
			code: $http_code,
			headers: $http_headers,
			custom_response_message: $http_message
		);
		
		echo $response_text;
		Application::end();
		
	}
	
	
	/**
	 *
	 * @param DataModel_Fetch_Instances $data
	 *
	 * @return Data_Paginator
	 */
	public function handleDataPagination( DataModel_Fetch_Instances $data ): Data_Paginator
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
	public function handleOrderBy( DataModel_Fetch_Instances $data,
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
					$this->responseError( RESTServer::ERR_CODE_REQUEST_ERROR, ['Unknown sort item: ' . $oi] );
				}
				
				$order_by[] = $direction . $sort_items_map[$item];
			}
			
			$data->getQuery()->setOrderBy( $order_by );
			
			
		}
		
		return $data;
	}
	
}