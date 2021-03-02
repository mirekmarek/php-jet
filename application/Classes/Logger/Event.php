<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_IDController_AutoIncrement;

use Jet\Auth_User_Interface;

use Jet\Data_DateTime;
use Jet\Http_Request;

/**
 *
 */
#[DataModel_Definition(
	name: 'Auth_Event',
	id_controller_class: DataModel_IDController_AutoIncrement::class,
	id_controller_options: ['id_property_name' => 'id']
)]
abstract class Logger_Event extends DataModel
{

	/**
	 * @var int
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_ID_AUTOINCREMENT,
		is_id: true
	)]
	protected int $id = 0;

	/**
	 * @var ?Data_DateTime
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_DATE_TIME
	)]
	protected ?Data_DateTime $date_time = null;

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
		is_key: true
	)]
	protected string $event_class = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
		is_key: true
	)]
	protected string $event = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 1024
	)]
	protected string $event_message = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
		is_key: true
	)]
	protected string $context_object_id = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255
	)]
	protected string $context_object_name = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 134217728
	)]
	protected string $context_object_data = '';


	/**
	 * @var string|int
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		is_key: true
	)]
	protected string|int $user_id = 0;

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
		is_key: true
	)]
	protected string $user_username = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 65536
	)]
	protected string $request_URL = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 45
	)]
	protected string $remote_IP = '';

	/**
	 *
	 * @param string $event_class
	 * @param string $event
	 * @param string $event_message
	 * @param string $context_object_id
	 * @param string $context_object_name
	 * @param mixed $context_object_data
	 * @param Auth_User_Interface|bool $current_user
	 *
	 * @return static
	 */
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

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return Data_DateTime
	 */
	public function getDateTime(): Data_DateTime
	{
		return $this->date_time;
	}

	/**
	 * @return string
	 */
	public function getEventClass(): string
	{
		return $this->event_class;
	}

	/**
	 * @return string
	 */
	public function getEvent(): string
	{
		return $this->event;
	}

	/**
	 * @return string
	 */
	public function getEventMessage(): string
	{
		return $this->event_message;
	}

	/**
	 * @return string
	 */
	public function getContextObjectId(): string
	{
		return $this->context_object_id;
	}

	/**
	 * @return string
	 */
	public function getContextObjectName(): string
	{
		return $this->context_object_name;
	}

	/**
	 * @return string
	 */
	public function getContextObjectData(): string
	{
		return $this->context_object_data;
	}

	/**
	 * @return string
	 */
	public function getRemoteIP(): string
	{
		return $this->remote_IP;
	}

	/**
	 * @return string
	 */
	public function getRequestURL(): string
	{
		return $this->request_URL;
	}

	/**
	 * @return string
	 */
	public function getUserId(): string
	{
		return $this->user_id;
	}

	/**
	 * @return string
	 */
	public function getUserUsername(): string
	{
		return $this->user_username;
	}
}