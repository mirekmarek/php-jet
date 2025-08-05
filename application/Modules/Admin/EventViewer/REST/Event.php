<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Admin\EventViewer\REST;

use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_Fetch_Instances;
use Jet\DataModel_IDController_AutoIncrement;


use Jet\Data_DateTime;
use Jet\Logger;
use Jet\Tr;
use Jet\UI;
use Jet\UI_badge;

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
	
	public function getId(): int
	{
		return $this->id;
	}
	
	public function getDateTime(): Data_DateTime
	{
		return $this->date_time;
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
	
	public function getRemoteIP(): string
	{
		return $this->remote_IP;
	}
	
	public function getRequestURL(): string
	{
		return $this->request_URL;
	}
	
	public function getUserId(): string
	{
		return $this->user_id;
	}
	
	public function getUserUsername(): string
	{
		return $this->user_username;
	}
	
	public static function get( int $id ): static|null
	{
		return static::load( $id );
	}
	
	/**
	 *
	 * @return DataModel_Fetch_Instances|static[]
	 * @noinspection PhpDocSignatureInspection
	 */
	public static function getList(): iterable
	{
		
		$where = [];
		
		$list = static::fetchInstances(
			$where,
			[
				'id',
				'date_time',
				'event_class',
				'event',
				'event_message',
				'context_object_id',
				'context_object_name',
				'user_id',
				'user_username',
			] );
		
		$list->getQuery()->setOrderBy( '-id' );
		
		return $list;
	}
	
}