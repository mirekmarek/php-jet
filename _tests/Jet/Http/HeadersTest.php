<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package Http
 */
namespace Jet;

class Http_HeadersTest extends \PHPUnit_Framework_TestCase {

	protected $http_codes = array(
			200 => "OK",
			201 => "Created",
			202 => "Accepted",
			204 => "No Content",
			205 => "Reset Content",
			206 => "Partial Content",

			301 => "Moved Permanently",
			302 => "Found",
			303 => "See Other",
			304 => "Not Modified",
			307 => "Temporary Redirect",
			308 => "Permanent Redirect",

			400 => "Bad Request",
			401 => "Unauthorized",
			402 => "Payment Required",
			403 => "Forbidden",
			404 => "Not Found",
			405 => "Method Not Allowed",
			406 => "Not Acceptable",
			407 => "Proxy Authentication Required",
			408 => "Request Timeout",
			409 => "Conflict",
			410 => "Gone",
			411 => "Length Required",
			412 => "Precondition Failed",
			413 => "Request Entity Too Large",
			414 => "Request-URI Too Long",
			415 => "Unsupported Media Type",
			416 => "Requested Range Not Satisfiable",
			417 => "Expectation Failed",
			425 => "Unordered Collection",
			426 => "Upgrade Required",
			428 => "Precondition Required",
			429 => "Too Many Requests",
			431 => "Request Header Fields Too Large",
			444 => "No Response",
			451 => "Unavailable For Legal Reasons",

			500 => "Internal Server Error",
			501 => "Not Implemented",
			502 => "Bad Gateway",
			503 => "Service Unavailable",
			504 => "Gateway Timeout",
			505 => "HTTP Version Not Supported",
			506 => "Variant Also Negotiates",
			509 => "Bandwidth Limit Exceeded",
			510 => "Not Extended",
			511 => "Network Authentication Required",
			598 => "Network read timeout error",
			599 => "Network connect timeout error",
			);

	protected $test_headers = array(
			"Test-header"=>"test_value",
			"Test-header-wo-value",
			"Test-header-array" => array("value 1", "value 2", "value 3")
		);

	protected $redirect_target = "http://somewhere/over/the/rainbow/";

	protected function setUp() {
		$GLOBALS["_test_Http_Headers_sent_headers"] = array();
	}

	protected function tearDown() {
	}


	/**
	 * @covers Jet\Http_Headers::getHttpVersion
	 * @covers Jet\Http_Headers::setHttpVersion
	 */
	public function testSetGetHttpVersion() {
		$this->assertEquals( "1.1", Http_Headers::getHttpVersion() );
		Http_Headers::setHttpVersion("1.0");
		$this->assertEquals( "1.0", Http_Headers::getHttpVersion() );
		Http_Headers::setHttpVersion("1.1");
		$this->assertEquals( "1.1", Http_Headers::getHttpVersion() );
	}


	/**
	 * @covers Jet\Http_Headers::getResponseCodes
	 */
	public function testGetResponseCodes() {
		$this->assertEquals( $this->http_codes, Http_Headers::getResponseCodes() );
	}

	/**
	 * @covers Jet\Http_Headers::getResponseMessage
	 */
	public function testGetResponseMessage() {
		foreach($this->http_codes as $code=>$message) {
			$this->assertEquals( $message, Http_Headers::getResponseMessage( $code ) );
		}
	}

	/**
	 * @covers Jet\Http_Headers::getResponseHeader
	 */
	public function testGetResponseHeader() {
		$this->assertEquals( false, Http_Headers::getResponseHeader( 99999 ) );

		foreach($this->http_codes as $code=>$message) {
			$this->assertEquals( "HTTP/1.1 {$code} {$message}", Http_Headers::getResponseHeader( $code ) );
		}
	}

	protected  function getValidDataTestResponse( $code, $add_redirect_target=false ) {

		$headers = array();

		foreach($this->test_headers as $header=>$value) {
			if(is_int($header)) {
				$headers[] = $value;
			} else {
				if(is_array($value)){
					$value = implode("; ", $value);
				}

				$headers[] = "{$header}: {$value}";
			}
		}

		$main = array( "HTTP/1.1 {$code} {$this->http_codes[$code]}" );

		if($add_redirect_target) {
			$main[] = "Location: {$this->redirect_target}";
		}

		return array_merge(
			$main,
			$headers
		);

	}

