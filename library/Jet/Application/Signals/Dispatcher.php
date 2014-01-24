<?php
/**
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Application
 * @subpackage Application_Signals
 */
namespace Jet;

class Application_Signals_Dispatcher extends Object {
	
	/**
	 * Callbacks array
	 * array(
	 *      'signal_name' => callable
	 * )
	 * 
	 *
	 * @var array 
	 */
	protected static $callbacks = array();

	/**
	 * @var Application_Signals_Signal[]
	 */
	protected static $signal_queue = array();

	/**
	 * @var Application_Signals_Signal
	 */
	protected static $current_signal = null;


	/**
	 * Adds callback and returns callback ID
	 *
	 * @param string $signal_name
	 * @param callable $callback
	 *
	 * @return string
	 *
	 */
	public static function addCallback( $signal_name, callable $callback ) {
		if(!isset(static::$callbacks[$signal_name])) {
			static::$callbacks[$signal_name] = array();
		}

		$callback_ID = $signal_name.'~'.count( static::$callbacks[$signal_name] );

		static::$callbacks[$signal_name][$callback_ID] = $callback;

		return $callback_ID;
	}

	/**
	 * Removes callback by callback ID (@see addCallback)
	 *
	 * @param string $callback_ID
	 *
	 * @throws Application_Signals_Exception
	 *
	 * @return bool
	 */
	public static function removeCallback( $callback_ID ) {
		if( strpos($callback_ID, '~')===false ) {
			throw new Application_Signals_Exception(
				'Invalid signal callback ID \''.$callback_ID.'\'',
				Application_Signals_Exception::CODE_INVALID_SIGNAL_CALLBACK_ID
			);
		}

		list($signal_name) = explode('~', $callback_ID);

		if(
			!isset(static::$callbacks[$signal_name]) ||
			!isset(static::$callbacks[$signal_name][$callback_ID])
		) {
			throw new Application_Signals_Exception(
				'Invalid signal callback ID \''.$callback_ID.'\'',
				Application_Signals_Exception::CODE_INVALID_SIGNAL_CALLBACK_ID
			);
		}

		static::$callbacks[$signal_name][$callback_ID] = false;
	}

	/**
	 * Sends the signal to callbacks
	 *
	 * @param Application_Signals_Signal $signal
	 *
	 * @throws \Exception|Application_Modules_Exception
	 * @throws Application_Signals_Exception
	 */
	public static function dispatchSignal(Application_Signals_Signal $signal) {
		$signal_name = $signal->getName();

		if(
			isset( static::$signal_queue[$signal_name] ) ||
			(
				static::$current_signal &&
				static::$current_signal->getName()==$signal_name
			)
		){
			throw new Application_Signals_Exception(
				'There is signal \''.$signal_name.'\' in the queue! Loop detected!',
				Application_Signals_Exception::CODE_LOOP
			);
		}

		static::$signal_queue[$signal_name] = $signal;

		if( static::$current_signal ) {
			return;
		}


		while( static::$signal_queue ) {
			static::$current_signal = array_shift(static::$signal_queue);

			$signal_name = static::$current_signal->getName();

			if( isset(static::$callbacks[$signal_name]) ) {
				foreach(static::$callbacks[$signal_name] as $callback) {
					if( $callback===false ) {
						continue;
					}

					$callback( $signal );
				}
			}

			$active_modules = array();
			try {
				$active_modules = Application_Modules::getActivatedModulesList();
			} catch( Application_Modules_Exception $e ) {
				if( $e->getCode()!=Application_Modules_Exception::CODE_MODULES_LIST_NOT_FOUND ) {
					throw $e;
				}
			}
			foreach($active_modules as $module_name => $module_info){
				/**
				 * @var Application_Modules_Module_Info $module_info
				 */
				$signal_callbacks = $module_info->getSignalCallbacks();

				if(
					!$signal_callbacks ||
					empty($signal_callbacks[$signal_name])
				){
					continue;
				}

				$callbacks = $signal_callbacks[$signal_name];
				if( !is_array($callbacks) ){
					$callbacks = array($callbacks);
				}


				$module_instance = Application_Modules::getModuleInstance( $module_name );
				foreach( $callbacks as $callback ) {
					$module_instance->$callback( $signal );
				}
			}
		}

		static::$current_signal = null;
		static::$signal_queue = array();
	}

	/**
	 * Returns current signal or null if there is no current signal
	 *
	 * @return null|Application_Signals_Signal
	 */
	public static function getCurrentSignal() {
		return static::$current_signal;
	}

	/**
	 *
	 * @return Application_Signals_Signal[]
	 */
	public static function getSignalQueue() {
		return self::$signal_queue;
	}


}