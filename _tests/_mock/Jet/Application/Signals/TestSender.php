<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package Application
 * @subpackage Application_Signals
 */
namespace Jet;

class Application_Signals_SignalTest_Sender extends Object {

	/**
	 * @var string
	 */
	protected $__signals_signal_object_class_name = Application_Signals::DEFAULT_SIGNAL_OBJECT_CLASS_NAME;

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
	protected $signals = array();

	protected $recived_signals = array();

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

	public function getRecivedSignals() {
		return $this->recived_signals;
	}

	public function addSignal( $signal ) {
		$this->signals[] = $signal;
	}

	public function getHasSignal( $signal_name ) {
		return in_array( $signal_name, $this->signals );
	}

	public function setSignalRecived( $signal_name ) {
		$this->recived_signals[] = $signal_name;
	}
	/**
	 * @param string $signal_name
	 *
	 * @return string
	 */
	public function getSignalObjectClassName( $signal_name ) {
		return $this->__signals_signal_object_class_name;
	}


	/**
	 * @param string $_signals_signal_object_class_name
	 */
	public function setSignalsSignalObjectClassName($_signals_signal_object_class_name) {
		$this->__signals_signal_object_class_name = $_signals_signal_object_class_name;
	}



}
