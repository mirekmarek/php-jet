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
interface Application_Log_Logger_Interface
{

	/**
	 *
	 * @param string $event_class
	 * @param string $event
	 * @param string $event_message
	 * @param string $context_object_id (optional)
	 * @param string $context_object_name (optional)
	 * @param mixed $context_object_data (optional)
	 * @param Auth_User_Interface $current_user (optional; default: null)
	 */
	public function log($event_class, $event, $event_message, $context_object_id='', $context_object_name='', $context_object_data=[], Auth_User_Interface $current_user=null );
}