<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2015 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Related
 */
namespace Jet;

class DataModel_Related_1toN_Iterator extends Object implements \ArrayAccess, \Iterator, \Countable, DataModel_Related_Interface   {

	/**
	 * @var string
	 */
	protected $item_class_name = '';

	/**
	 * @var DataModel_Related_1toN[]
	 */
	protected $items = [];

	/**
	 * @param $item_class_name
	 */
	public function __construct( $item_class_name ) {

		$this->item_class_name = $item_class_name;
	}


	/**
	 * @return DataModel_Related_1toN
	 */
	protected function _getEmptyItemInstance() {
		$this_main_model_instance = &DataModel_ObjectState::getVar($this, 'main_model_instance');
		$this_parent_model_instance = &DataModel_ObjectState::getVar($this, 'parent_model_instance');

		/**
		 * @var DataModel_Related_1toN $this_empty_item_instance
		 */
		$this_empty_item_instance = &DataModel_ObjectState::getVar($this, 'empty_item_instance');

		if(!$this_empty_item_instance) {
			$class_name = $this->item_class_name;

			$this_empty_item_instance = new $class_name();

			$this_empty_item_instance->setupParentObjects( $this_main_model_instance, $this_parent_model_instance );
		}

		return $this_empty_item_instance;

	}

	/**
	 * @return DataModel_Related_Interface|null
	 */
	public function createNewRelatedDataModelInstance() {
		return $this;
	}

	/**
	 * @param DataModel_Interface $main_model_instance
	 * @param DataModel_Related_Interface $parent_model_instance
	 */
	public function setupParentObjects( DataModel_Interface $main_model_instance, DataModel_Related_Interface $parent_model_instance=null ) {

		$this_main_model_instance = &DataModel_ObjectState::getVar($this, 'main_model_instance');
		$this_parent_model_instance = &DataModel_ObjectState::getVar($this, 'parent_model_instance');

		$this_main_model_instance = $main_model_instance;
		$this_parent_model_instance = $parent_model_instance;

		/**
		 * @var DataModel_Related_1toN $this_empty_item_instance
		 */
		$this_empty_item_instance = &DataModel_ObjectState::getVar($this, 'empty_item_instance');

		if($this_empty_item_instance) {
			$this_empty_item_instance->setupParentObjects($this_main_model_instance, $this_parent_model_instance);
		}

		if($this->items) {
			foreach( $this->items as $item ) {
				$item->setupParentObjects( $this_main_model_instance, $this_parent_model_instance );
			}
		}


	}


	/**
	 * @param array $order_by
	 */
	public function setLoadRelatedDataOrderBy(array $order_by)
	{
		$this->_getEmptyItemInstance()->setLoadRelatedDataOrderBy( $order_by );
	}

	/**
	 * @return array|void
	 */
	public function loadRelatedData() {

		return $this->_getEmptyItemInstance()->loadRelatedData();
	}

	/**
	 * @param array &$loaded_related_data
	 * @return mixed
	 */
	public function loadRelatedInstances(array &$loaded_related_data ) {
		$this_deleted_items = &DataModel_ObjectState::getVar($this, 'deleted_items', []);
		$this_deleted_items = [];

		$this->items = $this->_getEmptyItemInstance()->loadRelatedInstances($loaded_related_data);

		return $this;
	}

	/**
	 * @return array
	 */
	public function getCommonFormPropertiesList() {
		return $this->_getEmptyItemInstance()->getCommonFormPropertiesList();
	}

	/**
	 *
	 * @param DataModel_Definition_Property_Abstract $parent_property_definition
	 * @param array $properties_list
	 *
	 * @return Form_Field_Abstract[]
	 */
	public function getRelatedFormFields( DataModel_Definition_Property_Abstract $parent_property_definition, array $properties_list ) {

		$fields = [];
		if(!$this->items) {
			return $fields;

		}

		$parent_field_name = $parent_property_definition->getName();

		foreach($this->items as $key=>$related_instance) {

			/**
			 * @var DataModel_Related_1toN $related_instance
			 * @var Form $related_form
			 */
			$related_form = $related_instance->getRelatedFormFields( $parent_property_definition, $properties_list );

			foreach($related_form->getFields() as $field) {

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
	 * @param array $values
	 *
	 * @return bool
	 */
	public function catchRelatedForm( array $values ) {

		$ok = true;
		if(!$this->items) {
			return $ok;

		}

		foreach( $this->items as $r_key=>$r_instance ) {

			$r_values = isset( $values[$r_key] ) ? $values[$r_key] : [];

			/**
			 * @var DataModel $r_instance
			 */
			//$r_form = $r_instance->getForm( '', array_keys($values) );
			$r_form = $r_instance->getCommonForm();

			if(!$r_instance->catchForm( $r_form, $r_values, true )) {
				$ok = false;
			}

		}

		return $ok;
	}



	/**
	 *
	 * @throws Exception
	 * @throws DataModel_Exception
	 */
	public function save() {
		$this_main_model_instance = &DataModel_ObjectState::getVar($this, 'main_model_instance');
		$this_parent_model_instance = &DataModel_ObjectState::getVar($this, 'parent_model_instance');
		$this_deleted_items = &DataModel_ObjectState::getVar($this, 'deleted_items', []);

		foreach($this_deleted_items as $item) {
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
			$item->setupParentObjects($this_main_model_instance, $this_parent_model_instance);
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
		$this_deleted_items = &DataModel_ObjectState::getVar($this, 'deleted_items', []);

		foreach($this_deleted_items as $item) {
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


	/** @noinspection PhpMissingParentCallMagicInspection
	 *
	 * @return array
	 */
	public function __sleep() {
		$this->validateKeys();

		return ['item_class_name', 'items'];
	}

	/**
	 *
	 */
	public function __wakeup() {
		if(!$this->items) {
			$this->items = [];
			$this_deleted_items = &DataModel_ObjectState::getVar($this, 'deleted_items', []);
			$this_deleted_items = [];
		} else {
			$this->validateKeys();
		}
	}


	/**
	 *
	 */
	public function __wakeup_relatedItems() {
		if($this->items) {
			foreach( $this->items as $item ) {
				$item->__wakeup_relatedItems();
			}
		}
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
	public function clearData() {
		if($this->items) {
			$this_deleted_items = &DataModel_ObjectState::getVar($this, 'deleted_items', []);
			$this_deleted_items = $this->items;
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
	 * @return DataModel
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


		$valid_class = $this->item_class_name;

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
		$this_deleted_items = &DataModel_ObjectState::getVar($this, 'deleted_items', []);
		$this_deleted_items[] = $this->items[$offset];

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


	/**
	 *
	 */
	protected function validateKeys() {
		if(!$this->items) {
			return;
		}
		$items = [];
		foreach($this->items as $key=>$item) {

			$new_key = $item->getArrayKeyValue();
			$key = $new_key!==null ? $new_key : $key;

			if(is_object($key)) {
				$key = (string)$key;
			}

			if(!$key) {
				$items[] = $item;
			} else {
				$items[$key] = $item;
			}

		}

		$this->items = $items;

	}

}