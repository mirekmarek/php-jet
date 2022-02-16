<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Logger
{
	const EVENT_CLASS_SUCCESS = 'success';
	const EVENT_CLASS_INFO = 'info';
	const EVENT_CLASS_WARNING = 'warning';
	const EVENT_CLASS_DANGER = 'danger';
	const EVENT_CLASS_FAULT = 'fault';


	/**
	 * @var ?Logger_Interface
	 */
	protected static ?Logger_Interface $logger = null;


	/**
	 * @param Logger_Interface $logger
	 */
	public static function setLogger( Logger_Interface $logger ): void
	{
		static::$logger = $logger;
	}

	/**
	 * @return Logger_Interface|null
	 */
	public static function getLogger(): Logger_Interface|null
	{
		return static::$logger;
	}

	/**
	 *
	 * @param string $event_class
	 * @param string $event
	 * @param string $event_message
	 * @param string $context_object_id (optional)
	 * @param string $context_object_name (optional)
	 * @param mixed $context_object_data (optional)
	 */
	public static function common( string                        $event_class,
	                               string                        $event,
	                               string                        $event_message,
	                               string                        $context_object_id = '',
	                               string                        $context_object_name = '',
	                               mixed                         $context_object_data = []  ): void
	{
		$logger = static::getLogger();
		if( !$logger ) {
			return;
		}


		if(Auth::checkCurrentUser()) {
			$current_user = Auth::getCurrentUser();
		} else {
			$current_user = false;
		}


		$logger->log(
			$event_class,
			$event,
			$event_message,
			$context_object_id,
			$context_object_name,
			$context_object_data,
			$current_user
		);
	}

	/**
	 *
	 * @param string $event
	 * @param string $event_message
	 * @param string $context_object_id (optional)
	 * @param string $context_object_name (optional)
	 * @param mixed $context_object_data (optional)
	 */
	public static function success( string $event,
	                                string $event_message,
	                                string $context_object_id = '',
	                                string $context_object_name = '',
	                                mixed $context_object_data = [] ): void
	{
		static::common(
			static::EVENT_CLASS_SUCCESS,
			$event,
			$event_message,
			$context_object_id,
			$context_object_name,
			$context_object_data
		);
	}

	/**
	 *
	 * @param string $event
	 * @param string $event_message
	 * @param string $context_object_id (optional)
	 * @param string $context_object_name (optional)
	 * @param mixed $context_object_data (optional)
	 */
	public static function info( string $event,
	                             string $event_message,
	                             string $context_object_id = '',
	                             string $context_object_name = '',
	                             mixed $context_object_data = [] ): void
	{
		static::common(
			static::EVENT_CLASS_INFO,
			$event,
			$event_message,
			$context_object_id,
			$context_object_name,
			$context_object_data
		);
	}

	/**
	 *
	 * @param string $event
	 * @param string $event_message
	 * @param string $context_object_id (optional)
	 * @param string $context_object_name (optional)
	 * @param mixed $context_object_data (optional)
	 */
	public static function warning( string $event,
	                                string $event_message,
	                                string $context_object_id = '',
	                                string $context_object_name = '',
	                                mixed $context_object_data = [] ): void
	{
		static::common(
			static::EVENT_CLASS_WARNING,
			$event,
			$event_message,
			$context_object_id,
			$context_object_name,
			$context_object_data
		);
	}

	/**
	 *
	 * @param string $event
	 * @param string $event_message
	 * @param string $context_object_id (optional)
	 * @param string $context_object_name (optional)
	 * @param mixed $context_object_data (optional)
	 */
	public static function danger( string $event,
	                               string $event_message,
	                               string $context_object_id = '',
	                               string $context_object_name = '',
	                               mixed $context_object_data = [] ): void
	{
		static::common(
			static::EVENT_CLASS_DANGER,
			$event,
			$event_message,
			$context_object_id,
			$context_object_name,
			$context_object_data
		);
	}

	/**
	 *
	 * @param string $event
	 * @param string $event_message
	 * @param string $context_object_id (optional)
	 * @param string $context_object_name (optional)
	 * @param mixed $context_object_data (optional)
	 */
	public static function fault( string $event,
	                              string $event_message,
	                              string $context_object_id = '',
	                              string $context_object_name = '',
	                              mixed $context_object_data = [] ): void
	{
		static::common(
			static::EVENT_CLASS_FAULT,
			$event,
			$event_message,
			$context_object_id,
			$context_object_name,
			$context_object_data
		);
	}

}