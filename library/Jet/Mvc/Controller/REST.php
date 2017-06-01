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


	//TODO: prepracovat strankovani
	/**
	 * @var string
	 */
	protected static $pagination_request_http_header_name = 'Range';
	/**
	 * @var string
	 */
	protected static $pagination_request_http_header_regexp = '/^items=([0-9]{1,})-([0-9]{1,})$/';
	/**
	 * @var string
	 */
	protected static $pagination_response_http_header_name = 'Content-Range';
	/**
	 * @var string
	 */
	protected static $pagination_response_http_header_value_template = 'items %RANGE_FROM%-%RANGE_TO%/%COUNT%';

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
	 *
	 * @param Mvc_Page_Content_Interface $content
	 */
	public function __construct( Mvc_Page_Content_Interface $content )
	{
		parent::__construct( $content );

		Debug::setOutputIsJSON( true );
		//TODO: error stranky (např 401) napojit na kontroler
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
			static::$request_method = strtoupper( Http_Request::getRequestMethod() );
		}

		return static::$request_method;
	}

	/**
	 * @return bool|mixed
	 */
	public function getRequestData()
	{

		if(static::$request_data===null) {

			$data = Http_Request::getRawPostData();

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
		$headers = Http_Request::getHeaders();

		return isset( $headers[$header] ) ? $headers[$header] : $default_value;
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
	public function responseOK()
	{
		$this->_response( json_encode( 'OK' ) );

	}

	/**
	 * @param BaseObject_Serializable_JSON $data
	 */
	public function responseData( BaseObject_Serializable_JSON $data )
	{
		$this->_response( $data->toJSON() );
	}

	/**
	 * @param DataModel_Fetch $data
	 */
	public function responseDataModelsList( DataModel_Fetch $data )
	{

		$response_headers = $this->handleDataPagination( $data );
		$this->handleOrderBy( $data );
		$this->_response( $data->toJSON(), $response_headers );
	}

	/**
	 * @param string $module_action
	 * @param string $controller_action
	 * @param array  $action_parameters
	 *
	 */
	public function responseAccessDenied( $module_action, $controller_action, $action_parameters )
	{
		if( !Auth::getCurrentUser() ) {
			$this->responseError( self::ERR_CODE_AUTHORIZATION_REQUIRED );
		} else {
			$this->responseError(
				self::ERR_CODE_ACCESS_DENIED, [
					                            'module_action'     => $module_action,
					                            'controller_action' => $controller_action,
				                            ]
			);
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
	 * @return array
	 */
	protected function handleDataPagination( DataModel_Fetch $data )
	{
		$response_headers = [];

		$header = $this->getHttpRequestHeader( static::$pagination_request_http_header_name );

		if( preg_match( static::$pagination_request_http_header_regexp, $header, $matches ) ) {
			list( , $range_from, $range_to ) = $matches;


			$offset = $range_from;
			$limit = ( $range_to+1 )-$range_from;

			$data->setPagination( $limit, $offset );

			$count = $data->getCount();

			if( $range_to>$count ) {
				$range_to = $count;
			}

			if( $range_from>$count ) {
				$range_from = $count;
			}

			$response_headers[static::$pagination_response_http_header_name] = Data_Text::replaceData(
				static::$pagination_response_http_header_value_template, [
					                                                       'RANGE_FROM' => $range_from,
					                                                       'RANGE_TO'   => $range_to, 'COUNT' => $count,
				                                                       ]
			);
		}

		return $response_headers;
	}

	/**
	 * Handles data order by
	 *
	 * Order by is determined by GET parameter
	 *
	 * Examples:
	 *
	 * ?sort(+name,+age)
	 *
	 * ?sort(-price)
	 *
	 *
	 * @param DataModel_Fetch $data
	 */
	protected function handleOrderBy( DataModel_Fetch $data )
	{

		foreach( Http_Request::GET()->getRawData() as $key => $value ) {
			if( substr( $key, 0, 5 )=='sort('&&substr( $key, -1 )==')' ) {

				$order_by = explode( ',', substr( $key, 5, -1 ) );

				foreach( $order_by as $i => $v ) {
					if( $v[0]=='_' ) {
						$order_by[$i][0] = '+';
					}
				}

				$data->getQuery()->setOrderBy( $order_by );

			}
		}
	}

}