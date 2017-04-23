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

class Application_Signals_DispatcherTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@unlink(JET_TESTS_TMP.'modules_list_test.php');
	}

	/**
	 * @covers Application_Signals_Dispatcher::addCallback
	 * @covers Application_Signals_Dispatcher::removeCallback
	 */
	public function testAddRemoveCallback() {
		$callback_id_1 = Application_Signals_Dispatcher::addCallback('/test/signal', function( $signal ) {});
		$callback_id_2 = Application_Signals_Dispatcher::addCallback('/test/signal', function( $signal ) {});
		$callback_id_3 = Application_Signals_Dispatcher::addCallback('/test/signal', function( $signal ) {});

		Application_Signals_Dispatcher::removeCallback($callback_id_2);

		$callback_id_4 = Application_Signals_Dispatcher::addCallback('/test/signal', function( $signal ) {});

		$this->assertEquals('/test/signal~0', $callback_id_1);
		$this->assertEquals('/test/signal~1', $callback_id_2);
		$this->assertEquals('/test/signal~2', $callback_id_3);
		$this->assertEquals('/test/signal~3', $callback_id_4);

	}

	/**
	 * @covers Application_Signals_Dispatcher::removeCallback
	 *
	 * @expectedException \Jet\Application_Signals_Exception
	 * @expectedExceptionCode \Jet\Application_Signals_Exception::CODE_INVALID_SIGNAL_CALLBACK_ID
	 */
	public function testRemoveCallbackFailed1() {
		Application_Signals_Dispatcher::removeCallback('invalid_callback_id');
	}

	/**
	 * @covers Application_Signals_Dispatcher::removeCallback
	 *
	 * @expectedException \Jet\Application_Signals_Exception
	 * @expectedExceptionCode \Jet\Application_Signals_Exception::CODE_INVALID_SIGNAL_CALLBACK_ID
	 */
	public function testRemoveCallbackFailed2() {

		Application_Signals_Dispatcher::removeCallback('/test/imaginary/signal~2');
	}


	/**
	 * @covers Application_Signals_Dispatcher::dispatchSignal
	 * @covers Application_Signals_Dispatcher::getCurrentSignal
	 * @covers Application_Signals_Dispatcher::getSignalQueue
	 */
	public function testDispatchSignal() {


		/*$callback_id_1 = */Application_Signals_Dispatcher::addCallback('/test/signal1', function( Application_Signals_Signal $signal ) {
			/**
			 * @var Application_Signals_SignalTest_Sender $sender
			 */
			$sender = $signal->getSender();
			$sender->setSignalReceived('/test/signal1');
			$sender->getTestCase()->assertEquals( '/test/signal1', Application_Signals_Dispatcher::getCurrentSignal()->getName() );

			$signal = Application_Signals::createSignal( $signal->getSender(), '/test/signal2', ['value'=>1]);
			Application_Signals_Dispatcher::dispatchSignal( $signal );

		});
		/*$callback_id_2 = */Application_Signals_Dispatcher::addCallback('/test/signal2', function( Application_Signals_Signal $signal ) {
			/**
			 * @var Application_Signals_SignalTest_Sender $sender
			 */
			$sender = $signal->getSender();
			$sender->setSignalReceived('/test/signal2');
			$sender->getTestCase()->assertEquals( '/test/signal2', Application_Signals_Dispatcher::getCurrentSignal()->getName() );

			$signal = Application_Signals::createSignal( $signal->getSender(), '/test/signal3', ['value'=>1]);
			Application_Signals_Dispatcher::dispatchSignal( $signal );
		});
		/*$callback_id_3 = */Application_Signals_Dispatcher::addCallback('/test/signal3', function( Application_Signals_Signal $signal ) {
			/**
			 * @var Application_Signals_SignalTest_Sender $sender
			 */
			$sender = $signal->getSender();
			$sender->setSignalReceived('/test/signal3');
			$sender->getTestCase()->assertEquals( '/test/signal3', Application_Signals_Dispatcher::getCurrentSignal()->getName() );
		});

		$sender_mock = new Application_Signals_SignalTest_Sender();
		$sender_mock->setTestCase($this);

		$sender_mock->addSignal('/test/signal1');
		$sender_mock->addSignal('/test/signal2');
		$sender_mock->addSignal('/test/signal3');
		$signal = Application_Signals::createSignal( $sender_mock, '/test/signal1', ['value'=>1]);
		Application_Signals_Dispatcher::dispatchSignal( $signal );

		$this->assertEquals( [
			'/test/signal1',
			'/test/signal2',
			'/test/signal3'
		], $sender_mock->getReceivedSignals());
	}

}
