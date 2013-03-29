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

$_GET["string"] = 'Test <script type="text/javascript">alert("I am a bad boy");</script>';
$_GET["int"] = 123;
$_GET["float"] = pi();
$_GET["bool_true"] = 1;
$_GET["bool_false"] = 0;

$_POST["string"] = 'Test <script type="text/javascript">alert("I am a bad boy");</script>';
$_POST["int"] = 123;
$_POST["float"] = pi();
$_POST["bool_true"] = 1;
$_POST["bool_false"] = 0;

$_SERVER["string"] = 'Test <script type="text/javascript">alert("I am a bad boy");</script>';
$_SERVER["int"] = 123;
$_SERVER["float"] = pi();
$_SERVER["bool_true"] = 1;
$_SERVER["bool_false"] = 0;



class Http_RequestTest extends \PHPUnit_Framework_TestCase {

	protected function setUp() {
	}

	protected function tearDown() {
	}

	/**
	 * @covers Jet\Http_Request::initialize
	 * @covers Jet\Http_Request::getIsInitialized
	 */
	public function testInitialize() {
		$this->assertFalse( Http_Request::getIsInitialized() );
		Http_Request::initialize();
		$this->assertTrue( Http_Request::getIsInitialized() );
	}

	/**
	 * @covers Jet\Http_Request::initialize
	 * @covers Jet\Http_Request::hidePHPRequestData
	 * @covers Jet\Http_Request_Data_Hoax
	 *
	 * @expectedException \Jet\Http_Request_Exception
	 * @expectedExceptionCode \Jet\Http_Request_Exception::CODE_REQUEST_DATA_HOAX
	 */
	public function testHidePHPRequestDataPost() {
		echo $_POST["test"];
	}

	/**
	 * @covers Jet\Http_Request::initialize
	 * @covers Jet\Http_Request::hidePHPRequestData
	 * @covers Jet\Http_Request_Data_Hoax
	 *
	 * @expectedException \Jet\Http_Request_Exception
	 * @expectedExceptionCode \Jet\Http_Request_Exception::CODE_REQUEST_DATA_HOAX
	 */
	public function testHidePHPRequestDataGet() {
		echo $_GET["test"];
	}

	/**
	 * @covers Jet\Http_Request::initialize
	 * @covers Jet\Http_Request::hidePHPRequestData
	 * @covers Jet\Http_Request_Data_Hoax
	 *
	 * @expectedException \Jet\Http_Request_Exception
	 * @expectedExceptionCode \Jet\Http_Request_Exception::CODE_REQUEST_DATA_HOAX
	 */
	public function testHidePHPRequestDataRequest() {
		echo $_REQUEST["test"];
	}

	/**
	 * @covers Jet\Http_Request::POST
	 */
	public function testPOST() {
		$this->assertTrue(Http_Request::POST()->getBool("bool_true"));
		$this->assertFalse(Http_Request::POST()->getBool("bool_false"));
		$this->assertEquals(
			htmlspecialchars('Test <script type="text/javascript">alert("I am a bad boy");</script>'),
			Http_Request::POST()->getString("string")
		);
		$this->assertEquals(
			pi(),
			Http_Request::POST()->getFloat("float")
		);
		$this->assertEquals(
			123,
			Http_Request::POST()->getFloat("int")
		);

		$this->assertEquals(
			'Test <script type="text/javascript">alert("I am a bad boy");</script>',
			Http_Request::POST()->getRaw("string")
		);
		$this->assertEquals( 1, Http_Request::POST()->getRaw("bool_true") );
		$this->assertEquals( 0, Http_Request::POST()->getRaw("bool_false") );
		$this->assertEquals( pi(), Http_Request::POST()->getRaw("float") );
		$this->assertEquals( 123, Http_Request::POST()->getRaw("int") );

	}

	/**
	 * @covers Jet\Http_Request::GET
	 */
	public function testGET() {
		$this->assertTrue(Http_Request::GET()->getBool("bool_true"));
		$this->assertFalse(Http_Request::GET()->getBool("bool_false"));
		$this->assertEquals(
			htmlspecialchars('Test <script type="text/javascript">alert("I am a bad boy");</script>'),
			Http_Request::GET()->getString("string")
		);
		$this->assertEquals(
			pi(),
			Http_Request::GET()->getFloat("float")
		);
		$this->assertEquals(
			123,
			Http_Request::GET()->getFloat("int")
		);

		$this->assertEquals(
			'Test <script type="text/javascript">alert("I am a bad boy");</script>',
			Http_Request::GET()->getRaw("string")
		);
		$this->assertEquals( 1, Http_Request::GET()->getRaw("bool_true") );
		$this->assertEquals( 0, Http_Request::GET()->getRaw("bool_false") );
		$this->assertEquals( pi(), Http_Request::GET()->getRaw("float") );
		$this->assertEquals( 123, Http_Request::GET()->getRaw("int") );
	}

