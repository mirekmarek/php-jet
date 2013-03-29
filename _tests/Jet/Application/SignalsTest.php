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
require_once "_mock/Jet/Application/Signals/FakeSignalClass.php";

class Application_SignalsTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers Jet\Application_Signals::createSignal
	 *
	 * @expectedException \Jet\Application_Signals_Exception
	 * @expectedExceptionCode \Jet\Application_Signals_Exception::CODE_UNKNOWN_SIGNAL
	 */
	public function testCreateSignalFailedImaginarySignal() {
		$sender_mock = new Application_Signals_SignalTest_Sender();

		Application_Signals::createSignal( $sender_mock, "/test/imaginary/signal" );
	}

	/**
	 * @covers Jet\Application_Signals::createSignal
	 *
	 * @expectedException \Jet\Application_Signals_Exception
	 * @expectedExceptionCode \Jet\Application_Signals_Exception::INVALID_SIGNAL_OBJECT_CLASS
	 */
	public function testCreateSignalFailedInvalidClass() {
		$sender_mock = new Application_Signals_SignalTest_Sender();
		$sender_mock->addSignal("/test/signal");
		$sender_mock->setSignalsSignalObjectClassName("Jet\\FakeSignalClass");

		Application_Signals::createSignal( $sender_mock, "/test/signal", array() );
	}

	/**
	 * @covers Jet\Application_Signals::createSignal
	 *
	 */
	public function testCreateSignal() {
		$sender_mock = new Application_Signals_SignalTest_Sender();
		$sender_mock->addSignal("/test/signal");

		Application_Signals::createSignal( $sender_mock, "/test/signal" );
	}
}
