<?php
/**
 *
 *
 *
 * REST controller class
 * @see Mvc/readme.txt
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Controller
 */
namespace Jet;

abstract class Mvc_Controller_REST extends Mvc_Controller_Abstract {
	const ERR_CODE_AUTHORIZATION_REQUIRED = "AuthorizationRequired";
	const ERR_CODE_ACCESS_DENIED = "AccessDenied";
	const ERR_CODE_UNSUPPORTED_DATA_CONTENT_TYPE = "UnsupportedDataContentType";
	const ERR_CODE_FORM_ERRORS = "FormErrors";
	const ERR_CODE_UNKNOWN_ITEM = "UnknownItem";

	const RESPONSE_FORMAT_XML = "XML";
	const RESPONSE_FORMAT_JSON = "JSON";

	const CONTENT_TYPE_XML = "text/xml";
	const CONTENT_TYPE_JSON = "application/json";

	const INTERNAL_CHARSET = "UTF-8";

	/**
	 * @var string
	 */
	protected static $pagination_request_http_header_name = "Range";
	/**
	 * @var string
	 */
	protected static $pagination_request_http_header_regexp = "/^items=([0-9]{1,})-([0-9]{1,})$/";
	/**
	 * @var string
	 */
	protected static $pagination_response_http_header_name = "Content-Range";
	/**
	 * @var string
	 */
	protected static $pagination_response_http_header_value_template = "items %RANGE_FROM%-%RANGE_TO%/%COUNT%";

	/**
	 * @var string
	 */
	protected static $response_format_xml_http_get_parameter = "xml";
	/**
	 * @var string
	 */
	protected static $response_format_json_http_get_parameter = "json";
	/**
	 * @var string
	 */
	protected static $response_charset_http_get_parameter = "charset";

	/**
	 * @var string
	 */
	protected static $default_response_format = self::RESPONSE_FORMAT_JSON;
	/**
	 * @var string
	 */
	protected static $default_response_charset = "UTF-8";

	/**
	 * @var array
	 */
	protected $http_headers = null;

	/**
	 *
	 * Format:
	 *
	 * error_code => array(http_error_code, error_message)
	 *
	 * Example:
	 *
	 * <code>
	 * const ERROR_CODE_OBJECT_NOT_FOUND = "ObjectNotFound";
	 *
	 * protected static $errors = array(
	 *      self::ERROR_CODE_OBJECT_NOT_FOUND => array(self::HTTP_NOT_FOUND, "Object not found")
	 * );
	 * </code>
	 *
	 * @var array
	 */
	protected static $errors = array(
		self::ERR_CODE_AUTHORIZATION_REQUIRED => array(Http_Headers::CODE_401_UNAUTHORIZED, "Access denied! Authorization required! "),
		self::ERR_CODE_ACCESS_DENIED => array(Http_Headers::CODE_401_UNAUTHORIZED, "Access denied! Insufficient permissions! "),
		self::ERR_CODE_UNSUPPORTED_DATA_CONTENT_TYPE => array(Http_Headers::CODE_400_BAD_REQUEST, "Unsupported data Content-Type"),
		self::ERR_CODE_FORM_ERRORS => array(Http_Headers::CODE_400_BAD_REQUEST, "There are errors in form"),
		self::ERR_CODE_UNKNOWN_ITEM => array(Http_Headers::CODE_404_NOT_FOUND, "Unknown item"),
	);



	/**
	 * @return bool|mixed
	 */
	public function getRequestData() {
		$data = Http_Request::getRawPostData();

		if(!$data) {
			return false;
		}

		$content_type = $this->getHttpRequestHeader("Content-Type");

		if(strpos($content_type, ";")!==false) {
			list($content_type, $charset) = explode(";", $content_type);
			$content_type = trim($content_type);
			$charset = trim($charset);

			list(,$charset) = explode("=", $charset);

			if($charset!=static::INTERNAL_CHARSET) {
				$data = iconv($charset, static::INTERNAL_CHARSET, $data);
			}
		}

		switch($content_type) {
			case static::CONTENT_TYPE_JSON:
				return json_decode($data, true);
			break;
			case static::CONTENT_TYPE_XML:
				//TODO:
			break;
			default:
				$this->responseError(self::ERR_CODE_UNSUPPORTED_DATA_CONTENT_TYPE, array("Content-Type"=>$content_type));
			break;

		}

		return false;
	}

