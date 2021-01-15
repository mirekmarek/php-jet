<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

/**
 *
 */
class DataModel_Related_MtoN_Iterator extends BaseObject implements DataModel_Related_MtoN_Iterator_Interface
{

	/**
	 * @var ?DataModel_Definition_Model_Related_MtoN
	 */
	protected ?DataModel_Definition_Model_Related_MtoN $_item_definition = null;

	/**
	 * @var DataModel_Related_MtoN[]
	 */
	protected array $items = [];

	/**
	 * @var DataModel_PropertyFilter|null
	 */
	private DataModel_PropertyFilter|null $_load_filter = null;

	/**
	 * @var DataModel_Related_MtoN[]
	 */
	private array $_deleted_items = [];

	/**
	 * @param DataModel_Definition_Model_Related_MtoN $item_definition
	 * @param DataModel_Related_MtoN[] $items
	 */
	public function __construct( DataModel_Definition_Model_Related_MtoN $item_definition, array $items = [] )
	{

		$this->_item_definition = $item_definition;

		$this->items = [];
		$this->_deleted_items = [];

		foreach( $items as $item ) {
			$key = $item->getArrayKeyValue();
			if( $key === null ) {
				$this->items[] = $item;
			} else {
				$this->items[$key] = $item;
			}
		}
	}


	/**
	 * @param DataModel_IDController $parent_id
	 */
	public function actualizeParentId( DataModel_IDController $parent_id ): void
	{
		foreach( $this->items as $item ) {
			$item->actualizeParentId( $parent_id );
		}
	}

	/**
	 * @param DataModel_IDController $main_id
	 */
	public function actualizeMainId( DataModel_IDController $main_id ): void
	{

		foreach( $this->items as $item ) {
			$item->actualizeMainId( $main_id );
		}
	}

	/**
	 *
	 * @throws Exception
	 * @throws DataModel_Exception
	 */
	public function save(): void
	{


		foreach( $this->_deleted_items as $item ) {
			/**
			 * @var DataModel_Related_MtoN $item
			 */
			if( $item->getIsSaved() ) {
				$item->delete();
			}
		}

		if( !$this->items ) {
			return;
		}

		foreach( $this->items as $item ) {
			$item->save();
		}

	}


	/**
	 *
	 * @throws DataModel_Exception
	 */
	public function delete(): void
	{

		foreach( $this->_deleted_items as $item ) {
			$item->delete();
		}

		if( !$this->items ) {
			return;
		}

		foreach( $this->items as $d ) {
			if( $d->getIsSaved() ) {
				$d->delete();
			}
		}
	}


	/**
	 *
	 */
	public function removeAllItems(): void
	{
		if( $this->items ) {
			$this->_deleted_items = $this->items;
		}
		$this->items = [];
	}

	/**
	 * @param DataModel[] $N_instances
	 *
	 * @throws DataModel_Exception
	 */
	public function setItems( array $N_instances ): void
	{

		$add_items = [];

		foreach( $this->items as $i => $item ) {
			$exists = false;
			foreach( $N_instances as $N_instance ) {
				if( $item->getNId()->toString() == $N_instance->getIDController()->toString() ) {
					$exists = true;
					break;
				}
			}

			if( !$exists ) {
				$this->offsetUnset( $i );
			}

		}

		foreach( $N_instances as $N_instance ) {
			$exists = false;
			foreach( $this->items as $item ) {
				if( $item->getNId()->toString() == $N_instance->getIDController()->toString() ) {
					$exists = true;
					break;
				}
			}

			if( !$exists ) {
				$add_items[] = $N_instance;
			}
		}

		if( $add_items ) {
			$this->addItems( $add_items );
		}

	}

	/**
	 * @param mixed $offset
	 * @see \ArrayAccess
	 *
	 */
	public function offsetUnset( mixed $offset ): void
	{

		$this->_deleted_items[] = $this->items[$offset];

		unset( $this->items[$offset] );
	}

	/**
	 * @param DataModel[] $N_instances
	 *
	 * @throws DataModel_Exception
	 */
	public function addItems( array $N_instances ): void
	{
		foreach( $N_instances as $N_instance ) {
			$this->offsetSet( null, $N_instance );
		}
	}

