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
class DataModel_Related_1toN_Iterator extends BaseObject implements DataModel_Related_1toN_Iterator_Interface, BaseObject_Interface_Serializable_JSON
{

	/**
	 * @var ?DataModel_Definition_Model_Related_1toN
	 */
	protected ?DataModel_Definition_Model_Related_1toN $_item_definition = null;

	/**
	 * @var DataModel_Related_1toN[]
	 */
	protected array $items = [];

	/**
	 * @var ?DataModel_Related_1toN
	 */
	protected ?DataModel_Related_1toN $_empty_item_instance = null;

	/**
	 * @var DataModel_Related_1toN[]
	 */
	private array $_deleted_items = [];


	/**
	 * @param DataModel_Definition_Model_Related_1toN $item_definition
	 * @param DataModel_Related_1toN[]                $items
	 */
	public function __construct( DataModel_Definition_Model_Related_1toN $item_definition, array $items = [] )
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
	 * @return DataModel_Related_1toN[]
	 */
	public function getItems() : array
	{
		return $this->items;
	}

	/**
	 * @param callable $sort_callback
	 */
	public function sortItems( callable $sort_callback) : void
	{
		uasort( $this->items, $sort_callback );
	}


	/**
	 * @param DataModel_IDController $parent_id
	 */
	public function actualizeParentId( DataModel_IDController $parent_id ) : void
	{
		foreach( $this->items as $item ) {
			$item->actualizeParentId( $parent_id );
		}
	}

	/**
	 * @param DataModel_IDController $main_id
	 */
	public function actualizeMainId( DataModel_IDController $main_id ) : void
	{
		foreach( $this->items as $item ) {
			$item->actualizeMainId( $main_id );
		}
	}

	/**
	 *
	 * @param DataModel_Definition_Property $parent_property_definition
	 * @param ?DataModel_PropertyFilter $property_filter
	 *
	 * @return Form_Field[]
	 *
	 */
	public function getRelatedFormFields( DataModel_Definition_Property $parent_property_definition,
	                                      ?DataModel_PropertyFilter $property_filter = null ) : array
	{

		$fields = [];
		if( !$this->items ) {
			return $fields;
		}

		$parent_field_name = $parent_property_definition->getName();

		foreach( $this->items as $key => $related_instance ) {

			/**
			 * @var DataModel_Related_1toN $related_instance
			 */
			$related_form_fields = $related_instance->getRelatedFormFields(
				$parent_property_definition, $property_filter
			);

			foreach( $related_form_fields as $field ) {

				$field_name = $field->getName();

				if( $field_name[0]=='/' ) {
					$field->setName( '/'.$parent_field_name.'/'.$key.$field_name );
				} else {
					$field->setName( '/'.$parent_field_name.'/'.$key.'/'.$field_name );
				}


				$fields[] = $field;
			}

		}

		return $fields;
	}

	/**
	 *
	 * @throws Exception
	 * @throws DataModel_Exception
	 */
	public function save() : void
	{

		foreach( $this->_deleted_items as $item ) {
			/**
			 * @var DataModel_Related_1toN $item
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
	 */
	public function removeAllItems() : void
	{
		if( $this->items ) {
			$this->_deleted_items = $this->items;
		}
		$this->items = [];
	}


	/**
	 *
	 * @throws DataModel_Exception
	 */
	public function delete() : void
	{
		/**
		 * @var DataModel_Related_1toN[] $this_deleted_items
		 */

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
	 * @return string
	 */
	public function toJSON() : string
	{
		$data = $this->jsonSerialize();

		return json_encode( $data );
	}

	/**
	 * @return array
	 */
	public function jsonSerialize() : array
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
	 * @see \Countable
	 *
	 * @return int
	 */
	public function count() : int
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
	public function offsetExists( mixed $offset ) : bool
	{
		return isset( $this->items[$offset] );
	}

	/**
	 * @see \ArrayAccess
	 *
	 * @param mixed $offset
	 *
	 * @return DataModel_Related_1toN
	 */
	public function offsetGet( mixed $offset ) : DataModel_Related_1toN
	{
		return $this->items[$offset];
	}

	/**
	 *
	 * @see \ArrayAccess
	 *
	 * @param mixed $offset
	 * @param mixed $value
	 *
	 * @throws DataModel_Exception
	 */
	public function offsetSet( mixed $offset,  mixed $value ) : void
	{


		$valid_class = $this->_item_definition->getClassName();

		if( !( $value instanceof $valid_class ) ) {
			throw new DataModel_Exception(
				'New item must be instance of \''.$valid_class.'\' class. \''.get_class( $value ).'\' given.',
				DataModel_Exception::CODE_INVALID_CLASS
			);
		}

		if( is_null( $offset ) ) {
			$offset = $value->getArrayKeyValue();
			if( is_object( $offset ) ) {
				$offset = (string)$offset;
			}
		}

		if( !$offset ) {
			$this->items[] = $value;
		} else {
			$this->items[$offset] = $value;
		}
	}

	/**
	 * @see \ArrayAccess
	 *
	 * @param mixed $offset
	 */
	public function offsetUnset( mixed $offset ) : void
	{
		$this->_deleted_items[] = $this->items[$offset];

		unset( $this->items[$offset] );
	}

	/**
	 * @see \Iterator
	 *
	 * @return DataModel|null
	 */
	public function current() : DataModel|null
	{
		if( $this->items===null ) {
			return null;
		}

		return current( $this->items );
	}

	/**
	 * @see \Iterator
	 *
	 * @return string|null
	 */
	public function key() : string|null
	{
		if( $this->items===null ) {
			return null;
		}

		return key( $this->items );
	}

	/**
	 * @see \Iterator
	 *
	 * @return DataModel|bool|null
	 */
	public function next() : DataModel|bool|null
	{
		if( $this->items===null ) {
			return null;
		}

		return next( $this->items );
	}

	/**
	 * @see \Iterator
	 */
	public function rewind() : void
	{
		if( $this->items!==null ) {
			reset( $this->items );
		}
	}

	/**
	 * @see \Iterator
	 * @return bool
	 */
	public function valid() : bool
	{
		if( $this->items===null ) {
			return false;
		}

		return key( $this->items )!==null;
	}

}