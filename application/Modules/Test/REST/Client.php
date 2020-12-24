<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\Test\REST;

use Jet\Mvc;

use Jet\Mvc_Page_Interface;
use JetApplication\Application_REST;

use \CURLFile;

/**
 *
 */
class Client
{
	const METHOD_POST = 'POST';
	const METHOD_GET = 'GET';
	const METHOD_DELETE = 'DELETE';
	const METHOD_PUT = 'PUT';

	const HTTP_STATUS_OK = 200;

	/**
	 * @var string
	 */
	protected string $username = '';

	/**
	 * @var string
	 */
	protected string $password = '';

	/**
	 * @var ?Mvc_Page_Interface
	 */
	protected ?Mvc_Page_Interface $root_page = null;

	/**
	 * @var string
	 */
	protected string $error_message = '';

	/**
	 * @var string
	 */
	protected string $request = '';

	/**
	 * @var ?array
	 */
	protected ?array $request_data = null;

	/**
	 * @var string|array
	 */
	protected string|array $request_body = '';

	/**
	 * @var int
	 */
	protected int $response_status = 0;

	/**
	 * @var string
	 */
	protected string $response_header = '';

	/**
	 * @var string
	 */
	protected string $response_body = '';

	/**
	 * @var array|null
	 */
	protected array|null$response_data = null;


	/**
	 * Client constructor.
	 *
	 * @param string $username
	 * @param string $password
	 */
	public function __construct( string $username='', string $password='' )
	{
		if(!$username) {
			$session = Main::getSession();

			$username = $session->getValue('username');
			$password = $session->getValue('password');
		}


		$this->username = $username;
		$this->password = $password;

		$this->root_page = Application_REST::getSite()->getHomepage( Mvc::getCurrentLocale() );
	}


	/**
	 *
	 * @param string $method
	 * @param string $object
	 * @param array  $data
	 * @param array  $get_params
	 * @param string $upload_file_path
	 *
	 * @return bool
	 */
	public function exec( string $method,
	                      string $object,
	                      array $data = [],
	                      array $get_params=[],
	                      string $upload_file_path='' ) : bool
	{
		$headers = [];

		if( substr( $object, -1 ) == '/' ) {
			$object = substr( $object, 0, -1 );
		}


		$URL = $this->root_page->getURL( explode( '/', $object ), $get_params );

		$curl_handle = curl_init();


		if( $this->username ) {
			curl_setopt( $curl_handle, CURLOPT_USERPWD, $this->username.':'.$this->password );
		}

		curl_setopt( $curl_handle, CURLOPT_URL, $URL );



		switch( $method ) {
			case self::METHOD_GET:
				curl_setopt( $curl_handle, CURLOPT_HTTPGET, true );
				break;
			case self::METHOD_DELETE:
				curl_setopt( $curl_handle, CURLOPT_CUSTOMREQUEST, self::METHOD_DELETE );
				break;
			case self::METHOD_POST:
				curl_setopt( $curl_handle, CURLOPT_POST, true );

				if($upload_file_path) {
					$this->request_body = [
						'file' => new CURLFile($upload_file_path)
					];

				} else {
					$headers[] = 'Content-Type: application/json';

					$this->request_data = $data;
					$this->request_body = json_encode( $data );

				}


				curl_setopt( $curl_handle, CURLOPT_POSTFIELDS, $this->request_body );
				$headers[] = 'Expect:';
				break;
			case self::METHOD_PUT:
				$this->request_data = $data;
				$this->request_body = json_encode( $data );

				$handle = fopen( 'php://temp', 'w+' );
				fwrite( $handle, $this->request_body );
				rewind( $handle );
				$f_stat = fstat( $handle );
				curl_setopt( $curl_handle, CURLOPT_PUT, true );
				curl_setopt( $curl_handle, CURLOPT_INFILE, $handle );
				curl_setopt( $curl_handle, CURLOPT_INFILESIZE, $f_stat['size'] );
				$headers[] = 'Content-Type: application/json';
				$headers[] = 'Expect:';
				break;
		}

		curl_setopt( $curl_handle, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl_handle, CURLOPT_VERBOSE, true );
		curl_setopt( $curl_handle, CURLOPT_HEADER, true );

		curl_setopt( $curl_handle, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl_handle, CURLINFO_HEADER_OUT, true );
		curl_setopt( $curl_handle, CURLOPT_HTTPHEADER, $headers );


		$this->response_body = curl_exec( $curl_handle );

		$this->request = curl_getinfo( $curl_handle, CURLINFO_HEADER_OUT );
		$this->response_status = curl_getinfo( $curl_handle, CURLINFO_HTTP_CODE );


		$header_size = curl_getinfo( $curl_handle, CURLINFO_HEADER_SIZE );
		$this->response_header = substr( $this->response_body, 0, $header_size );
		$this->response_body = substr( $this->response_body, $header_size );



		$result = false;

		if( $this->response_data === false ) {
			$this->error_message = 'CURL_ERR:'.curl_errno( $curl_handle ).' - '.curl_error( $curl_handle );

		} else {

			$this->response_data = json_decode( $this->response_body, true );

			if( !is_array( $this->response_data ) ) {
				$this->error_message = "JSON parse error";
			} else {

				switch( $this->response_status ) {
					case self::HTTP_STATUS_OK:
						$result = true;
						break;
					default:
						break;
				}

			}

		}

		curl_close( $curl_handle );

		return $result;

	}

	/**
	 * @param string $object
	 * @param array  $get_params
	 *
	 * @return bool
	 */
	public function get( string $object, array $get_params=[] ) : bool
	{
		return $this->exec( static::METHOD_GET, $object, [], $get_params );
	}

	/**
	 * @param string $object
	 *
	 * @return bool
	 */
	public function delete( string $object ) : bool
	{
		return $this->exec( static::METHOD_DELETE, $object, [], [] );
	}


	/**
	 * @param string $object
	 * @param array  $data
	 * @param string $upload_file_path
	 *
	 * @return bool
	 */
	public function post( string $object, array $data, string $upload_file_path=''  ) : bool
	{
		return $this->exec( static::METHOD_POST, $object, $data, [], $upload_file_path );
	}

	/**
	 * @param string $object
	 * @param array  $data
	 *
	 * @return bool
	 */
	public function put( string $object, array $data  ) : bool
	{
		return $this->exec( static::METHOD_PUT, $object, $data );
	}


	/**
	 * @return string
	 */
	public function request() : string
	{
		return $this->request;
	}

	/**
	 * @return string
	 */
	public function requestBody() : string
	{
		return $this->request_body;
	}

	/**
	 * @return array
	 */
	public function requestData() : array
	{
		return $this->request_data;
	}

	/**
	 * @return string
	 */
	public function errorMessage() : string
	{
		return $this->error_message;
	}

	/**
	 * @return int
	 */
	public function responseStatus() : int
	{
		return $this->response_status;
	}

	/**
	 * @return string
	 */
	public function responseHeader() : string
	{
		return $this->response_header;
	}

	/**
	 * @return string
	 */
	public function responseBody() : string
	{
		return $this->response_body;
	}

	/**
	 * @return array|null
	 */
	public function responseData() : array|null
	{
		return $this->response_data;
	}


}