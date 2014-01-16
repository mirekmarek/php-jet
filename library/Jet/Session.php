<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Session
 */

namespace Jet;

class Session extends Object {
	protected static $session_started = false;

	/**
	 * @var string
	 */
	protected $namespace;

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
			@session_start();
			static::$session_started = true;
		}

		if(!isset($_SESSION[$this->namespace])) {
			$_SESSION[$this->namespace] = array();
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
	public function getSessionID() {
		return session_id();
	}


	/**
	 *
	 */
	public static function destroy() {
		session_destroy();
		$_SESSION = array();
	}
}