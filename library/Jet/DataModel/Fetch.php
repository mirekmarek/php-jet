<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
abstract class DataModel_Fetch extends BaseObject implements BaseObject_Interface_Serializable_JSON, Data_Paginator_DataSource
{

	/**
	 * @var ?DataModel_Definition_Model
	 */
	protected ?DataModel_Definition_Model $data_model_definition = null;

	/**
	 *
	 * @var ?DataModel_Query
	 */
	protected ?DataModel_Query $query = null;

	/**
	 * @var int|null
	 */
	protected int|null $count = null;

	/**
	 * @var bool
	 */
	protected bool $pagination_enabled = false;


	/**
	 *
	 * @var ?DataModel_IDController
	 */
	protected ?DataModel_IDController $empty_id_instance = null;

	/**
	 *
	 * @var ?array
	 */
	protected ?array $data = null;


	/**
	 *
	 * @param DataModel_Query $query
	 *
	 */
	final public function __construct( DataModel_Query $query )
	{

		$this->data_model_definition = $query->getDataModelDefinition();

		$this->query = $query;

		$load_properties = [];

		foreach( $this->data_model_definition->getIdProperties() as $property_definition ) {
			$load_properties[] = $property_definition;
		}

		$this->query->setSelect( $load_properties );

		$this->empty_id_instance = $this->data_model_definition->getIDController();
	}

	/**
	 * @param int $limit
	 * @param int $offset
	 */
	public function setPagination( int $limit, int $offset ) : void
	{
		$this->pagination_enabled = true;

		$this->query->setLimit( $limit, $offset );
	}

	/**
	 * @return DataModel_Query
	 */
	public function getQuery() : DataModel_Query
	{
		return $this->query;
	}

	/**
	 * Returns data count
	 *
	 * @return int
	 */
	public function getCount() : int
	{
		if( $this->count===null ) {
			$this->count = DataModel_Backend::get($this->data_model_definition)->getCount( $this->query );
		}

		return $this->count;
	}

	/**
	 *
	 */
	abstract protected function _fetch() : void;


	/**
	 * @return string
	 */
	public function toJSON() : string
	{
		return json_encode( $this->jsonSerialize() );
	}

	/**
	 * @return array
	 */
	public function jsonSerialize() : array
	{
		$result = [];

		foreach( $this as $val ) {
			/**
			 * @var DataModel $val
			 */
			$result[] = $val->jsonSerialize();
		}

		return $result;
	}

	/**
	 * @return array
	 */
	abstract public function toArray() : array;

	/**
	 * @see Countable
	 *
	 * @return int
	 */
	public function count() : int
	{
		return $this->getCount();
	}

	/**
	 * @see ArrayAccess
	 *
	 * @param mixed $offset
	 *
	 * @return bool
	 */
	public function offsetExists( mixed $offset ) : bool
	{
		$this->_fetch();

		return array_key_exists( $offset, $this->data );
	}

	/**
	 * Do nothing - DataModel_FetchAll is readonly
	 *
	 * @see ArrayAccess
	 *
	 * @param mixed $offset
	 * @param mixed $value
	 */
	public function offsetSet( mixed $offset, mixed $value ) : void
	{
	}

	/**
	 * @see ArrayAccess
	 *
	 * @param mixed $offset
	 *
	 * @return DataModel
	 */
	public function offsetGet( mixed $offset ) : DataModel
	{
		$this->_fetch();

		return $this->_get( $this->data[$offset] );
	}

	/**
	 * @param mixed $item
	 *
	 * @return DataModel|DataModel_IDController
	 */
	abstract protected function _get( mixed $item ) : DataModel|DataModel_IDController;

	/**
	 * @see ArrayAccess
	 *
	 * @param int $offset
	 */
	public function offsetUnset( mixed $offset ) : void
	{
		$this->_fetch();
		unset( $this->data[$offset] );
	}

	/**
	 * @see Iterator
	 *
	 * @return DataModel
	 */
	public function current() : DataModel
	{
		$this->_fetch();

		return $this->_get( current( $this->data ) );
	}

	/**
	 * @see Iterator
	 * @return string
	 */
	public function key() : string
	{
		$this->_fetch();

		return key( $this->data );
	}

	/**
	 * @see Iterator
	 */
	public function next() : void
	{
		$this->_fetch();
		next( $this->data );
	}

	/**
	 * @see Iterator
	 */
	public function rewind() : void
	{
		$this->_fetch();
		reset( $this->data );
	}

	/**
	 * @see Iterator
	 * @return bool
	 */
	public function valid() : bool
	{
		$this->_fetch();

		return key( $this->data )!==null;
	}

}