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
abstract class DataModel_Fetch extends BaseObject implements BaseObject_Interface_Serializable_JSON, Data_Paginator_DataSource
{

	/**
	 * @var DataModel_Definition_Model
	 */
	protected $data_model_definition;

	/**
	 * Query
	 *
	 * @var DataModel_Query
	 */
	protected $query;

	/**
	 * @var int
	 */
	protected $count = null;

	/**
	 * @var bool
	 */
	protected $pagination_enabled = false;


	/**
	 *
	 * @var DataModel_IDController
	 */
	protected $empty_id_instance;

	/**
	 *
	 * @var array
	 */
	protected $data;


	/**
	 *
	 * @param array|DataModel_Query $query
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
	public function setPagination( $limit, $offset )
	{
		$this->pagination_enabled = true;

		$this->query->setLimit( $limit, $offset );
	}

	/**
	 * @return DataModel_Query
	 */
	public function getQuery()
	{
		return $this->query;
	}

	/**
	 * Returns data count
	 *
	 * @return int
	 */
	public function getCount()
	{
		if( $this->count===null ) {
			$this->count = DataModel_Backend::get($this->data_model_definition)->getCount( $this->query );
		}

		return $this->count;
	}

	/**
	 *
	 */
	abstract protected function _fetch();


	/**
	 * @return string
	 */
	public function toJSON()
	{
		return json_encode( $this->jsonSerialize() );
	}

	/**
	 * @return array
	 */
	public function jsonSerialize()
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
	abstract public function toArray();

	/**
	 * @see Countable
	 *
	 * @return int
	 */
	public function count()
	{
		return $this->getCount();
	}

	/**
	 * @see ArrayAccess
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
	 * Do nothing - DataModel_FetchAll is readonly
	 *
	 * @see ArrayAccess
	 *
	 * @param int   $offset
	 * @param mixed $value
	 */
	public function offsetSet( $offset, $value )
	{
	}

	/**
	 * @see ArrayAccess
	 *
	 * @param int $offset
	 *
	 * @return DataModel
	 */
	public function offsetGet( $offset )
	{
		$this->_fetch();

		return $this->_get( $this->data[$offset] );
	}

	/**
	 * @param mixed $item
	 *
	 * @return DataModel|DataModel_IDController
	 */
	abstract protected function _get( $item );

	/**
	 * @see ArrayAccess
	 *
	 * @param int $offset
	 */
	public function offsetUnset( $offset )
	{
		$this->_fetch();
		unset( $this->data[$offset] );
	}

	/**
	 * @see Iterator
	 *
	 * @return DataModel
	 */
	public function current()
	{
		$this->_fetch();

		return $this->_get( current( $this->data ) );
	}

	/**
	 * @see Iterator
	 * @return string
	 */
	public function key()
	{
		$this->_fetch();

		return key( $this->data );
	}

	/**
	 * @see Iterator
	 */
	public function next()
	{
		$this->_fetch();
		next( $this->data );
	}

	/**
	 * @see Iterator
	 */
	public function rewind()
	{
		$this->_fetch();
		reset( $this->data );
	}

	/**
	 * @see Iterator
	 * @return bool
	 */
	public function valid()
	{
		$this->_fetch();

		return key( $this->data )!==null;
	}

}