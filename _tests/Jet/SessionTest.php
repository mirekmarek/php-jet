<?php
/**
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package Session
 */
namespace Jet;

class SessionTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var Session
	 */
	protected $object;

	/**
	 *
	 */
	protected function setUp() {
		$this->object = new Session('test-namespace');
	}

	/**
	 *
	 */
	protected function tearDown() {
	}


	/**
	 * @covers Jet\Session::__construct
	 *
	 * @expectedException \Jet\Session_Exception
	 * @expectedExceptionCode \Jet\Session_Exception::CODE_INVALID_KEY
	 */
	public function testInvalidKey() {
		$this->object->setValue('', 'value');
	}

	/**
	 * @covers Jet\Session::__construct
	 * @covers Jet\Session::getNamespace
	 */
	public function testGetNamespace() {
		$this->assertEquals('test-namespace', $this->object->getNamespace() );
	}

	/**
	 * @covers Jet\Session::setValue
	 * @covers Jet\Session::unsetValue
	 * @covers Jet\Session::getValueExists
	 * @covers Jet\Session::getValue
	 */
	public function testGeneral() {
		$this->assertFalse( $this->object->getValueExists('imaginary') );
		$this->assertEquals('default value', $this->object->getValue('imaginary', 'default value'));

		$this->object->setValue( 'key', 'value' );
		$this->assertTrue( $this->object->getValueExists('key') );
		$this->assertEquals('value', $this->object->getValue('key'));

		$this->assertEquals([
			'test-namespace' => [
				'key' => 'value',
			],
		], $_SESSION);

		$this->object->unsetValue('key');
		$this->assertFalse( $this->object->getValueExists('key') );

		$this->assertEquals([
			'test-namespace' => [
			],
		], $_SESSION);
	}

	/**
	 * @covers Jet\Session::getSessionID
	 */
	public function testGetSessionID() {
		$this->assertEquals( session_id(), $this->object->getSessionID() );
	}

	/**
	 * @covers Jet\Session::destroy
	 */
	public function testDestroy() {
		$this->object->destroy();
		$this->assertEquals([], $_SESSION);
	}
}
