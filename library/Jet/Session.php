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
class Session extends BaseObject
{

	/**
	 * @var bool
	 */
	protected static bool $session_started = false;
	/**
	 * @var callable
	 */
	protected static $session_validator;
	/**
	 * @var string
	 */
	protected string $namespace = '';

	/**
	 *
	 */
	protected function sessionStart(): void
	{
		if( !static::$session_started ) {
			if(session_status() !== PHP_SESSION_ACTIVE) {
				session_start();
			}

			static::$session_started = true;
			if( static::$session_validator ) {
				$validator = static::$session_validator;
				if( !$validator() ) {
					session_reset();
					session_regenerate_id();
				}
			}
		}

		if( !isset( $_SESSION[$this->namespace] ) ) {
			$_SESSION[$this->namespace] = [];
		}
	}

	/**
	 * @return callable
	 */
	public static function getSessionValidator(): callable
	{
		return static::$session_validator;
	}

	/**
	 * @param callable $session_validator
	 */
	public static function setSessionValidator( callable $session_validator ): void
	{
		static::$session_validator = $session_validator;
	}

	/**
	 *
	 */
	public static function regenerateId(): void
	{
		session_regenerate_id();
	}

	/**
	 * @return string
	 */
	public static function getSessionId(): string
	{
		return session_id();
	}


	/**
	 *
	 */
	public static function destroy(): void
	{
		session_destroy();
		$_SESSION = [];
	}

	/**
	 * @param string $namespace
	 */
	public function __construct( string $namespace )
	{

		$this->namespace = $namespace;

		$this->sessionStart();
	}

	/**
	 * @return null|string
	 */
	public function getNamespace(): null|string
	{
		return $this->namespace;
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public function setValue( string $key, mixed $value ): void
	{
		$this->checkKey( $key );

		$_SESSION[$this->namespace][$key] = $value;
	}

	/**
	 * @param string $key
	 *
	 * @throws Session_Exception
	 */
	protected function checkKey( string &$key ): void
	{
		if( $key == '' ) {
			throw new Session_Exception(
				'Key must be a non-empty string', Session_Exception::CODE_INVALID_KEY
			);
		}
	}

	/**
	 *
	 * @param string $key
	 */
	public function unsetValue( string $key ): void
	{
		$this->checkKey( $key );

		if( $this->getValueExists( $key ) ) {
			unset( $_SESSION[$this->namespace][$key] );
		}
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function getValueExists( string $key ): bool
	{
		$this->checkKey( $key );

		return array_key_exists( $key, $_SESSION[$this->namespace] );
	}

	/**
	 * @param string $key
	 * @param mixed $default_value (optional)
	 *
	 * @return mixed
	 */
	public function getValue( string $key, mixed $default_value = null ): mixed
	{
		$this->checkKey( $key );

		if( $this->getValueExists( $key ) ) {
			return $_SESSION[$this->namespace][$key];
		}

		return $default_value;
	}

	/**
	 *
	 */
	public function reset(): void
	{
		$_SESSION[$this->namespace] = [];
	}

}