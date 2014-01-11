<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule\JetExample\AuthManager
 */

namespace JetApplicationModule\JetExample\AuthManager;
use Jet;

class Event extends Jet\DataModel {

	protected static $__data_model_forced_history_enabled = false;
	protected static $__data_model_forced_cache_enabled = false;

	protected static $__data_model_model_name = "Jet_Auth_Events";

	protected static $__data_model_properties_definition = array(
		"ID" => array(
			"type" => self::TYPE_ID,
			"is_ID" => true
		),
		"date_time" => array(
			"type" => self::TYPE_DATE_TIME,
			"is_required" => true
		),
		"event" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 255,
			"is_required" => true
		),
		"event_txt" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 1024,
			"is_required" => true
		),
		"event_data" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 134217728,
			"is_required" => true
		),
		"user_ID" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 255,
			"is_required" => false
		),
		"user_login" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 255,
			"is_required" => false
		),
		"request_URL" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 65536,
			"is_required" => true
		),
		"request_data" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 134217728,
			"is_required" => false
		),
		"remote_IP" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 45,
			"is_required" => true
		),
	);

	/**
	 * @var string
	 */
	protected $ID;

	/**
	 * @var Jet\DateTime
	 */
	protected $date_time;

	/**
	 * @var string
	 */
	protected $event;

	/**
	 * @var string
	 */
	protected $event_txt;

	/**
	 * @var string
	 */
	protected $event_data;

	/**
	 * @var string
	 */
	protected $user_ID;

	/**
	 * @var string
	 */
	protected $user_login="";

	/**
	 * @var string
	 */
	protected $request_URL;

	/**
	 * @var string
	 */
	protected $request_data;

	/**
	 * @var string
	 */
	protected $remote_IP;


	/**
	 * @return Jet\DateTime
	 */
	public function getDateTime() {
		return $this->date_time;
	}

	/**
	 * @return string
	 */
	public function getEvent() {
		return $this->event;
	}

	/**
	 * @return string
	 */
	public function getEventData() {
		return $this->event_data;
	}

	/**
	 * @return string
	 */
	public function getEventTxt() {
		return $this->event_txt;
	}

	/**
	 * @return string
	 */
	public function getRemoteIP() {
		return $this->remote_IP;
	}

	/**
	 * @return string
	 */
	public function getRequestURL() {
		return $this->request_URL;
	}

	/**
	 * @return string
	 */
	public function getRequestData() {
		return $this->request_data;
	}

	/**
	 * @return string
	 */
	public function getUserID() {
		return $this->user_ID;
	}

	/**
	 * @return string
	 */
	public function getUserLogin() {
		return $this->user_login;
	}

	/**
	 * Log auth event
	 *
	 * @param string $event
	 * @param mixed $event_data
	 * @param string $event_txt
	 * @param string $user_ID
	 * @param string $user_login
	 *
	 * @return Event
	 */
	public static function logEvent( $event, $event_data, $event_txt, $user_ID, $user_login ) {
		$event_i = new self();
		$event_i->initNewObject();

		$event_i->date_time = Jet\DateTime::now();
		$event_i->event = $event;
		$event_i->event_data = json_encode($event_data);
		$event_i->event_txt = $event_txt;
		$event_i->user_ID = $user_ID;
		$event_i->user_login = $user_login;

		$event_i->request_URL = Jet\Http_Request::getURL();
		$event_i->remote_IP = Jet\Http_Request::getClientIP();

		$request_data = Jet\Http_Request::getRawPostData();

		$event_i->request_data = json_encode($request_data);

		$event_i->validateProperties();
		$event_i->save();

		return $event_i;
	}
}