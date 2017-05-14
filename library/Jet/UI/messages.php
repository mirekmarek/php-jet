<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class messages
 * @package Jet
 */
class UI_messages extends BaseObject
{
	const C_SUCCESS = 'success';
	const C_INFO = 'info';
	const C_WARNING = 'warning';
	const C_DANGER = 'danger';


	protected static $session_namespace = '_jsa_ui_messages';

	/**
	 * @var UI_messages_message[]
	 */
	protected static $messages;

	/**
	 * @var Session
	 */
	protected static $session;

	/**
	 * @return string
	 */
	public static function getSessionNamespace()
	{
		return static::$session_namespace;
	}

	/**
	 * @param string $session_namespace
	 */
	public static function setSessionNamespace( $session_namespace )
	{
		static::$session_namespace = $session_namespace;
	}



	/**
	 * @param string $message
	 */
	public static function success( $message )
	{
		static::set( static::C_SUCCESS, $message );
	}

	/**
	 * @param string $class
	 * @param string $message
	 */
	public static function set( $class, $message )
	{
		$s = static::getSession();

		if( !$s->getValueExists( 'msg' ) ) {
			$s->setValue( 'msg', [] );
		}

		$current = $s->getValue( 'msg' );
		$current[] = static::create( $class, $message );
		$s->setValue( 'msg', $current );

		static::$messages = null;
	}

	/**
	 * @return Session
	 */
	protected static function getSession()
	{
		if( static::$session===null ) {
			static::$session = new Session( static::getSessionNamespace() );
		}

		return static::$session;
	}

	/**
	 * @param string $class
	 * @param string $message
	 *
	 * @return UI_messages_message
	 */
	public static function create( $class, $message )
	{
		return new UI_messages_message( $class, $message );
	}

	/**
	 * @param string $message
	 *
	 * @return UI_messages_message
	 */
	public static function createSuccess( $message )
	{
		return static::create( static::C_SUCCESS, $message );
	}

	/**
	 * @param string $message
	 */
	public static function info( $message )
	{
		static::set( static::C_INFO, $message );
	}

	/**
	 * @param string $message
	 *
	 * @return UI_messages_message
	 */
	public static function createInfo( $message )
	{
		return static::create( static::C_INFO, $message );
	}

	/**
	 * @param string $message
	 */
	public static function warning( $message )
	{
		static::set( static::C_WARNING, $message );
	}

	/**
	 * @param string $message
	 *
	 * @return UI_messages_message
	 */
	public static function createWarning( $message )
	{
		return static::create( static::C_WARNING, $message );
	}

	/**
	 * @param string $message
	 */
	public static function danger( $message )
	{
		static::set( static::C_DANGER, $message );
	}

	/**
	 * @param string $message
	 *
	 * @return UI_messages_message
	 */
	public static function createDanger( $message )
	{
		return static::create( static::C_DANGER, $message );
	}

	/**
	 *
	 * @return UI_messages_message[]
	 */
	public static function get()
	{
		if( static::$messages===null ) {
			static::$messages = [];

			$s = static::getSession();

			if( $s->getValueExists( 'msg' ) ) {
				foreach( $s->getValue( 'msg' ) as $msg ) {
					static::$messages[] = $msg;
				}
			}

			$s->setValue( 'msg', [] );

		}

		return static::$messages;
	}
}