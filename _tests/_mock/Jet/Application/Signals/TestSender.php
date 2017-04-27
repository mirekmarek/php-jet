<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Application_Signals_SignalTest_Sender
 *
 */
class Application_Signals_SignalTest_Sender extends BaseObject {

	protected $_test_signals_signal_object_class_name = Application_Signals::DEFAULT_SIGNAL_OBJECT_CLASS_NAME;

	/**
	 * Signals list
	 *
	 * array(
	 *      "/my_signal_group/signal1",
	 *      "/my_signal_group/signal2"
	 * )
	 *
	 *
	 * @var array
	 */
	protected $signals = [];

	protected $received_signals = [];

	/**
	 * @var \PHPUnit_Framework_TestCase
	 */
	protected $test_case;

	/**
	 * @param \PHPUnit_Framework_TestCase $test_case
	 */
	public function setTestCase($test_case) {
		$this->test_case = $test_case;
	}

	/**
	 * @return \PHPUnit_Framework_TestCase
	 */
	public function getTestCase() {
		return $this->test_case;
	}

	public function getReceivedSignals() {
		return $this->received_signals;
	}

	public function addSignal( $signal ) {
		$this->signals[] = $signal;
	}

	public function getHasSignal( $signal_name ) {
		return in_array( $signal_name, $this->signals );
	}

	/**
	 * @param $signal_name
	 */
	public function setSignalReceived($signal_name ) {
		$this->received_signals[] = $signal_name;
	}
	/**
	 * @param string $signal_name
	 *
	 * @return string
	 */
	public function getSignalObjectClassName( $signal_name ) {

		return BaseObject_Reflection::parseClassName( $this->_test_signals_signal_object_class_name );
	}


	/**
	 * @param string $_signals_signal_object_class_name
	 */
	public function setSignalsSignalObjectClassName($_signals_signal_object_class_name) {
		$this->_test_signals_signal_object_class_name = $_signals_signal_object_class_name;
	}



}
