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
abstract class Mvc_Controller_REST extends Mvc_Controller
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
	protected static $sort_get_param = 'sort';

	/**
	 * @var string
	 */
	protected static $page_get_param = 'page';

	/**
	 * @var string
	 */
	protected static $items_per_page_get_param = 'items_per_page';

	/**
	 * @var int
	 */
	protected static $default_items_per_page = 20;

	/**
	 * @var int
	 */
	protected static $max_items_per_page = 100;

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
	protected static $errors = [
		self::ERR_CODE_AUTHORIZATION_REQUIRED        => [ Http_Headers::CODE_401_UNAUTHORIZED, 'Access denied! Authorization required! ', ],
		self::ERR_CODE_ACCESS_DENIED                 => [ Http_Headers::CODE_401_UNAUTHORIZED, 'Access denied! Insufficient permissions! ', ],
		self::ERR_CODE_VALIDATION_ERROR              => [ Http_Headers::CODE_400_BAD_REQUEST,  'Validation error', ],
		self::ERR_CODE_REQUEST_ERROR                 => [ Http_Headers::CODE_400_BAD_REQUEST,  'Bad request' ],
		self::ERR_CODE_UNKNOWN_ITEM                  => [ Http_Headers::CODE_404_NOT_FOUND,    'Unknown item' ],
		self::ERR_CODE_COMMON                        => [ Http_Headers::CODE_400_BAD_REQUEST,  'Common error' ],
	];

	/**
	 * @var array
	 */
	protected $http_headers = null;

	/**
	 * @var string
	 */
	protected static $request_method;

	/**
	 * @var array
	 */
	protected static $request_data;

	/**
	 * @return string
	 */
	public static function getSortGetParam()
	{
		return static::$sort_get_param;
	}

	/**
	 * @param string $sort_get_param
	 */
	public static function setSortGetParam( $sort_get_param )
	{
		static::$sort_get_param = $sort_get_param;
	}

	/**
	 * @return string
	 */
	public static function getPageGetParam()
	{
		return static::$page_get_param;
	}

	/**
	 * @param string $page_get_param
	 */
	public static function setPageGetParam( $page_get_param )
	{
		static::$page_get_param = $page_get_param;
	}

	/**
	 * @return string
	 */
	public static function getItemsPerPageGetParam()
	{
		return static::$items_per_page_get_param;
	}

	/**
	 * @param string $items_per_page_get_param
	 */
	public static function setItemsPerPageGetParam( $items_per_page_get_param )
	{
		static::$items_per_page_get_param = $items_per_page_get_param;
	}

	/**
	 * @return int
	 */
	public static function getDefaultItemsPerPage()
	{
		return static::$default_items_per_page;
	}

	/**
	 * @param int $default_items_per_page
	 */
	public static function setDefaultItemsPerPage( $default_items_per_page )
	{
		static::$default_items_per_page = (int)$default_items_per_page;
	}

	/**
	 * @return int
	 */
	public static function getMaxItemsPerPage()
	{
		return static::$max_items_per_page;
	}

	/**
	 * @param int $max_items_per_page
	 */
	public static function setMaxItemsPerPage( $max_items_per_page )
	{
		static::$max_items_per_page = (int)$max_items_per_page;
	}




	/**
	 *
	 * @param Mvc_Page_Content_Interface $content
	 */
	public function __construct( Mvc_Page_Content_Interface $content )
	{
		parent::__construct( $content );

		Debug::setOutputIsJSON( true );

		ErrorPages::setHandler(401, [$this, 'responseAccessDenied']);
		ErrorPages::setHandler(404, [$this, 'responseBadRequest']);
	}

	/**
	 * @param string $path
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function resolve( $path )
	{
		throw new Exception('You have to implement resolver: '.get_called_class().'::resolve() ');

	}

	/**
	 * @return string
	 */
	public function getRequestMethod() {
		if(!static::$request_method) {
			static::$request_method = strtoupper( Http_Request::requestMethod() );
		}

		return static::$request_method;
	}

	/**
	 * @return bool|mixed
	 */
	public function getRequestData()
	{

		if(static::$request_data===null) {

			$data = Http_Request::rawPostData();

			if( !$data ) {
				$this->responseValidationError(['Input is missing']);
			}

			$data = json_decode( $data, true );

			if($data===null) {
				//$error = json_last_error();
				$this->responseValidationError(['Input is not valid JSON']);
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
	protected function getHttpRequestHeader( $header, $default_value = '' )
	{
		$headers = Http_Request::headers();

		return isset( $headers[$header] ) ? $headers[$header] : $default_value;
	}


	/**
	 * @param string $message
	 */
	public function responseOK( $message='' )
	{
		$response = [
			'result' => 'OK',
		];

		if($message) {
			$response['message'] = $message;
		}

		$this->_response( json_encode( $response ) );

	}

	/**
	 * @param mixed $data
	 */
	public function responseData(  $data )
	{
		$this->_response( json_encode($data) );
	}


	/**
	 * @param string|int $code
	 * @param mixed      $data
	 *
	 * @throws Mvc_Controller_Exception
	 */
	public function responseError( $code, $data = null )
	{
		if( !isset( static::$errors[$code] ) ) {
			throw new Mvc_Controller_Exception(
				'REST Error (code:'.$code.') is not specified! Please enter the error. Add '.get_class( $this ).'::$errors['.$code.'] entry.  ', Mvc_Controller_Exception::CODE_INVALID_RESPONSE_CODE
			);
		}

		list( $http_code, $error_message ) = static::$errors[$code];

		$error_code = $code;

		$error = [
			'result' => 'error',
			'error_code' => $error_code,
			'error_msg'  => $error_message,
		];

		if( $data ) {
			$error['error_data'] = $data;
		}


		$this->_response( json_encode( $error ), [], $http_code, $error_message );

	}



	/**
	 *
	 */
	public function responseAccessDenied( $module_action, $controller_action, $action_parameters )
	{
		if( !Auth::getCurrentUser() ) {
			$this->responseError( self::ERR_CODE_AUTHORIZATION_REQUIRED );
		} else {
			$this->responseError( self::ERR_CODE_ACCESS_DENIED );
		}
	}

	/**
	 * @param array $errors
	 */
	public function responseValidationError( array $errors )
	{
		$this->responseError( self::ERR_CODE_VALIDATION_ERROR, $errors );
	}


	/**
	 * @param string|array $id
	 */
	public function responseUnknownItem( $id )
	{
		if( !is_array( $id ) ) {
			$id = [ 'id' => $id ];
		}
		$this->responseError( self::ERR_CODE_UNKNOWN_ITEM, $id );
	}

	/**
	 *
	 */
	public function responseBadRequest()
	{
		$this->responseError( self::ERR_CODE_REQUEST_ERROR, [] );
	}


	/**
	 * @param string $response_text
	 * @param array  $http_headers
	 * @param int    $http_code
	 * @param string $http_message
	 */
	protected function _response( $response_text, array $http_headers = [], $http_code = 200, $http_message = 'OK' )
	{

		Http_Headers::sendHeader( 'HTTP/1.1 '.$http_code.' '.$http_message, true, $http_code );

		Http_Headers::sendHeader( 'Content-type:application/json;charset='.JET_CHARSET );

		foreach( $http_headers as $header => $header_value ) {
			Http_Headers::sendHeader( $header.': '.$header_value );
		}

		echo $response_text;
		Application::end();

	}


	/**
	 *
	 * @param DataModel_Fetch $data
	 *
	 * @return Data_Paginator
	 */
	protected function handleDataPagination( DataModel_Fetch $data )
	{
		$GET = Http_Request::GET();

		$current_page_no = $GET->getInt(static::getPageGetParam(), 1);
		$items_per_page = $GET->getInt(static::getItemsPerPageGetParam(), static::getDefaultItemsPerPage());

		if($items_per_page>static::getMaxItemsPerPage()) {
			$items_per_page = static::getMaxItemsPerPage();
		}

		$page_get_param = static::getPageGetParam();

		$paginator = new Data_Paginator($current_page_no, $items_per_page, function( $page_no ) use ($page_get_param) {
			return Http_Request::currentURL( [ $page_get_param => $page_no ] );
		});

		$paginator->setDataSource($data);

		return $paginator;
	}

	/**
	 *
	 * @param DataModel_Fetch $data
	 * @param array           $sort_items_map
	 *
	 * @return DataModel_Fetch
	 */
	protected function handleOrderBy( DataModel_Fetch $data, array $sort_items_map )
	{
		$GET = Http_Request::GET();

		if( ($order_by_param=$GET->getString(static::getSortGetParam())) ) {

			$order_by = [];

			foreach( explode(',', $order_by_param) as $oi ) {
				$direction = $oi[0];

				if( $direction!='+' && $direction!='-' ) {
					$direction = '+';
					$item = $oi;
				} else {
					$item = substr($oi, 1);
				}

				if(!isset($sort_items_map[$item])) {
					$this->responseError( self::ERR_CODE_REQUEST_ERROR, ['Unknown sort item: '.$oi] );
				}

				$order_by[] = $direction.$sort_items_map[$item];
			}

			$data->getQuery()->setOrderBy( $order_by );


		}

		return $data;
	}

}