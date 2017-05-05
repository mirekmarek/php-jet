<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Application_Log extends BaseObject
{
	const EVENT_CLASS_SUCCESS = 'success';
	const EVENT_CLASS_INFO = 'info';
	const EVENT_CLASS_WARNING = 'warning';
	const EVENT_CLASS_DANGER = 'danger';
	const EVENT_CLASS_FAULT = 'fault';


	/**
	 * @var Application_Log_Logger_Interface
	 */
	protected static $logger;

	/**
	 *
	 * @param string              $event
	 * @param string              $event_message
	 * @param string              $context_object_id (optional)
	 * @param string              $context_object_name (optional)
	 * @param mixed               $context_object_data (optional)
	 * @param Auth_User_Interface $current_user (optional; default: null = current user)
	 */
	public static function success( $event, $event_message, $context_object_id = '', $context_object_name = '', $context_object_data = [], Auth_User_Interface $current_user = null )
	{
		static::common(
			static::EVENT_CLASS_SUCCESS, $event, $event_message, $context_object_id, $context_object_name,
			$context_object_data, $current_user
		);
	}

	/**
	 *
	 * @param string              $event_class
	 * @param string              $event
	 * @param string              $event_message
	 * @param string              $context_object_id (optional)
	 * @param string              $context_object_name (optional)
	 * @param mixed               $context_object_data (optional)
	 * @param Auth_User_Interface $current_user (optional; default: null = current user)
	 */
	public static function common( $event_class, $event, $event_message, $context_object_id = '', $context_object_name = '', $context_object_data = [], Auth_User_Interface $current_user = null )
	{
		$logger = static::getLogger();
		if( !$logger ) {
			return;
		}

		if( $current_user===null ) {
			$current_user = Auth::getCurrentUser();
		}


		static::getLogger()->log(
			$event_class, $event, $event_message, $context_object_id, $context_object_name, $context_object_data,
			$current_user
		);
	}

	/**
	 * @return Application_Log_Logger_Interface
	 */
	public static function getLogger()
	{
		return self::$logger;
	}

	/**
	 * @param Application_Log_Logger_Interface $logger
	 */
	public static function setLogger( Application_Log_Logger_Interface $logger )
	{
		self::$logger = $logger;
	}

	/**
	 *
	 * @param string              $event
	 * @param string              $event_message
	 * @param string              $context_object_id (optional)
	 * @param string              $context_object_name (optional)
	 * @param mixed               $context_object_data (optional)
	 * @param Auth_User_Interface $current_user (optional; default: null = current user)
	 */
	public static function info( $event, $event_message, $context_object_id = '', $context_object_name = '', $context_object_data = [], Auth_User_Interface $current_user = null )
	{
		static::common(
			static::EVENT_CLASS_INFO, $event, $event_message, $context_object_id, $context_object_name,
			$context_object_data, $current_user
		);
	}

	/**
	 *
	 * @param string              $event
	 * @param string              $event_message
	 * @param string              $context_object_id (optional)
	 * @param string              $context_object_name (optional)
	 * @param mixed               $context_object_data (optional)
	 * @param Auth_User_Interface $current_user (optional; default: null = current user)
	 */
	public static function warning( $event, $event_message, $context_object_id = '', $context_object_name = '', $context_object_data = [], Auth_User_Interface $current_user = null )
	{
		static::common(
			static::EVENT_CLASS_WARNING, $event, $event_message, $context_object_id, $context_object_name,
			$context_object_data, $current_user
		);
	}

	/**
	 *
	 * @param string              $event
	 * @param string              $event_message
	 * @param string              $context_object_id (optional)
	 * @param string              $context_object_name (optional)
	 * @param mixed               $context_object_data (optional)
	 * @param Auth_User_Interface $current_user (optional; default: null = current user)
	 */
	public static function danger( $event, $event_message, $context_object_id = '', $context_object_name = '', $context_object_data = [], Auth_User_Interface $current_user = null )
	{
		static::common(
			static::EVENT_CLASS_DANGER, $event, $event_message, $context_object_id, $context_object_name,
			$context_object_data, $current_user
		);
	}

	/**
	 *
	 * @param string              $event
	 * @param string              $event_message
	 * @param string              $context_object_id (optional)
	 * @param string              $context_object_name (optional)
	 * @param mixed               $context_object_data (optional)
	 * @param Auth_User_Interface $current_user (optional; default: null = current user)
	 */
	public static function fault( $event, $event_message, $context_object_id = '', $context_object_name = '', $context_object_data = [], Auth_User_Interface $current_user = null )
	{
		static::common(
			static::EVENT_CLASS_FAULT, $event, $event_message, $context_object_id, $context_object_name,
			$context_object_data, $current_user
		);
	}

}