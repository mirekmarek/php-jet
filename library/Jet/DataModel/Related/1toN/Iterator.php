<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Related_1toN_Iterator
 * @package Jet
 */
class DataModel_Related_1toN_Iterator extends BaseObject implements DataModel_Related_1toN_Iterator_Interface {

	/**
	 * @var DataModel_Definition_Model_Related_1toN
	 */
	protected $_item_definition;

	/**
	 * @var DataModel_Related_1toN[]
	 */
	protected $items = [];

	/**
	 * @var DataModel_Related_1toN
	 */
	protected $_empty_item_instance;

	/**
	 * @var DataModel_Related_1toN[]
	 */
	private $_deleted_items = [];

	/**
	 * @var DataModel_PropertyFilter
	 */
	private $_load_filter;


	/**
	 * @param DataModel_Definition_Model_Related_1toN $item_definition
	 * @param DataModel_Related_1toN[] $items
	 */
	public function __construct( DataModel_Definition_Model_Related_1toN $item_definition, array $items=[] ) {

		$this->_item_definition = $item_definition;
		$this->items = [];
		$this->_deleted_items = [];

		foreach( $items as $item ) {
			$this->items[$item->getArrayKeyValue()] = $item;
		}
	}


	/**
	 * @return DataModel_Related_1toN
	 */
	protected function _getEmptyItemInstance() {

		if(!$this->_empty_item_instance) {

			$class_name = $this->_item_definition->getClassName();
			$this->_empty_item_instance = new $class_name();

			$this->_empty_item_instance->setLoadFilter($this->_load_filter);
		}

		return $this->_empty_item_instance;

	}


	/**
	 * @param DataModel_Id_Abstract $parent_id
	 */
	public function actualizeParentId(DataModel_Id_Abstract $parent_id ) {
		foreach( $this->items as $item ) {
			$item->actualizeParentId( $parent_id );
		}
	}

	/**
	 * @param DataModel_Id_Abstract $main_id
	 */
	public function actualizeMainId(DataModel_Id_Abstract $main_id ) {
		foreach( $this->items as $item ) {
			$item->actualizeMainId( $main_id );
		}
	}


	/**
	 *
	 * @param DataModel_Definition_Property_Abstract $parent_property_definition
	 * @param DataModel_PropertyFilter|null $property_filter
	 *
	 * @return Form_Field_Abstract[]
	 *
	 */
	public function getRelatedFormFields( DataModel_Definition_Property_Abstract $parent_property_definition, DataModel_PropertyFilter $property_filter=null )
	{

		$fields = [];
		if(!$this->items) {
			return $fields;
		}

		$parent_field_name = $parent_property_definition->getName();

		foreach($this->items as $key=>$related_instance) {

			/**
			 * @var DataModel_Related_1toN $related_instance
			 */
			$related_form_fields = $related_instance->getRelatedFormFields( $parent_property_definition, $property_filter );

			foreach($related_form_fields as $field) {

				$field_name = $field->getName();

				if($field_name[0]=='/') {
					$field->setName('/'.$parent_field_name.'/'.$key.$field_name );
				} else {
					$field->setName('/'.$parent_field_name.'/'.$key.'/'.$field_name );
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
	public function save() {

		foreach($this->_deleted_items as $item) {
			/**
			 * @var DataModel_Related_1toN $item
			 */
			if($item->getIsSaved()) {
				$item->delete();
			}
		}

		if( !$this->items ) {
			return;
		}

		foreach($this->items as $item) {
			$item->save();
		}

	}


	/**
	 *
	 * @throws DataModel_Exception
	 */
	public function delete() {
		/**
		 * @var DataModel_Related_1toN[] $this_deleted_items
		 */

		foreach($this->_deleted_items as $item) {
			$item->delete();
		}

		if( !$this->items ) {
			return;
		}

		foreach($this->items as $d) {
			if($d->getIsSaved()) {
				$d->delete();
			}
		}
	}


	/**
	 * @return array
	 */
	public function jsonSerialize() {

		$res = [];

		if(!$this->items) {
			return $res;
		}

		foreach($this->items as $k=>$d) {
			$res[$k] = $d->jsonSerialize();
		}

		return $res;

	}

	/**
	 * @return string
	 */
	public function toXML() {
		$res = [];
		if(is_array($this->items)) {
			foreach($this->items as $d) {
				/**
				 * @var DataModel_Related_1toN $d
				 */
				$res[] = $d->toXML();
			}
		}

		return implode(JET_EOL,$res);
	}

	/**
	 * @return string
	 */
	public function toJSON() {
		$data = $this->jsonSerialize();
		return json_encode($data);
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
	 *
	 */
	public function removeAllItems() {
		if($this->items) {
			$this->_deleted_items = $this->items;
		}
		$this->items = [];
	}

	/**
	 * @see \Countable
	 *
	 * @return int
	 */
	public function count() {
		return count($this->items);
	}

	/**
	 * @see \ArrayAccess
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists( $offset  ) {
		return isset($this->items[$offset]);
	}
	/**
	 * @see \ArrayAccess
	 * @param mixed $offset
	 *
	 * @return DataModel_Related_1toN
	 */
	public function offsetGet( $offset ) {
		return $this->items[$offset];
	}

	/**
	 *
	 * @see \ArrayAccess
	 *
	 * @param mixed $offset
	 * @param DataModel_Related_1toN $value
	 *
	 * @throws DataModel_Exception
	 */
	public function offsetSet( $offset , $value ) {


		$valid_class = $this->_item_definition->getClassName();

		if( !($value instanceof $valid_class) ) {
			throw new DataModel_Exception(
				'New item must be instance of \''.$valid_class.'\' class. \''.get_class($value).'\' given.',
				DataModel_Exception::CODE_INVALID_CLASS
			);
		}

		if(is_null($offset)) {
			/**
			 * @var DataModel_Related_1toN $value
			 */
			$offset = $value->getArrayKeyValue();
			if(is_object($offset)) {
				$offset = (string)$offset;
			}
		}

		if(!$offset) {
			$this->items[] = $value;
		} else {
			$this->items[$offset] = $value;
		}
	}

	/**
	 * @see \ArrayAccess
	 * @param mixed $offset
	 */
	public function offsetUnset( $offset )	{
		$this->_deleted_items[] = $this->items[$offset];

		unset( $this->items[$offset] );
	}

	/**
	 * @see \Iterator
	 *
	 * @return DataModel
	 */
	public function current() {
		if( $this->items===null ) {
			return null;
		}
		return current($this->items);
	}
	/**
	 * @see \Iterator
	 *
	 * @return string
	 */
	public function key() {
		if( $this->items===null ) {
			return null;
		}
		return key($this->items);
	}
	/**
	 * @see \Iterator
	 */
	public function next() {
		if( $this->items===null ) {
			return null;
		}
		return next($this->items);
	}
	/**
	 * @see \Iterator
	 */
	public function rewind() {
		if( $this->items!==null ) {
			reset($this->items);
		}
	}
	/**
	 * @see \Iterator
	 * @return bool
	 */
	public function valid()	{
		if( $this->items===null ) {
			return false;
		}
		return key($this->items)!==null;
	}

}