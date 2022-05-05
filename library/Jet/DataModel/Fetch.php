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
abstract class DataModel_Fetch extends BaseObject implements BaseObject_Interface_Serializable_JSON, Data_Paginator_DataSource, BaseObject_Interface_ArrayEmulator
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
	public function setPagination( int $limit, int $offset ): void
	{
		$this->pagination_enabled = true;

		$this->query->setLimit( $limit, $offset );
	}

	/**
	 * @return DataModel_Query
	 */
	public function getQuery(): DataModel_Query
	{
		return $this->query;
	}

	/**
	 * Returns data count
	 *
	 * @return int
	 */
	public function getCount(): int
	{
		if( $this->count === null ) {
			$this->count = DataModel_Backend::get( $this->data_model_definition )->getCount( $this->query );
		}

		return $this->count;
	}

	/**
	 *
	 */
	abstract protected function _fetch(): void;


	/**
	 * @return string
	 */
	public function toJSON(): string
	{
		return json_encode( $this->jsonSerialize() );
	}

	/**
	 * @return array
	 */
	public function jsonSerialize(): array
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
	abstract public function toArray(): array;

	/**
	 * @return int
	 * @see Countable
	 *
	 */
	public function count(): int
	{
		return $this->getCount();
	}
	

	/**
	 * @param mixed $offset
	 *
	 * @return bool
	 * @see ArrayAccess
	 *
	 */
	public function offsetExists( mixed $offset ): bool
	{
		$this->_fetch();

		return array_key_exists( $offset, $this->data );
	}

	/**
	 * Do nothing - DataModel_FetchAll is readonly
	 *
	 * @param mixed $offset
	 * @param mixed $value
	 * @see ArrayAccess
	 *
	 */
	public function offsetSet( mixed $offset, mixed $value ): void
	{
	}

	/**
	 * @param mixed $offset
	 *
	 * @return DataModel
	 * @see ArrayAccess
	 *
	 */
	public function offsetGet( mixed $offset ): DataModel
	{
		$this->_fetch();

		return $this->_get( $this->data[$offset] );
	}
	

	/**
	 * @param int $offset
	 * @see ArrayAccess
	 *
	 */
	public function offsetUnset( mixed $offset ): void
	{
		$this->_fetch();
		unset( $this->data[$offset] );
	}
	
	/**
	 * @param mixed $item
	 *
	 * @return DataModel|DataModel_Related_1toN|DataModel_Related_1to1|DataModel_IDController
	 */
	abstract protected function _get( mixed $item ): DataModel|DataModel_Related_1toN|DataModel_Related_1to1|DataModel_IDController;
	
	/**
	 * @return DataModel|DataModel_Related_1toN|DataModel_Related_1to1|DataModel_IDController
	 * @see Iterator
	 *
	 */
	public function current(): DataModel|DataModel_Related_1toN|DataModel_Related_1to1|DataModel_IDController
	{
		$this->_fetch();

		return $this->_get( current( $this->data ) );
	}

	/**
	 * @return string
	 * @see Iterator
	 */
	public function key(): string
	{
		$this->_fetch();

		return key( $this->data );
	}

	/**
	 * @see Iterator
	 */
	public function next(): void
	{
		$this->_fetch();
		next( $this->data );
	}

	/**
	 * @see Iterator
	 */
	public function rewind(): void
	{
		$this->_fetch();
		reset( $this->data );
	}

	/**
	 * @return bool
	 * @see Iterator
	 */
	public function valid(): bool
	{
		$this->_fetch();

		return key( $this->data ) !== null;
	}

}