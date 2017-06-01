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
abstract class DataModel_Fetch_Data extends DataModel_Fetch implements Data_Paginator_DataSource, BaseObject_ArrayEmulator
{
	/**
	 * @var string
	 */
	protected $backend_fetch_method = '';

	/**
	 * @var array
	 */
	protected $data = null;

	/**
	 * @var callable
	 */
	protected $array_walk_callback;


	/**
	 *
	 * @param string[]        $select_items
	 * @param DataModel_Query $query
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function __construct( array $select_items, DataModel_Query $query )
	{
		parent::__construct( $query );

		$this->query->setSelect( $select_items );
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		$this->_fetch();

		return $this->data;
	}

	/**
	 * Fetches data
	 *
	 */
	public function _fetch()
	{

		if( $this->data!==null ) {
			return;
		}

		$this->data = DataModel_Backend::get($this->data_model_definition)->{$this->backend_fetch_method}( $this->query );
	}

	/**
	 * @return array
	 */
	public function jsonSerialize()
	{
		$result = [];

		foreach( $this as $key => $val ) {
			foreach( $val as $k => $v ) {
				if( is_object( $v ) ) {
					$val[$k] = (string)$v;
				}
			}
			$result[$key] = $val;
		}

		return $result;
	}

	/**
	 * @return string
	 */
	public function toJSON()
	{
		$this->_fetch();

		return json_encode( $this->jsonSerialize() );
	}

//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------

	/**
	 * @see \Countable
	 *
	 * @return int
	 */
	public function count()
	{
		$this->_fetch();

		return count( $this->data );
	}

	/**
	 * @see \ArrayAccess
	 *
	 * @param int $offset
	 *
	 * @return bool
	 */
	public function offsetExists( $offset )
	{
		$this->_fetch();

		return array_key_exists( $offset, $this->data );
	}

	/**
	 * @see \ArrayAccess
	 *
	 * @param int $offset
	 *
	 * @return DataModel
	 */
	public function offsetGet( $offset )
	{
		$this->_fetch();

		return $this->data[$offset];
	}

	/**
	 *
	 * @see \ArrayAccess
	 *
	 * @param int   $offset
	 * @param mixed $value
	 */
	public function offsetSet( $offset, $value )
	{
		$this->data[$offset] = $value;
	}

	/**
	 * @see \ArrayAccess
	 *
	 * @param int $offset
	 */
	public function offsetUnset( $offset )
	{
		$this->_fetch();
		unset( $this->data[$offset] );
	}

	/**
	 * @see \Iterator
	 *
	 * @return DataModel
	 */
	public function current()
	{
		$this->_fetch();

		return current( $this->data );
	}

	/**
	 * @see \Iterator
	 * @return string
	 */
	public function key()
	{
		$this->_fetch();

		return key( $this->data );
	}

	/**
	 * @see \Iterator
	 */
	public function next()
	{
		$this->_fetch();

		return next( $this->data );
	}

	/**
	 * @see \Iterator
	 */
	public function rewind()
	{
		$this->_fetch();
		reset( $this->data );
	}

	/**
	 * @see \Iterator
	 * @return bool
	 */
	public function valid()
	{
		$this->_fetch();

		return key( $this->data )!==null;
	}

}