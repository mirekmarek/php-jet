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
class DataModel_Related_MtoN_Iterator extends BaseObject implements DataModel_Related_MtoN_Iterator_Interface
{

	/**
	 * @var DataModel_Definition_Model_Related_MtoN
	 */
	protected $_item_definition;

	/**
	 * @var DataModel_Related_MtoN[]
	 */
	protected $items = [];

	/**
	 * @var DataModel_PropertyFilter|null
	 */
	private $_load_filter;

	/**
	 * @var DataModel_Related_MtoN[]
	 */
	private $_deleted_items = [];

	/**
	 * @param DataModel_Definition_Model_Related_MtoN $item_definition
	 * @param DataModel_Related_MtoN[]                $items
	 */
	public function __construct( DataModel_Definition_Model_Related_MtoN $item_definition, array $items = [] )
	{

		$this->_item_definition = $item_definition;

		$this->items = [];
		$this->_deleted_items = [];

		foreach( $items as $item ) {
			$key = $item->getArrayKeyValue();
			if($key===null) {
				$this->items[] = $item;
			} else {
				$this->items[$key] = $item;
			}
		}
	}


	/**
	 * @param DataModel_IDController $parent_id
	 */
	public function actualizeParentId( DataModel_IDController $parent_id )
	{
		foreach( $this->items as $item ) {
			$item->actualizeParentId( $parent_id );
		}
	}

	/**
	 * @param DataModel_IDController $main_id
	 */
	public function actualizeMainId( DataModel_IDController $main_id )
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
	public function save()
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
	public function delete()
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
	public function removeAllItems()
	{
		if( $this->items ) {
			$this->_deleted_items = $this->items;
		}
		$this->items = [];
	}

	/**
	 * @param DataModel[] $N_instances
	 *$this->_items
	 *
	 * @throws DataModel_Exception
	 */
	public function setItems( $N_instances )
	{

		$add_items = [];

		foreach( $this->items as $i => $item ) {
			$exists = false;
			foreach( $N_instances as $N_instance ) {
				if( $item->getNId()->toString()==$N_instance->getIDController()->toString() ) {
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
				if( $item->getNId()->toString()==$N_instance->getIDController()->toString() ) {
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
	 * @see \ArrayAccess
	 *
	 * @param mixed $offset
	 */
	public function offsetUnset( $offset )
	{

		$this->_deleted_items[] = $this->items[$offset];

		unset( $this->items[$offset] );
	}

	/**
	 * @param DataModel[] $N_instances
	 *
	 * @throws DataModel_Exception
	 */
	public function addItems( $N_instances )
	{
		foreach( $N_instances as $N_instance ) {
			$this->offsetSet( null, $N_instance );
		}
	}

	/**
	 *
	 * @see ArrayAccess
	 *
	 * @param int       $offset
	 * @param DataModel $value
	 *
	 * @throws DataModel_Exception
	 */
	public function offsetSet( $offset, $value )
	{

		$valid_class_name = $this->_item_definition->getNModelClassName();

		if( !is_object( $value ) ) {
			throw new DataModel_Exception(
				'Value instance must be instance of \''.$valid_class_name.'\'.'
			);

		}

		if( !( $value instanceof $valid_class_name ) ) {
			throw new DataModel_Exception(
				'Value instance must be instance of \''.$valid_class_name.'\'. \''.get_class( $value ).'\' given '
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
	public function getIds()
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
	public function getItems()
	{
		return $this->items;
	}


	/**
	 * @param callable $sort_callback
	 */
	public function sortItems( callable $sort_callback)
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
	public function getRelatedFormFields( DataModel_Definition_Property $parent_property_definition, DataModel_PropertyFilter $property_filter = null )
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
	public function toJSON()
	{
		$data = $this->jsonSerialize();

		return json_encode( $data );
	}

	/**
	 * @return array
	 */
	public function jsonSerialize()
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
	public function getRelationItem( $key )
	{
		if( !isset( $this->items[$key] ) ) {
			return null;
		}

		return $this->items[$key];
	}

	/**
	 * @see \Countable
	 *
	 * @return int
	 */
	public function count()
	{
		return count( $this->items );
	}

	/**
	 * @see \ArrayAccess
	 *
	 * @param mixed $offset
	 *
	 * @return bool
	 */
	public function offsetExists( $offset )
	{
		return isset( $this->items[$offset] );
	}

	/**
	 * @see \ArrayAccess
	 *
	 * @param mixed $offset
	 *
	 * @return DataModel|DataModel_Related_MtoN
	 */
	public function offsetGet( $offset )
	{
		return $this->_getCurrentItem( $this->items[$offset] );
	}

	/**
	 * @param DataModel_Related_MtoN $item
	 *
	 * @return DataModel
	 */
	protected function _getCurrentItem( DataModel_Related_MtoN $item )
	{
		return $item->getNInstance( $this->_load_filter );
	}

	/**
	 * @see \Iterator
	 *
	 * @return DataModel|DataModel_Related_MtoN
	 */
	public function current()
	{
		if( $this->items===null ) {
			return null;
		}
		$current = current( $this->items );

		return $this->_getCurrentItem( $current );
	}

	/**
	 * @see \Iterator
	 *
	 * @return string
	 */
	public function key()
	{
		if( $this->items===null ) {
			return null;
		}

		return key( $this->items );
	}

	/**
	 * @see \Iterator
	 */
	public function next()
	{
		if( $this->items===null ) {
			return null;
		}

		return next( $this->items );
	}

	/**
	 * @see \Iterator
	 */
	public function rewind()
	{
		if( $this->items!==null ) {
			reset( $this->items );
		}
	}

	/**
	 * @see \Iterator
	 * @return bool
	 */
	public function valid()
	{
		if( $this->items===null ) {
			return false;
		}

		return key( $this->items )!==null;
	}

}