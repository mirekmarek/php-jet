<?php
/**
 *
 *
 *
 * Implementation of signal (message).
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>,
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Application
 * @subpackage Application_Signals
 */

namespace Jet;

class Application_Signals extends Object implements Object_Reflection_ParserInterface {
	const DEFAULT_SIGNAL_OBJECT_CLASS_NAME = 'Application_Signals_Signal';

	/**
	 * @param Object_Interface $sender
	 * @param $signal_name
	 * @param array $signal_data (optional)
	 *
	 * @throws Application_Signals_Exception
	 * @return Application_Signals_Signal
	 */
	public static function createSignal( Object_Interface $sender, $signal_name, array $signal_data= []) {
		if( !$sender->getHasSignal( $signal_name ) ) {
			throw new Application_Signals_Exception(
				'Unknown signal \''.$signal_name.'\'. Please add definition to the '.get_class($sender).' ( @JetApplication_Signals:signal=\''.$signal_name.'\' ) ',
				Application_Signals_Exception::CODE_UNKNOWN_SIGNAL
			);
		}


		$signal_object_class_name = $sender->getSignalObjectClassName($signal_name);

		$signal = new $signal_object_class_name( $sender, $signal_name, $signal_data );

		if( !($signal instanceof Application_Signals_Signal) ) {
			throw new Application_Signals_Exception(
				'Signal must be instance of '.__NAMESPACE__.'\Application_Signals_Signal! (Signal: \''.$signal_name.'\', Signal object class name: \''.get_class($signal).'\' ) ',
				Application_Signals_Exception::INVALID_SIGNAL_OBJECT_CLASS
			);
		}

		return $signal;

	}

	/**
	 * @param array $reflection_data
	 * @param $class_name
	 * @param string $key
	 * @param string $definition
	 * @param mixed $value
	 *
	 * @throws Object_Reflection_Exception
	 */
	public static function parseClassDocComment(&$reflection_data, $class_name, $key, $definition, $value) {

		switch($key) {
			case 'signal_object_class_name':
				$reflection_data['signal_object_class_name'] = Object_Reflection::parseClassName( (string)$value );
				break;
			case 'signal':
				$reflection_data['signals'][] = (string)$value;
				break;
			default:
				throw new Object_Reflection_Exception('Unknown definition! Class: \''.$class_name.'\', definition: \''.$definition.'\' ');
		}

	}

	/**
	 * @param array &$reflection_data
	 * @param $class_name
	 * @param string $property_name
	 * @param string $key
	 * @param string $definition
	 * @param mixed $value
	 *
	 * @throws Object_Reflection_Exception
	 */
	public static function parsePropertyDocComment(&$reflection_data, $class_name, $property_name, $key, $definition, $value) {
		throw new Object_Reflection_Exception(
			'Unknown definition! Class: \''.$class_name().'\', property: \''.$property_name.'\', definition: \''.$definition.'\' ',
			Object_Reflection_Exception::CODE_UNKNOWN_PROPERTY_DEFINITION
		);
	}

}