	/**
	 * @covers Jet\Http_Headers::response
	 */
	public function testResponse() {

		Http_Headers::response(200,  $this->test_headers);

		$this->assertEquals( $this->getValidDataTestResponse(200), $GLOBALS["_test_Http_Headers_sent_headers"] );
	}

	/**
	 * @covers Jet\Http_Headers::responseOK
	 */
	public function testResponseOK() {
		Http_Headers::responseOK( $this->test_headers);

		$this->assertEquals( $this->getValidDataTestResponse(200), $GLOBALS["_test_Http_Headers_sent_headers"] );
	}

	/**
	 * @covers Jet\Http_Headers::movedPermanently
	 */
	public function testMovedPermanently() {
		Http_Headers::movedPermanently( $this->redirect_target, false, $this->test_headers );

		$this->assertEquals( $this->getValidDataTestResponse(301, true), $GLOBALS["_test_Http_Headers_sent_headers"] );
	}

	/**
	 * @covers Jet\Http_Headers::movedTemporary
	 */
	public function testMovedTemporary() {
		Http_Headers::movedTemporary( $this->redirect_target, false, $this->test_headers );

		$this->assertEquals( $this->getValidDataTestResponse(302, true), $GLOBALS["_test_Http_Headers_sent_headers"] );
	}

	/**
	 * @covers Jet\Http_Headers::seeOther
	 */
	public function testSeeOther() {
		Http_Headers::seeOther( $this->redirect_target, false, $this->test_headers );

		$this->assertEquals( $this->getValidDataTestResponse(303, true), $GLOBALS["_test_Http_Headers_sent_headers"] );
	}

	/**
	 * @covers Jet\Http_Headers::notFound
	 */
	public function testNotFound() {
		Http_Headers::notFound( $this->test_headers );

		$this->assertEquals( $this->getValidDataTestResponse(404), $GLOBALS["_test_Http_Headers_sent_headers"] );
	}

	/**
	 * @covers Jet\Http_Headers::notModified
	 */
	public function testNotModified() {
		Http_Headers::notModified( $this->test_headers );

		$this->assertEquals( $this->getValidDataTestResponse(304), $GLOBALS["_test_Http_Headers_sent_headers"] );
	}

	/**
	 * @covers Jet\Http_Headers::authorizationRequired
	 */
	public function testAuthorizationRequired() {
		Http_Headers::authorizationRequired( $this->test_headers );

		$this->assertEquals( $this->getValidDataTestResponse(401), $GLOBALS["_test_Http_Headers_sent_headers"] );
	}

	/**
	 * @covers Jet\Http_Headers::badRequest
	 */
	public function testBadRequest() {
		Http_Headers::badRequest( $this->test_headers );

		$this->assertEquals( $this->getValidDataTestResponse(400), $GLOBALS["_test_Http_Headers_sent_headers"] );
	}

	/**
	 * @covers Jet\Http_Headers::forbidden
	 */
	public function testForbidden() {
		Http_Headers::forbidden( $this->test_headers );

		$this->assertEquals( $this->getValidDataTestResponse(403), $GLOBALS["_test_Http_Headers_sent_headers"] );
	}

	/**
	 * @covers Jet\Http_Headers::internalServerError
	 */
	public function testInternalServerError() {
		Http_Headers::internalServerError( $this->test_headers );

		$this->assertEquals( $this->getValidDataTestResponse(500), $GLOBALS["_test_Http_Headers_sent_headers"] );
	}

	/**
	 * @covers Jet\Http_Headers::reload
	 */
	public function testReload() {
		Http_Headers::reload( false );

		$this->assertEquals( array("Location: ?#"), $GLOBALS["_test_Http_Headers_sent_headers"] );
	}

	/**
	 * @covers Jet\Http_Headers::formSent
	 */
	public function testFormSent() {
		$test_form = new Form("test_form", array());

		Http_Headers::formSent($test_form, false);

		$this->assertEquals( array("Location: #test_form"), $GLOBALS["_test_Http_Headers_sent_headers"] );
	}
}