	/**
	 * @covers Jet\Http_Request::SERVER
	 */
	public function testSERVER() {

		$this->assertTrue(Http_Request::SERVER()->getBool("bool_true"));
		$this->assertFalse(Http_Request::SERVER()->getBool("bool_false"));
		$this->assertEquals(
			htmlspecialchars('Test <script type="text/javascript">alert("I am a bad boy");</script>'),
			Http_Request::SERVER()->getString("string")
		);
		$this->assertEquals(
			pi(),
			Http_Request::SERVER()->getFloat("float")
		);
		$this->assertEquals(
			123,
			Http_Request::SERVER()->getFloat("int")
		);

		$this->assertEquals(
			'Test <script type="text/javascript">alert("I am a bad boy");</script>',
			Http_Request::SERVER()->getRaw("string")
		);
		$this->assertEquals( 1, Http_Request::SERVER()->getRaw("bool_true") );
		$this->assertEquals( 0, Http_Request::SERVER()->getRaw("bool_false") );
		$this->assertEquals( pi(), Http_Request::SERVER()->getRaw("float") );
		$this->assertEquals( 123, Http_Request::SERVER()->getRaw("int") );
	}

	/**
	 * @covers Jet\Http_Request::getRawPostData
	 */
	public function testGetRawPostData() {
		//technically impossible to properly test
	}

	/**
	 * @covers Jet\Http_Request::getRequestMethod
	 */
	public function testGetRequestMethod() {
		$_SERVER["REQUEST_METHOD"] = null;
		$this->assertEquals("GET", Http_Request::getRequestMethod());
		$_SERVER["REQUEST_METHOD"] = "post";
		$this->assertEquals("POST", Http_Request::getRequestMethod());
		$_SERVER["REQUEST_METHOD"] = "get";
		$this->assertEquals("GET", Http_Request::getRequestMethod());
	}

	/**
	 * @covers Jet\Http_Request::getRequestIsHttp
	 */
	public function testGetRequestIsHTTP() {
		$_SERVER["HTTPS"] = null;
		$this->assertTrue(Http_Request::getRequestIsHttp());
		$_SERVER["HTTPS"] = "off";
		$this->assertTrue(Http_Request::getRequestIsHttp());
		$_SERVER["HTTPS"] = "on";
		$this->assertFalse(Http_Request::getRequestIsHttp());
	}

	/**
	 * @covers Jet\Http_Request::getRequestIsHttps
	 */
	public function testGetRequestIsHTTPS() {
		$_SERVER["HTTPS"] = null;
		$this->assertFalse(Http_Request::getRequestIsHttps());
		$_SERVER["HTTPS"] = "off";
		$this->assertFalse(Http_Request::getRequestIsHttps());
		$_SERVER["HTTPS"] = "on";
		$this->assertTrue(Http_Request::getRequestIsHttps());
	}

	/**
	 * @covers Jet\Http_Request::getClientIP
	 */
	public function testGetClientIP() {
		$_SERVER["REMOTE_ADDR"] = null;
		$this->assertEquals("unknown", Http_Request::getClientIP());
		$_SERVER["REMOTE_ADDR"] = "127.0.0.1";
		$this->assertEquals("127.0.0.1", Http_Request::getClientIP());
	}

	/**
	 * @covers Jet\Http_Request::getClientUserAgent
	 */
	public function testGetClientUserAgent() {
		$_SERVER["HTTP_USER_AGENT"] = null;
		$this->assertEquals("unknown", Http_Request::getClientUserAgent());
		$_SERVER["HTTP_USER_AGENT"] = "UltraBrowser";
		$this->assertEquals("UltraBrowser", Http_Request::getClientUserAgent());
	}

	/**
	 * @covers Jet\Http_Request::getURL
	 */
	public function testGetURL() {
		$_SERVER["HTTP_HOST"] = "www.domain.tld";
		$_SERVER["REQUEST_URI"] = "/path/?q=value";

		$_SERVER["HTTPS"] = null;
		$_SERVER["SERVER_PORT"] = "80";
		$this->assertEquals( "http://www.domain.tld/path/?q=value", Http_Request::getURL(true) );
		$this->assertEquals( "http://www.domain.tld/path/", Http_Request::getURL(false) );
		$_SERVER["SERVER_PORT"] = "81";
		$this->assertEquals( "http://www.domain.tld:81/path/?q=value", Http_Request::getURL(true) );
		$this->assertEquals( "http://www.domain.tld:81/path/", Http_Request::getURL(false) );

		$_SERVER["HTTPS"] = "on";
		$_SERVER["SERVER_PORT"] = "443";

		$this->assertEquals( "https://www.domain.tld/path/?q=value", Http_Request::getURL(true) );
		$this->assertEquals( "https://www.domain.tld/path/", Http_Request::getURL(false) );
		$_SERVER["SERVER_PORT"] = "8443";
		$this->assertEquals( "https://www.domain.tld:8443/path/?q=value", Http_Request::getURL(true) );
		$this->assertEquals( "https://www.domain.tld:8443/path/", Http_Request::getURL(false) );
	}

	/**
	 * @covers Jet\Http_Request::getHeaders
	 */
	public function testGetHeaders() {
		$headers = array(
			"Header-1" => "Hrader 1 value",
			"Header-2" => "Hrader 2 value",
			"Header-3" => "Hrader 3 value",
		);

		foreach( $headers as $key=>$val ) {
			$_SERVER["HTTP_".strtoupper(str_replace("-", "_",strtoupper($key)))] = $val;
		}

		$this->assertEquals(  $headers, Http_Request::getHeaders() );
	}
}
