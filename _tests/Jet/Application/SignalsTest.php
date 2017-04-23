<?php
/**
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package Application
 * @subpackage Application_Signals
 */
namespace Jet;

/** @noinspection PhpIncludeInspection */
require_once '_mock/Jet/Application/Signals/TestSender.php';
/** @noinspection PhpIncludeInspection */
require_once '_mock/Jet/Application/Signals/FakeSignalClass.php';

class Application_SignalsTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers Application_Signals::createSignal
	 *
	 * @expectedException \Jet\Application_Signals_Exception
	 * @expectedExceptionCode \Jet\Application_Signals_Exception::CODE_UNKNOWN_SIGNAL
	 */
	public function testCreateSignalFailedImaginarySignal() {
		$sender_mock = new Application_Signals_SignalTest_Sender();

		Application_Signals::createSignal( $sender_mock, '/test/imaginary/signal' );
	}

	/**
	 * @covers Application_Signals::createSignal
	 *
	 * @expectedException \Jet\Application_Signals_Exception
	 * @expectedExceptionCode \Jet\Application_Signals_Exception::INVALID_SIGNAL_OBJECT_CLASS
	 */
	public function testCreateSignalFailedInvalidClass() {
		$sender_mock = new Application_Signals_SignalTest_Sender();
		$sender_mock->addSignal('/test/signal');
		$sender_mock->setSignalsSignalObjectClassName('Jet\\FakeSignalClass');

		Application_Signals::createSignal( $sender_mock, '/test/signal', []);
	}

	/**
	 * @covers Application_Signals::createSignal
	 *
	 */
	public function testCreateSignal() {
		$sender_mock = new Application_Signals_SignalTest_Sender();
		$sender_mock->addSignal('/test/signal');

		Application_Signals::createSignal( $sender_mock, '/test/signal' );
	}
}
