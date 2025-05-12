<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\REST\Logger;

use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_IDController_AutoIncrement;

use Jet\Auth_User_Interface;

use Jet\Data_DateTime;
use Jet\Http_Request;

#[DataModel_Definition(
	database_table_name: 'events_rest',
	name: 'logger_event',
	id_controller_class: DataModel_IDController_AutoIncrement::class,
	id_controller_options: ['id_property_name' => 'id']
)]
class Event extends DataModel
{
	
	#[DataModel_Definition(
		type: DataModel::TYPE_ID_AUTOINCREMENT,
		is_id: true
	)]
	protected int $id = 0;
	
	#[DataModel_Definition(
		type: DataModel::TYPE_DATE_TIME
	)]
	protected ?Data_DateTime $date_time = null;
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
		is_key: true
	)]
	protected string $event_class = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
		is_key: true
	)]
	protected string $event = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 1024
	)]
	protected string $event_message = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
		is_key: true
	)]
	protected string $context_object_id = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255
	)]
	protected string $context_object_name = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 134217728
	)]
	protected string $context_object_data = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		is_key: true
	)]
	protected string|int $user_id = 0;
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
		is_key: true
	)]
	protected string $user_username = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 65536
	)]
	protected string $request_URL = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 45
	)]
	protected string $remote_IP = '';
	
	
	public static function log( string $event_class,
	                            string $event,
	                            string $event_message,
	                            string $context_object_id = '',
	                            string $context_object_name = '',
	                            mixed $context_object_data = [],
	                            Auth_User_Interface|bool $current_user = false ): static
	{
		
		
		$event_i = new static();
		
		$event_i->date_time = Data_DateTime::now();
		$event_i->request_URL = Http_Request::URL();
		$event_i->remote_IP = Http_Request::clientIP();
		
		$event_i->event_class = $event_class;
		$event_i->event = $event;
		$event_i->event_message = $event_message;
		
		$event_i->context_object_id = $context_object_id;
		$event_i->context_object_name = $context_object_name;
		$event_i->context_object_data = json_encode( $context_object_data );
		
		if( $current_user instanceof Auth_User_Interface ) {
			$event_i->user_id = $current_user->getId();
			$event_i->user_username = $current_user->getUsername();
		}
		
		
		$event_i->save();
		
		return $event_i;
	}
	
	
}