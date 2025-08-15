<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Logger;

use Jet\Auth_User_Interface;
use Jet\Data_DateTime;
use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_IDController_AutoIncrement;
use Jet\Http_Request;
use Jet\Locale;
use Jet\Logger;
use Jet\MVC;
use Jet\UI;
use Jet\UI_badge;
use Jet\Tr;


#[DataModel_Definition(
	database_table_name: 'events_administration',
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
	public int $id = 0;
	
	#[DataModel_Definition(
		type: DataModel::TYPE_DATE_TIME
	)]
	public ?Data_DateTime $date_time = null;
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
		is_key: true
	)]
	protected string $base_id = '';
	
	
	#[DataModel_Definition(
		type: DataModel::TYPE_LOCALE,
		is_key: true
	)]
	protected ?Locale $locale = null;
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
		is_key: true
	)]
	protected string $page_id = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
		is_key: true
	)]
	public string $event_class = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
		is_key: true
	)]
	public string $event = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 1024
	)]
	public string $event_message = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
		is_key: true
	)]
	public string $context_object_id = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255
	)]
	public string $context_object_name = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 134217728
	)]
	public string $context_object_data = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		is_key: true
	)]
	public string|int $user_id = 0;
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
		is_key: true
	)]
	public string $user_username = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 65536
	)]
	public string $request_URL = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 45
	)]
	public string $remote_IP = '';


	public static function log( string $event_class,
	                            string $event,
	                            string $event_message,
	                            string $context_object_id = '',
	                            string $context_object_name = '',
	                            mixed $context_object_data = [],
	                            Auth_User_Interface|false $current_user = false ): static
	{
		$event_i = new static();
		$page = MVC::getPage();
		if($page) {
			$event_i->base_id  = $page->getBaseId();
			$event_i->locale = $page->getLocale();
			$event_i->page_id = $page->getId();
		}

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
	
	public function getId(): int
	{
		return $this->id;
	}
	
	public function getDateTime(): ?Data_DateTime
	{
		return $this->date_time;
	}
	
	public function getBaseId(): string
	{
		return $this->base_id;
	}
	
	public function getLocale(): ?Locale
	{
		return $this->locale;
	}
	
	public function getPageId(): string
	{
		return $this->page_id;
	}
	
	public function getEventClass(): string
	{
		return $this->event_class;
	}
	
	public function getEventClassReadable() : string
	{
		return match ($this->getEventClass()) {
			Logger::EVENT_CLASS_DANGER  => UI::badge( UI_badge::DANGER, Tr::_( 'danger' ) ),
			Logger::EVENT_CLASS_FAULT   => UI::badge( UI_badge::WARNING, Tr::_( 'fault' ) ),
			Logger::EVENT_CLASS_INFO    => UI::badge( UI_badge::INFO, Tr::_( 'info' ) ),
			Logger::EVENT_CLASS_SUCCESS => UI::badge( UI_badge::SUCCESS, Tr::_( 'success' ) ),
			Logger::EVENT_CLASS_WARNING => UI::badge( UI_badge::WARNING, Tr::_( 'warning' ) ),
			default                     => '?? ' . $this->getEventClass() . ' ??',
		};
		
	}
	
	
	public function getEvent(): string
	{
		return $this->event;
	}
	
	public function getEventMessage(): string
	{
		return $this->event_message;
	}
	
	public function getContextObjectId(): string
	{
		return $this->context_object_id;
	}
	
	public function getContextObjectName(): string
	{
		return $this->context_object_name;
	}
	
	public function getContextObjectData(): string
	{
		return $this->context_object_data;
	}
	
	public function getUserId(): int|string
	{
		return $this->user_id;
	}
	
	public function getUserUsername(): string
	{
		return $this->user_username;
	}
	
	public function getRequestURL(): string
	{
		return $this->request_URL;
	}
	
	public function getRemoteIP(): string
	{
		return $this->remote_IP;
	}


}