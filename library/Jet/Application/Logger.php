<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Application_Logger extends BaseObject
{
	const EVENT_CLASS_SUCCESS = 'success';
	const EVENT_CLASS_INFO = 'info';
	const EVENT_CLASS_WARNING = 'warning';
	const EVENT_CLASS_DANGER = 'danger';
	const EVENT_CLASS_FAULT = 'fault';


	/**
	 * @var Application_Logger_Interface
	 */
	protected static Application_Logger_Interface $logger;


	/**
	 * @param Application_Logger_Interface $logger
	 */
	public static function setLogger( Application_Logger_Interface $logger ) : void
	{
		static::$logger = $logger;
	}

	/**
	 * @return Application_Logger_Interface
	 */
	public static function getLogger() : Application_Logger_Interface
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
	 * @param Auth_User_Interface|null|bool $current_user
	 */
	public static function common( string $event_class,
	                               string $event,
	                               string $event_message,
	                               string $context_object_id = '',
	                               string $context_object_name = '',
	                               mixed $context_object_data = [],
	                               Auth_User_Interface|null|bool $current_user = null ) : void
	{
		$logger = static::getLogger();
		if( !$logger ) {
			return;
		}

		if( $current_user===null ) {
			$current_user = Auth::getCurrentUser();
		}

		if(!$current_user) {
			$current_user = null;
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
	 * @param Auth_User_Interface|null|bool $current_user
	 */
	public static function success( string $event,
	                                string $event_message,
	                                string $context_object_id = '',
	                                string $context_object_name = '',
	                                mixed $context_object_data = [],
	                                Auth_User_Interface|null|bool $current_user = null ) : void
	{
		static::common(
			static::EVENT_CLASS_SUCCESS,
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
	 * @param Auth_User_Interface|null|bool $current_user
	 */
	public static function info( string $event,
	                             string $event_message,
	                             string $context_object_id = '',
	                             string $context_object_name = '',
	                             mixed $context_object_data = [],
	                             Auth_User_Interface|null|bool $current_user = null ) : void
	{
		static::common(
			static::EVENT_CLASS_INFO,
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
	 * @param Auth_User_Interface|null|bool $current_user
	 */
	public static function warning( string $event,
	                                string $event_message,
	                                string $context_object_id = '',
	                                string $context_object_name = '',
	                                mixed $context_object_data = [],
	                                Auth_User_Interface|null|bool $current_user = null ) : void
	{
		static::common(
			static::EVENT_CLASS_WARNING,
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
	 * @param Auth_User_Interface|null|bool $current_user
	 */
	public static function danger( string $event,
	                               string $event_message,
	                               string $context_object_id = '',
	                               string $context_object_name = '',
	                               mixed $context_object_data = [],
	                               Auth_User_Interface|null|bool $current_user = null ) : void
	{
		static::common(
			static::EVENT_CLASS_DANGER,
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
	 * @param Auth_User_Interface|null|bool $current_user
	 */
	public static function fault( string $event,
	                              string $event_message,
	                              string $context_object_id = '',
	                              string $context_object_name = '',
	                              mixed $context_object_data = [],
	                              Auth_User_Interface|null|bool $current_user = null ) : void
	{
		static::common(
			static::EVENT_CLASS_FAULT,
			$event,
			$event_message,
			$context_object_id,
			$context_object_name,
			$context_object_data,
			$current_user
		);
	}

}