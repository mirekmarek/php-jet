<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetUI;
use Jet\BaseObject;
use Jet\Session;


class messages extends BaseObject
{
	const C_SUCCESS = 'success';
	const C_INFO = 'info';
	const C_WARNING = 'warning';
	const C_DANGER = 'danger';

	const SESSION_NS = '_jsa_ui_messages';

	/**
	 * @var messages_message[]
	 */
	protected static $messages;

	/**
	 * @var Session
	 */
	protected static $session;

	/**
	 * @return Session
	 */
	protected static function getSession()
	{
		if(static::$session===null) {
			static::$session = new Session( static::SESSION_NS );
		}
		return self::$session;
	}

	/**
	 * @param $message
	 */
	public static function success( $message ) {
		static::set(static::C_SUCCESS, $message);
	}

	/**
	 * @param $message
	 *
	 * @return messages_message
	 */
	public static function createSuccess( $message ) {
		return static::create(static::C_SUCCESS, $message);
	}

	/**
	 * @param $message
	 */
	public static function info( $message ) {
		static::set(static::C_INFO, $message);
	}

	/**
	 * @param $message
	 *
	 * @return messages_message
	 */
	public static function createInfo( $message ) {
		return static::create(static::C_INFO, $message);
	}

	/**
	 * @param $message
	 */
	public static function warning( $message ) {
		static::set(static::C_WARNING, $message);
	}

	/**
	 * @param $message
	 *
	 * @return messages_message
	 */
	public static function createWarning( $message ) {
		return static::create(static::C_WARNING, $message);
	}

	/**
	 * @param $message
	 */
	public static function danger( $message ) {
		static::set(static::C_DANGER, $message);
	}

	/**
	 * @param $message
	 *
	 * @return messages_message
	 */
	public static function createDanger( $message ) {
		return static::create(static::C_DANGER, $message);
	}


	/**
	 * @param string $class
	 * @param string $message
	 */
	public static function set( $class, $message ) {
		$s = static::getSession();

		if(!$s->getValueExists('msg')) {
			$s->setValue('msg', []);
		}

		$current = $s->getValue('msg');
		$current[] = static::create($class, $message);
		$s->setValue('msg', $current);

		static::$messages = null;
	}

	/**
	 * @param string $class
	 * @param string $message
	 *
	 * @return messages_message
	 */
	public static function create( $class, $message ) {
		return new messages_message( $class, $message );
	}


	/**
	 *
	 * @return messages_message[]
	 */
	public static function get()
	{
		if(static::$messages===null) {
			static::$messages = [];

			$s = static::getSession();

			if($s->getValueExists('msg')) {
				foreach($s->getValue('msg') as $msg) {
					static::$messages[] = $msg;
				}
			}

			$s->setValue('msg', []);

		}

		return static::$messages;
	}
}