<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Http_Request_Trap implements BaseObject_ArrayEmulator
{

	/**
	 * @throws Http_Request_Exception
	 */
	protected function trap()
	{
		throw new Http_Request_Exception(
			'Direct access to PHP request data ($_GET, $_POST and $_REQUEST) forbidden.',
			Http_Request_Exception::CODE_REQUEST_DATA_TRAP
		);
	}

	/**
	 * @param string $name
	 *
	 * @throws Http_Request_Exception
	 */
	public function __get( $name )
	{
		$this->trap();
	}

	/**
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @throws Http_Request_Exception
	 */
	public function __set( $name, $value )
	{
		$this->trap();
	}

	/**
	 * @param string $name
	 *
	 * @throws Http_Request_Exception
	 */
	public function __unset( $name )
	{
		$this->trap();
	}

	/**
	 * @param string $name
	 *
	 * @throws Http_Request_Exception
	 */
	public function __isset( $name )
	{
		$this->trap();
	}

	/**
	 * @throws Http_Request_Exception
	 */
	public function rewind()
	{
		$this->trap();
	}

	/**
	 * @throws Http_Request_Exception
	 */
	public function current()
	{
		$this->trap();
	}

	/**
	 * @throws Http_Request_Exception
	 */
	public function key()
	{
		$this->trap();
	}

	/**
	 * @throws Http_Request_Exception
	 */
	public function next()
	{
		$this->trap();
	}

	/**
	 * @throws Http_Request_Exception
	 */
	public function valid()
	{
		$this->trap();
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 *
	 * @throws Http_Request_Exception
	 */
	public function offsetSet( $offset, $value )
	{
		$this->trap();
	}

	/**
	 * @param mixed $offset
	 *
	 * @return bool
	 *
	 * @throws Http_Request_Exception
	 */
	public function offsetExists( $offset )
	{
		$this->trap();

		return false;
	}

	/**
	 * @param mixed $offset
	 *
	 * @throws Http_Request_Exception
	 */
	public function offsetUnset( $offset )
	{
		$this->trap();
	}

	/**
	 * @param mixed $offset
	 *
	 * @return mixed
	 *
	 * @throws Http_Request_Exception
	 */
	public function offsetGet( $offset )
	{
		$this->trap();

		return null;
	}

	/**
	 * @throws Http_Request_Exception
	 */
	public function count()
	{
		$this->trap();
	}
}