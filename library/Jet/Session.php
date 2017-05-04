<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Session
 * @package Jet
 */
class Session extends BaseObject {

	/**
	 * @var bool
	 */
	protected static $session_started = false;

	/**
	 * @var string
	 */
	protected $namespace;

	/**
	 * @var callable
	 */
	protected static $session_validator;

	/**
	 * @return callable
	 */
	public static function getSessionValidator()
	{
		return self::$session_validator;
	}

	/**
	 * @param callable $session_validator
	 */
	public static function setSessionValidator( callable $session_validator )
	{
		self::$session_validator = $session_validator;
	}

	/**
	 * @param string $namespace
	 */
	public function __construct( $namespace ) {

		$this->namespace = (string)$namespace;

		$this->sessionStart();
	}

	/**
	 *
	 */
	protected function sessionStart() {
		if(!static::$session_started) {
			/** @noinspection PhpUsageOfSilenceOperatorInspection */
			@session_start();
			static::$session_started = true;
			if(static::$session_validator) {
				$validator = static::$session_validator;
				if(!$validator()) {
					session_reset();
					session_regenerate_id();
				}
			}
		}

		if(!isset($_SESSION[$this->namespace])) {
			$_SESSION[$this->namespace] = [];
		}
	}

	/**
	 * @return null|string
	 */
	public function getNamespace() {
		return $this->namespace;
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public function setValue( $key, $value ) {
		$this->checkKey($key);

		$_SESSION[$this->namespace][$key] = $value;
	}

	/**
	 *
	 * @param string $key
	 */
	public function unsetValue( $key ) {
		$this->checkKey($key);

		if( $this->getValueExists($key) ) {
			unset($_SESSION[$this->namespace][$key]);
		}
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function getValueExists( $key ) {
		$this->checkKey($key);

		return array_key_exists($key, $_SESSION[$this->namespace]);
	}

	/**
	 * @param string $key
	 * @param mixed $default_value (optional)
	 * @return mixed
	 */
	public function getValue( $key, $default_value=null ) {
		$this->checkKey($key);

		if( $this->getValueExists($key) ) {
			return $_SESSION[$this->namespace][$key];
		}

		return $default_value;
	}

	/**
	 * @param string $key
	 * @throws Session_Exception
	 */
	protected function checkKey( &$key ) {
		$key = (string)$key;

		if($key=='') {
			throw new Session_Exception(
				'The key must be a non-empty string',
				Session_Exception::CODE_INVALID_KEY
			);
		}
	}

	/**
	 * @return string
	 */
	public function getSessionId() {
		return session_id();
	}


	/**
	 *
	 */
	public static function destroy() {
		session_destroy();
		$_SESSION = [];
	}
}