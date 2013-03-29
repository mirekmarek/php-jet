<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Fetch
 */
namespace Jet;

class DataModel_Fetch_Object_Assoc extends DataModel_Fetch_Object_Abstract implements Data_Paginator_DataSource_Interface,\ArrayAccess, \Iterator, \Countable  {
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
	 * @return bool
	 */
	public function offsetExists( $offset  ) {
		$this->_fetchIDs();
		return array_key_exists($offset, $this->data);
	}
	/**
	 * @see ArrayAccess
	 * @param int $offset
	 *
	 * @return DataModel
	 */
	public function offsetGet( $offset ) {
		$this->_fetchIDs();
		return $this->_get($offset);
	}

	/**
	 * Do nothing - DataModel_FetchAll is readonly
	 *
	 * @see ArrayAccess
	 * @param int $offset
	 * @param void $value
	 */
	public function offsetSet( $offset , $value ) {}

	/**
	 * @see ArrayAccess
	 * @param int $offset
	 */
	public function offsetUnset( $offset )	{
		$this->_fetchIDs();
		unset( $this->data[$offset] );
		foreach($this->IDs as $i=>$ID) {
			if((string)$ID==$offset) {
				unset($this->IDs[$i]);
				break;
			}
		}
	}

	/**
	 * @see Iterator
	 *
	 * @return DataModel
	 */
	public function current() {
		$this->_fetchIDs();

		return $this->_get($this->IDs[$this->iterator_position]);
	}
	/**
	 * @see Iterator
	 * @return string
	 */
	public function key() {
		$this->_fetchIDs();
		return (string)$this->IDs[$this->iterator_position];
	}
	/**
	 * @see Iterator
	 */
	public function next() {
		$this->_fetchIDs();
		++$this->iterator_position;
	}
	/**
	 * @see Iterator
	 */
	public function rewind() {
		$this->_fetchIDs();
		$this->iterator_position=0;
	}
	/**
	 * @see Iterator
	 * @return bool
	 */
	public function valid()	{
		$this->_fetchIDs();
		return isset( $this->IDs[$this->iterator_position] );
	}

}