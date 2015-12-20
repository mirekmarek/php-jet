<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Object
 */

namespace Jet;

//We do not have multiple inheritance in PHP :-(
trait Object_Trait {

	/**
	 * @return string
	 */
	public function getObjectIdentificationKey() {
		$object_key = get_class($this).':'.spl_object_hash($this);
		return $object_key;
	}

	/**
	 * @param $signal_name
	 *
	 * @return bool
	 */
	public function getHasSignal( $signal_name ) {
		$signals = Object_Reflection::get( get_class($this), 'signals', []);

		return in_array( $signal_name, $signals );
	}

	/**
	 *
	 * @param string $signal_name
	 *
	 * @return string
	 */
	public function getSignalObjectClassName(
		/** @noinspection PhpUnusedParameterInspection */
		$signal_name
	) {

		return Object_Reflection::get( get_class($this), 'signal_object_class_name', __NAMESPACE__.'\\'.Application_Signals::DEFAULT_SIGNAL_OBJECT_CLASS_NAME );
	}

	/**
	 * @param $signal_name
	 * @param array $signal_data
	 *
	 * @throws Object_Exception
	 *
	 * @return Application_Signals_Signal
	 */
	public function sendSignal( $signal_name, array $signal_data= []) {

		/** @var $this Object_Interface */
		$signal = Application_Signals::createSignal( $this, $signal_name, $signal_data );

		Application_Signals_Dispatcher::dispatchSignal( $signal );

		return $signal;
	}

	/**
	 * @param $property_name
	 *
	 * @return bool
	 */
	public function getHasProperty( $property_name ) {
		if(
			$property_name[0]=='_' ||
			!property_exists($this, $property_name)
		) {
			return false;
		}
		return true;
	}

	/**
	 * @param $property_name
	 *
	 * @return string
	 */
	public function getSetterMethodName( $property_name ) {
		$setter_method_name = 'set'.str_replace('_', '', $property_name);

		return $setter_method_name;
	}

	/**
	 * @param $property_name
	 *
	 * @return string
	 */
	public function getGetterMethodName( $property_name ) {
		$setter_method_name = 'get'.str_replace('_', '', $property_name);

		return $setter_method_name;
	}

}