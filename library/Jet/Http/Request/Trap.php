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
class Http_Request_Trap implements BaseObject_Interface_ArrayEmulator
{

	/**
	 * @throws Http_Request_Exception
	 */
	protected function trap(): void
	{
		throw new Http_Request_Exception(
			'Direct access to PHP request data ($_GET, $_POST and $_REQUEST) forbidden.',
			Http_Request_Exception::CODE_REQUEST_DATA_TRAP
		);
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 *
	 * @throws Http_Request_Exception
	 */
	public function __get( string $name ): mixed
	{
		$this->trap();

		return null;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 *
	 * @throws Http_Request_Exception
	 */
	public function __set( string $name, mixed $value ): void
	{
		$this->trap();
	}

	/**
	 * @param string $name
	 *
	 * @throws Http_Request_Exception
	 */
	public function __unset( string $name ): void
	{
		$this->trap();
	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 *
	 * @throws Http_Request_Exception
	 */
	public function __isset( string $name ): bool
	{
		$this->trap();

		return false;
	}

	/**
	 * @throws Http_Request_Exception
	 */
	public function rewind(): void
	{
		$this->trap();
	}

	/**
	 * @throws Http_Request_Exception
	 */
	public function current(): mixed
	{
		$this->trap();

		return null;
	}

	/**
	 * @throws Http_Request_Exception
	 */
	public function key(): string
	{
		$this->trap();

		return '';
	}

	/**
	 * @throws Http_Request_Exception
	 */
	public function next(): void
	{
		$this->trap();
	}

	/**
	 * @throws Http_Request_Exception
	 */
	public function valid(): bool
	{
		$this->trap();

		return false;
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 *
	 * @throws Http_Request_Exception
	 */
	public function offsetSet( mixed $offset, mixed $value ): void
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
	public function offsetExists( mixed $offset ): bool
	{
		$this->trap();

		return false;
	}

	/**
	 * @param mixed $offset
	 *
	 * @throws Http_Request_Exception
	 */
	public function offsetUnset( mixed $offset ): void
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
	public function offsetGet( mixed $offset ): mixed
	{
		$this->trap();

		return null;
	}

	/**
	 * @throws Http_Request_Exception
	 */
	public function count(): int
	{
		$this->trap();

		return 0;
	}
}