	/**
	 *
	 */
	public function responseOK() {
		if($this->responseFormatDetection()==static::RESPONSE_FORMAT_XML) {
			$this->_response("<result>OK</result>");
		} else {
			$this->_response(json_encode("OK"));
		}

	}

	/**
	 * @param Object_Serializable_REST $data
	 */
	public function responseData( Object_Serializable_REST $data ) {
		if($this->responseFormatDetection()==static::RESPONSE_FORMAT_XML) {
			$this->_response( $data->toXML() );
		} else {
			$this->_response( $data->toJSON() );
		}
	}

	/**
	 * @param DataModel_Fetch_Data_Abstract $data
	 */
	public function responseDataModelsList( DataModel_Fetch_Data_Abstract $data ) {

		$response_headers = $this->handleDataPagination( $data );
		$this->handleOrderBy( $data );

		if($this->responseFormatDetection()==static::RESPONSE_FORMAT_XML) {
			$this->_response( $data->toXML(), $response_headers );
		} else {
			$this->_response( $data->toJSON(), $response_headers );
		}
	}

	/**
	 * @param string $module_action
	 * @param string $controller_action
	 * @param array $action_parameters
	 *
	 */
	public function responseAclAccessDenied( $module_action, $controller_action, $action_parameters ) {
		if(!Auth::getCurrentUser()) {
			$this->responseError( self::ERR_CODE_AUTHORIZATION_REQUIRED );
		} else {
			$this->responseError( self::ERR_CODE_ACCESS_DENIED , array(
				"module_action" => $module_action,
				"controller_action" => $controller_action
			));
		}
	}

	/**
	 * @param array $errors
	 */
	public function responseFormErrors( array $errors ) {
		$this->responseError(self::ERR_CODE_FORM_ERRORS, $errors);
	}

	/**
	 * @param string|array $ID
	 */
	public function responseUnknownItem( $ID ) {
		if(!is_array($ID)) {
			$ID = array("ID"=>$ID);
		}
		$this->responseError(self::ERR_CODE_UNKNOWN_ITEM, $ID);
	}

	/**
	 * @param string|int $code
	 * @param mixed  $data
	 * @throws Mvc_Controller_Exception
	 */
	public function responseError( $code, $data=null ) {
		if(!isset(static::$errors[$code])) {
			throw new Mvc_Controller_Exception(
				"REST Error (code:{$code}) is not specified! Please specify the error. Add ".get_class($this)."::\$errors[{$code}] entry.  ",
				Mvc_Controller_Exception::CODE_INVALID_RESPONSE_CODE
			);
		}

		list($http_code, $error_message) = static::$errors[$code];

		$error_code = get_class($this).":".$code;
		$error = array(
			"error_code" => $error_code,
			"error_msg" => $error_message
		);

		if($data) {
			$error["error_data"] = $data;
		}


		if($this->responseFormatDetection()==self::RESPONSE_FORMAT_XML) {
			$this->_response( $this->_XMLSerialize($error, "error"), array(), $http_code, $error_message );
		} else {
			$this->_response(json_encode( $error ), array(), $http_code, $error_message);
		}

	}

