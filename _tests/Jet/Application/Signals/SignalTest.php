<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package Application
 * @subpackage Application_Signals
 */
namespace Jet;

require_once "_mock/Jet/Application/Signals/TestSender.php";

class Application_Signals_SignalTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var Application_Signals_Signal
	 */
	protected $object;

	/**
	 * @var Application_Signals_SignalTest_Sender
	 */
	protected $sender;
	protected $signal = "/test/signal1";
	protected $data = array( "val1", "a" => "valb" );

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->sender = new Application_Signals_SignalTest_Sender();

		$this->object = new Application_Signals_Signal( $this->sender, $this->signal , $this->data  );
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers Jet\Application_Signals_Signal::getSender
	 */
	public function testGetSender() {
		$this->assertEquals( $this->sender, $this->object->getSender() );
	}

	/**
	 * @covers Jet\Application_Signals_Signal::getName
	 */
	public function testGetName() {
		$this->assertEquals( $this->signal, $this->object->getName() );
	}

	/**
	 * @covers Jet\Application_Signals_Signal::getData
	 */
	public function testGetData() {
		$this->assertEquals( $this->data, $this->object->getData() );
	}
}
