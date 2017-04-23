<?php
/**
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package Http
 */
namespace Jet;

class Http_URLTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var Http_URL
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->object = new Http_URL('https://user:pass@www.domain.tld:8443/path/?query=value#fragment');
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers Http_URL::getIsValid
	 * @covers Http_URL::parseURL
	 */
	public function testParseURL() {
		$URL = new Http_URL('invalid~url');
		$this->assertFalse( $URL->getIsValid() );

		$this->assertTrue( $this->object->getIsValid() );
	}

	/**
	 * @covers Http_URL::parseURL
	 * @covers Http_URL::getFragment
	 */
	public function testGetFragment() {
		$this->assertEquals( 'fragment', $this->object->getFragment() );
	}

	/**
	 * @covers Http_URL::parseURL
	 * @covers Http_URL::getHost
	 */
	public function testGetHost() {
		$this->assertEquals( 'www.domain.tld', $this->object->getHost() );
	}


	/**
	 * @covers Http_URL::parseURL
	 * @covers Http_URL::getUser
	 */
	public function testGetUser() {
		$this->assertEquals( 'user', $this->object->getUser() );
	}

	/**
	 * @covers Http_URL::parseURL
	 * @covers Http_URL::getPassword
	 */
	public function testGetPassword() {
		$this->assertEquals( 'pass', $this->object->getPassword() );
	}

	/**
	 * @covers Http_URL::parseURL
	 * @covers Http_URL::getPath
	 */
	public function testGetPath() {
		$this->assertEquals( '/path/', $this->object->getPath() );
	}

	/**
	 * @covers Http_URL::parseURL
	 * @covers Http_URL::getPort
	 */
	public function testGetPort() {
		$this->assertEquals( 8443, $this->object->getPort() );
	}

	/**
	 * @covers Http_URL::parseURL
	 * @covers Http_URL::getQuery
	 */
	public function testGetQuery() {
		$this->assertEquals( 'query=value', $this->object->getQuery() );
	}

	/**
	 * @covers Http_URL::parseURL
	 * @covers Http_URL::getQueryData
	 */
	public function testGetQueryData() {
		$this->assertEquals( ['query' => 'value'], $this->object->getQueryData() );
	}

	/**
	 * @covers Http_URL::parseURL
	 * @covers Http_URL::getScheme
	 */
	public function testGetScheme() {
		$this->assertEquals( 'https', $this->object->getScheme() );
	}

}