	/**
	 * Handles data pagination by request HTTP headers and returns response HTTP headers
	 *
	 * @param DataModel_Fetch_Data_Abstract $data
	 * @return array
	 */
	protected function handleDataPagination( DataModel_Fetch_Data_Abstract $data ) {
		$response_headers = array();

		$header = $this->getHttpRequestHeader( static::$pagination_request_http_header_name );

		if(preg_match(static::$pagination_request_http_header_regexp, $header, $matches)) {

			list(,$range_from, $range_to) = $matches;
			$count = $data->getCount();

			$offset = $range_from;
			$limit = ($range_to+1) - $range_from;

			$data->getQuery()->setLimit( $limit, $offset );

			if( $range_to > $count ) {
				$range_to = $count;
			}

			if( $range_from > $count ) {
				$range_from = $count;
			}

			$response_headers[static::$pagination_response_http_header_name] = Data_Text::replaceData(
				static::$pagination_response_http_header_value_template,
				array(
					"RANGE_FROM" => $range_from,
					"RANGE_TO" => $range_to,
					"COUNT" => $count
				)
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
	 * @param DataModel_Fetch_Data_Abstract $data
	 */
	protected function handleOrderBy( DataModel_Fetch_Data_Abstract $data ) {

		foreach(Http_Request::GET()->getRawData() as $k=>$v) {
			if(substr($k,0, 5)=="sort(" && substr($k, -1)==")") {

				$order_by = explode(",", substr($k, 5, -1));

				$data->getQuery()->setOrderBy( $order_by );

			}
		}
	}


	/**
	 * @param string $response_text
	 * @param array $http_headers
	 * @param int $http_code
	 * @param string $http_message
	 */
	protected function _response( $response_text, array $http_headers=array(), $http_code = 200, $http_message="OK" ) {
		$response_format = $this->responseFormatDetection();
		$response_charset = $this->responseCharsetDetection();

		$response_content_type = $response_format==static::RESPONSE_FORMAT_XML ?  static::CONTENT_TYPE_XML : self::CONTENT_TYPE_JSON;

		if( $response_charset!=static::INTERNAL_CHARSET ) {
			$response_text = iconv(
					static::INTERNAL_CHARSET,
					$response_charset,
					$response_text
				);
		}

		header( "HTTP/1.1 {$http_code} {$http_message}" );
		header("Content-type:{$response_content_type};charset={$response_charset}");

		foreach( $http_headers as $header=>$header_value ) {
			header("{$header}: $header_value");
		}

		if( $response_format==static::RESPONSE_FORMAT_XML ) {
			echo "<?xml version=\"1.0\" encoding=\"{$response_charset}\" ?>\n";
		}
		echo $response_text;
		Application::end();

	}

	/**
	 * Detects charset by GET vars
	 *
	 * @return string
	 */
	protected function responseCharsetDetection() {
		$r_charset = Http_Request::GET()->getString( static::$response_charset_http_get_parameter );

		return $r_charset ? $r_charset : static::$default_response_charset;
	}

	/**
	 * Detect response format by GET vars
	 *
	 * @return string
	 */
	protected function responseFormatDetection() {

		if( Http_Request::GET()->exists( static::$response_format_xml_http_get_parameter ) ) {
			return static::RESPONSE_FORMAT_XML;
		}

		if( Http_Request::GET()->exists( static::$response_format_json_http_get_parameter ) ) {
			return static::RESPONSE_FORMAT_JSON;
		}

		return static::$default_response_format;
	}

	/**
	 * @param array|object $data
	 * @param string $tag
	 * @param string $prefix
	 *
	 * @return string
	 */
	protected function _XMLSerialize( $data, $tag, $prefix="" ) {
		$result = "{$prefix}<{$tag}>\n";

		if(is_object($data)) {
			$data = get_class_vars($data);
		}

		foreach($data as $key=>$val) {
			if(is_array($val) || is_object($val)) {
				$result .= $this->_XMLSerialize($val, $key, $prefix . "\t");
			} else {
				$result .= "{$prefix}\t<{$key}>".htmlspecialchars($val)."</{$key}>\n";
			}
		}
		$result .= "{$prefix}</{$tag}>\n";
		return $result;
	}

	/**
	 * Get request HTTP header or default value if does not exist
	 *
	 * @param $header
	 * @param string $default_value
	 *
	 * @return array
	 */
	protected function getHttpRequestHeader( $header, $default_value="" ) {
		$headers = Http_Request::getHeaders();
		return isset($headers[$header]) ? $headers[$header] : $default_value;
	}
}