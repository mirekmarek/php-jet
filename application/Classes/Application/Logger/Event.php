<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\DataModel;
use Jet\DataModel_IDController_UniqueString;

use Jet\Auth_User_Interface;

use Jet\Data_DateTime;
use Jet\Http_Request;

/**
 *
 * @JetDataModel:name = 'Auth_Event'
 * @JetDataModel:id_controller_class_name = 'DataModel_IDController_UniqueString'
 */
abstract class Application_Log_Event extends DataModel
{

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_id = true
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATE_TIME
	 *
	 * @var Data_DateTime
	 */
	protected $date_time;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:is_key = true
	 *
	 * @var string
	 */
	protected $event_class = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:is_key = true
	 *
	 * @var string
	 */
	protected $event = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 1024
	 *
	 * @var string
	 */
	protected $event_message = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:is_key = true
	 *
	 * @var string
	 */
	protected $context_object_id = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 *
	 * @var string
	 */
	protected $context_object_name = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 134217728
	 *
	 * @var string
	 */
	protected $context_object_data = '';


	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_INT
	 * @JetDataModel:is_key = true
	 *
	 * @var string
	 */
	protected $user_id = 0;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:is_key = true
	 *
	 * @var string
	 */
	protected $user_username = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 65536
	 *
	 * @var string
	 */
	protected $request_URL = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 45
	 *
	 * @var string
	 */
	protected $remote_IP = '';

	/**
	 *
	 * @param string                   $event_class
	 * @param string                   $event
	 * @param string                   $event_message
	 * @param string                   $context_object_id
	 * @param string                   $context_object_name
	 * @param mixed                    $context_object_data
	 * @param Auth_User_Interface|null $current_user
	 *
	 * @return static
	 */
	public static function log( $event_class, $event, $event_message, $context_object_id, $context_object_name, $context_object_data, $current_user = null )
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
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return Data_DateTime
	 */
	public function getDateTime()
	{
		return $this->date_time;
	}

	/**
	 * @return mixed
	 */
	public function getEventClass()
	{
		return $this->event_class;
	}

	/**
	 * @return string
	 */
	public function getEvent()
	{
		return $this->event;
	}

	/**
	 * @return string
	 */
	public function getEventMessage()
	{
		return $this->event_message;
	}

	/**
	 * @return mixed
	 */
	public function getContextObjectId()
	{
		return $this->context_object_id;
	}

	/**
	 * @return mixed
	 */
	public function getContextObjectName()
	{
		return $this->context_object_name;
	}

	/**
	 * @return mixed
	 */
	public function getContextObjectData()
	{
		return $this->context_object_data;
	}

	/**
	 * @return string
	 */
	public function getRemoteIP()
	{
		return $this->remote_IP;
	}

	/**
	 * @return string
	 */
	public function getRequestURL()
	{
		return $this->request_URL;
	}

	/**
	 * @return string
	 */
	public function getUserId()
	{
		return $this->user_id;
	}

	/**
	 * @return string
	 */
	public function getUserUsername()
	{
		return $this->user_username;
	}
}