	/**
	 *
	 * @param int $offset
	 * @param DataModel $value
	 *
	 * @throws DataModel_Exception
	 * @see ArrayAccess
	 *
	 */
	public function offsetSet( mixed $offset, mixed $value ): void
	{

		$valid_class_name = $this->_item_definition->getNModelClassName();

		if( !is_object( $value ) ) {
			throw new DataModel_Exception(
				'Value instance must be instance of \'' . $valid_class_name . '\'.'
			);

		}

		if( !($value instanceof $valid_class_name) ) {
			throw new DataModel_Exception(
				'Value instance must be instance of \'' . $valid_class_name . '\'. \'' . get_class( $value ) . '\' given '
			);
		}

		if( !$value->getIsSaved() ) {
			throw new DataModel_Exception(
				'Object instance must be saved '
			);
		}

		$class_name = $this->_item_definition->getClassName();
		/**
		 * @var DataModel_Related_MtoN $item
		 */
		$item = new $class_name();
		$item->setIsNew();
		$item->setNInstance( $value );


		if( is_null( $offset ) ) {
			/**
			 * @var DataModel_Related_1toN $value
			 */
			$offset = $item->getArrayKeyValue();
			if( is_object( $offset ) ) {
				$offset = (string)$offset;
			}
		}

		if( !$offset ) {
			$this->items[] = $item;
		} else {
			$this->items[$offset] = $item;
		}

	}

	/**
	 * @return DataModel_IDController[]
	 */
	public function getIds(): array
	{
		$ids = [];

		foreach( $this->items as $item ) {
			$ids[] = $item->getNId();
		}

		return $ids;
	}

	/**
	 * @return DataModel_Related_MtoN[]
	 */
	public function getItems(): array
	{
		return $this->items;
	}


	/**
	 * @param callable $sort_callback
	 */
	public function sortItems( callable $sort_callback ): void
	{
		uasort( $this->items, $sort_callback );
	}

	/**
	 *
	 * @param DataModel_Definition_Property $parent_property_definition
	 * @param DataModel_PropertyFilter|null $property_filter
	 *
	 * @return Form_Field[]
	 */
	public function getRelatedFormFields( DataModel_Definition_Property $parent_property_definition,
	                                      DataModel_PropertyFilter $property_filter = null ): array
	{
		return [];
	}


//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------

	/**
	 * @return string
	 */
	public function toJSON(): string
	{
		$data = $this->jsonSerialize();

		return json_encode( $data );
	}

	/**
	 * @return array
	 */
	public function jsonSerialize(): array
	{

		$res = [];

		if( !$this->items ) {
			return $res;
		}

		foreach( $this->items as $k => $d ) {
			$res[$k] = $d->jsonSerialize();
		}

		return $res;

	}

	/**
	 * @param string $key
	 *
	 * @return DataModel_Related_MtoN|null
	 */
	public function getRelationItem( string $key ): DataModel_Related_MtoN|null
	{
		if( !isset( $this->items[$key] ) ) {
			return null;
		}

		return $this->items[$key];
	}

	/**
	 * @return int
	 * @see \Countable
	 *
	 */
	public function count(): int
	{
		return count( $this->items );
	}

	/**
	 * @param mixed $offset
	 *
	 * @return bool
	 * @see \ArrayAccess
	 *
	 */
	public function offsetExists( mixed $offset ): bool
	{
		return isset( $this->items[$offset] );
	}

	/**
	 * @param mixed $offset
	 *
	 * @return DataModel|DataModel_Related_MtoN
	 * @see \ArrayAccess
	 *
	 */
	public function offsetGet( mixed $offset ): DataModel|DataModel_Related_MtoN
	{
		return $this->_getCurrentItem( $this->items[$offset] );
	}

	/**
	 * @param DataModel_Related_MtoN $item
	 *
	 * @return DataModel|DataModel_Related_MtoN|null
	 */
	protected function _getCurrentItem( DataModel_Related_MtoN $item ): DataModel|DataModel_Related_MtoN|null
	{
		return $item->getNInstance( $this->_load_filter );
	}

	/**
	 * @return DataModel|DataModel_Related_MtoN|null
	 * @see \Iterator
	 *
	 */
	public function current(): DataModel|DataModel_Related_MtoN|null
	{
		if( $this->items === null ) {
			return null;
		}
		$current = current( $this->items );

		return $this->_getCurrentItem( $current );
	}

	/**
	 * @return string|null
	 * @see \Iterator
	 *
	 */
	public function key(): string|null
	{
		if( $this->items === null ) {
			return null;
		}

		return key( $this->items );
	}

	/**
	 * @see \Iterator
	 */
	public function next(): null|bool|DataModel|DataModel_Related_MtoN
	{
		if( $this->items === null ) {
			return null;
		}

		return next( $this->items );
	}

	/**
	 * @see \Iterator
	 */
	public function rewind(): void
	{
		if( $this->items !== null ) {
			reset( $this->items );
		}
	}

	/**
	 * @return bool
	 * @see \Iterator
	 */
	public function valid(): bool
	{
		if( $this->items === null ) {
			return false;
		}

		return key( $this->items ) !== null;
	}

}