<?php
/**
 *
 *
 *
 * Implementation of signal (message).
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>,
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Application
 * @subpackage Application_Signals
 */

namespace Jet;

class Application_Signals extends Object {
	const DEFAULT_SIGNAL_OBJECT_CLASS_NAME = "Jet\\Application_Signals_Signal";

	/**
	 * @param \Jet\Object $sender
	 * @param $signal_name
	 * @param array $signal_data (optional)
	 *
	 * @throws Application_Signals_Exception
	 * @return Application_Signals_Signal
	 */
	public static function createSignal( Object $sender, $signal_name, array $signal_data=array() ) {
		if( !$sender->getHasSignal( $signal_name ) ) {
			throw new Application_Signals_Exception(
				"Unknown signal '{$signal_name}'. Please add a item to the ".get_class($sender)."::\$__signals object property. ",
				Application_Signals_Exception::CODE_UNKNOWN_SIGNAL
			);
		}

		$signal_object_class_name = $sender->getSignalObjectClassName($signal_name);

		$signal = new $signal_object_class_name( $sender, $signal_name, $signal_data );

		if( !($signal instanceof Application_Signals_Signal) ) {
			throw new Application_Signals_Exception(
				"Signal must be instance of \\Jet\\Application_Signals_Signal! (Signal: '$signal_name', Signal object class name: '".get_class($signal)."' ) ",
				Application_Signals_Exception::INVALID_SIGNAL_OBJECT_CLASS
			);
		}

		return $signal;

	}

}