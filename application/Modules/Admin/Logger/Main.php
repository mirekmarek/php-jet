<?php
/**
 *
 * @copyright 
 * @license  
 * @author  Miroslav Marek &lt;mirek.marek@web-jet.cz&gt;
 */
namespace JetApplicationModule\Admin\Logger;

use Jet\Application_Module;
use Jet\Auth_User_Interface;
use JetApplication\Application_Admin_Services_Logger;

/**
 *
 */
class Main extends Application_Module implements Application_Admin_Services_Logger
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