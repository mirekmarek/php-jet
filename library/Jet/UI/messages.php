<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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

	/**
	 * @var UI_messages_message[]|null
	 */
	protected static array|null $messages = null;

	/**
	 * @var Session|null
	 */
	protected static Session|null $session = null;
	
	/**
	 * @param string $class
	 * @param string $message
	 * @param string $context
	 */
	public static function set( string $class,
	                            string $message,
	                            string $context = '' ): void
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
	 * @param string|null $context
	 *
	 * @return UI_messages_message[]
	 */
	public static function get( string|null $context = null ): array
	{
		$messages = [];
		
		$not_relevant_messages = [];
		
		$s = static::getSession();
		
		if( $s->getValueExists( 'msg' ) ) {
			
			if( $context !== null ) {
				foreach( $s->getValue( 'msg' ) as $msg ) {
					/**
					 * @var UI_messages_message $msg
					 */
					
					if( $msg->getContext() == $context ) {
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
	

	/**
	 * @return Session
	 */
	protected static function getSession(): Session
	{
		if( static::$session === null ) {
			static::$session = new Session( SysConf_Jet_UI::getMessageSessionNamespace() );
		}

		return static::$session;
	}

	/**
	 * @param string $class
	 * @param string $message
	 * @param string $context =''
	 *
	 * @return UI_messages_message
	 */
	public static function create( string $class,
	                               string $message,
	                               string $context = '' ): UI_messages_message
	{
		return new UI_messages_message( $class, $message, $context );
	}
	
	/**
	 * @param string $message
	 * @param string $context
	 */
	public static function success( string $message, string $context = '' ): void
	{
		static::set( static::C_SUCCESS, $message, $context );
	}

	/**
	 * @param string $message
	 *
	 * @return UI_messages_message
	 */
	public static function createSuccess( string $message ): UI_messages_message
	{
		return static::create( static::C_SUCCESS, $message );
	}

	/**
	 * @param string $message
	 * @param string $context
	 */
	public static function info( string $message, string $context = '' ): void
	{
		static::set( static::C_INFO, $message, $context );
	}

	/**
	 * @param string $message
	 *
	 * @return UI_messages_message
	 */
	public static function createInfo( string $message ): UI_messages_message
	{
		return static::create( static::C_INFO, $message );
	}

	/**
	 * @param string $message
	 * @param string $context
	 */
	public static function warning( string $message, string $context = '' )
	{
		static::set( static::C_WARNING, $message, $context );
	}

	/**
	 * @param string $message
	 *
	 * @return UI_messages_message
	 */
	public static function createWarning( string $message ): UI_messages_message
	{
		return static::create( static::C_INFO, $message );
	}

	/**
	 * @param string $message
	 * @param string $context
	 */
	public static function danger( string $message, string $context = '' )
	{
		static::set( static::C_DANGER, $message, $context );
	}

	/**
	 * @param string $message
	 *
	 * @return UI_messages_message
	 */
	public static function createDanger( string $message ): UI_messages_message
	{
		return static::create( static::C_DANGER, $message );
	}

}