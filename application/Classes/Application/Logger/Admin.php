<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Application_Logger_Interface;
use Jet\Auth_User_Interface;
use Jet\BaseObject;

/**
 *
 */
class Application_Logger_Admin extends BaseObject implements Application_Logger_Interface
{

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
	public function log( string $event_class,
	                     string $event,
	                     string $event_message,
	                     string $context_object_id = '',
	                     string $context_object_name = '',
	                     mixed $context_object_data = [],
	                     Auth_User_Interface|null|bool $current_user = null )
	{
		Application_Logger_Admin_Event::log(
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