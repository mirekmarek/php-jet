<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Http
 * @subpackage Http_Request
 */
namespace Jet;

class Http_Request_Data_Hoax implements \ArrayAccess, \Iterator, \Countable {

	/**
	 * @throws Http_Request_Exception
	 */
	protected function hoax(){
		throw new Http_Request_Exception(
			'Direct access to PHP request data ($_GET, $_POST and $_REQUEST) forbidden.',
			Http_Request_Exception::CODE_REQUEST_DATA_HOAX
		);
	}

	/**
	 * @param string $name
	 *
	 * @throws Http_Request_Exception
	 */
	public function __get($name) {
		$this->hoax();
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 *
	 * @throws Http_Request_Exception
	 */
	public function __set($name, $value) {
		$this->hoax();
	}

	/**
	 * @param string $name
	 *
	 * @throws Http_Request_Exception
	 */
	public function __unset($name) {
		$this->hoax();
	}

	/**
	 * @param string $name
	 *
	 * @throws Http_Request_Exception
	 */
	public function __isset($name) {
		$this->hoax();
	}

	/**
	 * @throws Http_Request_Exception
	 */
	public function rewind() {
		$this->hoax();
	}

	/**
	 * @throws Http_Request_Exception
	 */
	public function current() {
		$this->hoax();
	}

	/**
	 * @throws Http_Request_Exception
	 */
	public function key() {
		$this->hoax();
	}

	/**
	 * @throws Http_Request_Exception
	 */
	public function next() {
		$this->hoax();
	}

	/**
	 * @throws Http_Request_Exception
	 */
	public function valid() {
		$this->hoax();
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 *
	 * @throws Http_Request_Exception
	 */
	public function offsetSet($offset, $value) {
		$this->hoax();
	}

	/**
	 * @param mixed $offset
	 *
	 * @return bool
	 *
	 * @throws Http_Request_Exception
	 */
	public function offsetExists($offset) {
		$this->hoax();
	}

	/**
	 * @param mixed $offset
	 *
	 * @throws Http_Request_Exception
	 */
	public function offsetUnset($offset) {
		$this->hoax();
	}

	/**
	 * @param mixed $offset
	 * @return mixed
	 *
	 * @throws Http_Request_Exception
	 */
	public function offsetGet($offset) {
		$this->hoax();
	}

	/**
	 * @throws Http_Request_Exception
	 */
	public function count(){
		$this->hoax();
	}
}