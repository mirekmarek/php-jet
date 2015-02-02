<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Fetch
 */
namespace Jet;

class DataModel_Fetch_Object_IDs extends DataModel_Fetch_Object_Abstract implements \ArrayAccess, \Iterator, \Countable  {
	/**
	 * @see Countable
	 *
	 * @return int
	 */
	public function count() {
		return $this->getCount();
	}

	/**
	 * @see ArrayAccess
	 * @param int $offset
	 *
	 * @return bool
	 */
	public function offsetExists( $offset  ) {
		$this->_fetch();
		return isset($this->IDs[(int)$offset]);
	}
	/**
	 * @see ArrayAccess
	 * @param int $offset
	 *
	 * @return DataModel_ID_Abstract
	 */
	public function offsetGet( $offset ) {
		$this->_fetch();
		return $this->IDs[(int)$offset];
	}

	/**
	 * Do nothing - DataModel_FetchAll is readonly
	 *
	 * @see ArrayAccess
	 * @param int $offset
	 * @param mixed $value
	 */
	public function offsetSet( $offset , $value ) {}
	
	/**
	 * @see ArrayAccess
	 * @param int $offset
	 */
	public function offsetUnset( $offset )	{
		$this->_fetch();
		unset( $this->IDs[(int)$offset] );
	}

	/**
	 * @see Iterator
	 *
	 * @return DataModel_ID_Abstract
	 */
	public function current() {
		$this->_fetch();
		return $this->IDs[$this->iterator_position];
	}
	/**
	 * @see Iterator
	 *
	 * @return int
	 */
	public function key() {
		$this->_fetch();
		return $this->iterator_position;
	}
	/**
	 * @see Iterator
	 */
	public function next() {
		$this->_fetch();
		++$this->iterator_position;
	}
	/**
	 * @see Iterator
	 */
	public function rewind() {
		$this->_fetch();
		$this->iterator_position=0;
	}
	/**
	 * @see Iterator
	 *
	 * @return bool
	 */
	public function valid()	{
		$this->_fetch();
		return isset( $this->IDs[$this->iterator_position] );
	}

	/**
	 *
	 * @return array
	 */
	public function toArray() {
		$this->_fetch();

		$result = array();

		foreach( $this->IDs as $ID ) {
			$result[] = $ID->toString();
		}

		return $result;

	}
}