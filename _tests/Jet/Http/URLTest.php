<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Http_URLTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var Http_URL
	 */
	protected $object;

	/**
	 * @covers \Jet\Http_URL::getIsValid
	 * @covers \Jet\Http_URL::parse
	 */
	public function testParseURL()
	{
		$URL = new Http_URL( 'invalid~url' );
		$this->assertFalse( $URL->getIsValid() );

		$this->assertTrue( $this->object->getIsValid() );
	}

	/**
	 * @covers \Jet\Http_URL::parse
	 * @covers \Jet\Http_URL::getFragment
	 */
	public function testGetFragment()
	{
		$this->assertEquals( 'fragment', $this->object->getFragment() );
	}

	/**
	 * @covers \Jet\Http_URL::parse
	 * @covers \Jet\Http_URL::getHost
	 */
	public function testGetHost()
	{
		$this->assertEquals( 'www.domain.tld', $this->object->getHost() );
	}

	/**
	 * @covers \Jet\Http_URL::parse
	 * @covers \Jet\Http_URL::getUser
	 */
	public function testGetUser()
	{
		$this->assertEquals( 'user', $this->object->getUser() );
	}

	/**
	 * @covers \Jet\Http_URL::parse
	 * @covers \Jet\Http_URL::getPassword
	 */
	public function testGetPassword()
	{
		$this->assertEquals( 'pass', $this->object->getPassword() );
	}

	/**
	 * @covers \Jet\Http_URL::parse
	 * @covers \Jet\Http_URL::getPath
	 */
	public function testGetPath()
	{
		$this->assertEquals( '/path/', $this->object->getPath() );
	}

	/**
	 * @covers \Jet\Http_URL::parse
	 * @covers \Jet\Http_URL::getPort
	 */
	public function testGetPort()
	{
		$this->assertEquals( 8443, $this->object->getPort() );
	}

	/**
	 * @covers \Jet\Http_URL::parse
	 * @covers \Jet\Http_URL::getQuery
	 */
	public function testGetQuery()
	{
		$this->assertEquals( 'query=value', $this->object->getQuery() );
	}

	/**
	 * @covers \Jet\Http_URL::parse
	 * @covers \Jet\Http_URL::getQueryData
	 */
	public function testGetQueryData()
	{
		$this->assertEquals( [ 'query' => 'value' ], $this->object->getQueryData() );
	}

	/**
	 * @covers \Jet\Http_URL::parse
	 * @covers \Jet\Http_URL::getScheme
	 */
	public function testGetScheme()
	{
		$this->assertEquals( 'https', $this->object->getScheme() );
	}

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new Http_URL( 'https://user:pass@www.domain.tld:8443/path/?query=value#fragment' );
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
	}

}
