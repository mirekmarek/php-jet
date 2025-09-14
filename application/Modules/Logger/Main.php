<?php
/**
 *
 * @copyright 
 * @license  
 * @author  Miroslav Marek &lt;mirek.marek@web-jet.cz&gt;
 */
namespace JetApplicationModule\Logger;

use Jet\Application_Module;
use Jet\Auth_User_Interface;
use JetApplication\Application_Service_Admin_Logger;
use JetApplication\Application_Service_REST_Logger;
use JetApplication\Application_Service_Web_Logger;

/**
 *
 */
class Main extends Application_Module implements Application_Service_Admin_Logger, Application_Service_Web_Logger, Application_Service_REST_Logger
{
	/**
	 *
	 * @param string $event_class
	 * @param string $event
	 * @param string $event_message
	 * @param string|int $context_object_id (optional)
	 * @param string $context_object_name (optional)
	 * @param mixed $context_object_data (optional)
	 * @param Auth_User_Interface|false $current_user
	 */
	public function log( string $event_class,
	                     string $event,
	                     string $event_message,
	                     string|int $context_object_id = '',
	                     string $context_object_name = '',
	                     mixed $context_object_data = [],
	                     Auth_User_Interface|false $current_user = false ) : void
	{
		Event::log(
			$event_class,
			$event,
			$event_message,
			$context_object_id,
			$context_object_name,
			$context_object_data,
			$current_user
		);
		
	}

}