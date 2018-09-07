<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
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
	 * @param string $context
	 */
	public static function set( $class, $message, $context='' )
	{
		$s = static::getSession();

		if( !$s->getValueExists( 'msg' ) ) {
			$s->setValue( 'msg', [] );
		}

		$current = $s->getValue( 'msg' );
		$current[] = static::create( $class, $message, $context );
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
	 * @param string $context=''
	 *
	 * @return UI_messages_message
	 */
	public static function create( $class, $message, $context='' )
	{
		return new UI_messages_message( $class, $message, $context );
	}

	/**
	 * @param string $message
	 * @param string $context
	 *
	 * @return UI_messages_message
	 */
	public static function createSuccess( $message, $context='' )
	{
		return static::create( static::C_SUCCESS, $message, $context );
	}

	/**
	 * @param string $message
	 * @param string $context
	 */
	public static function info( $message, $context='' )
	{
		static::set( static::C_INFO, $message, $context );
	}

	/**
	 * @param string $message
	 * @param string $context
	 *
	 * @return UI_messages_message
	 */
	public static function createInfo( $message, $context='' )
	{
		return static::create( static::C_INFO, $message, $context );
	}

	/**
	 * @param string $message
	 * @param string $context
	 */
	public static function warning( $message, $context='' )
	{
		static::set( static::C_WARNING, $message, $context );
	}

	/**
	 * @param string $message
	 * @param string $context
	 *
	 * @return UI_messages_message
	 */
	public static function createWarning( $message, $context='' )
	{
		return static::create( static::C_WARNING, $message, $context );
	}

	/**
	 * @param string $message
	 * @param string $context
	 */
	public static function danger( $message, $context='' )
	{
		static::set( static::C_DANGER, $message, $context );
	}

	/**
	 * @param string $message
	 * @param string $context
	 *
	 * @return UI_messages_message
	 */
	public static function createDanger( $message, $context='' )
	{
		return static::create( static::C_DANGER, $message, $context );
	}

	/**
	 * @param string|null $context
	 *
	 * @return UI_messages_message[]
	 */
	public static function get( $context=null )
	{
		$messages = [];

		$not_relevant_messages = [];

		$s = static::getSession();

		if( $s->getValueExists( 'msg' ) ) {

			if($context!==null) {
				foreach( $s->getValue( 'msg' ) as $msg ) {
					/**
					 * @var UI_messages_message $msg
					 */
					if($msg->getContext()==$context) {
						$messages[] = $msg;
					} else {
						$not_relevant_messages[] = $msg;
					}
				}

			} else {
				foreach( $s->getValue( 'msg' ) as $msg ) {
					$messages[] = $msg;
				}
			}
		}

		$s->setValue( 'msg', $not_relevant_messages );

		return $messages;
	}
}