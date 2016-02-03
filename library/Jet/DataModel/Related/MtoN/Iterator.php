<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2015 Miroslav Marek <mirek.marek.2m@gmail.com>
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

class DataModel_Related_MtoN_Iterator extends Object implements \ArrayAccess, \Iterator, \Countable, DataModel_Related_Interface   {

	/**
	 * @var string
	 */
	protected $item_class_name = '';

	/**
	 * @var DataModel_Related_MtoN[]
	 */
	protected $items = [];

	/**
	 * @param $item_class_name
	 */
	public function __construct( $item_class_name ) {

		$this->item_class_name = $item_class_name;
	}

	/**
	 * @return DataModel_Related_MtoN
	 */
	protected function _getEmptyItemInstance() {
		/**
		 * @var DataModel_Related_MtoN $this_empty_item_instance
		 */
		$this_empty_item_instance = &DataModel_ObjectState::getVar($this, 'empty_item_instance');

		if(!$this_empty_item_instance) {
			/**
			 * @var DataModel $this_main_model_instance
			 */
			$this_main_model_instance = &DataModel_ObjectState::getVar($this, 'main_model_instance');
			/**
			 * @var DataModel_Related_Interface $this_parent_model_instance
			 */
			$this_parent_model_instance = &DataModel_ObjectState::getVar($this, 'parent_model_instance');

			$this_empty_item_instance = new $this->item_class_name();

			$this_empty_item_instance->setupParentObjects( $this_main_model_instance, $this_parent_model_instance );
		}

		return $this_empty_item_instance;

	}

	/**
	 * @param array $where
	 */
	public function setLoadRelatedDataWhereQueryPart(array $where)
	{
		$this->_getEmptyItemInstance()->setLoadRelatedDataWhereQueryPart($where);
	}

	/**
	 * @param array $order_by
	 */
	public function setLoadRelatedDataOrderBy(array $order_by)
	{
		$this->_getEmptyItemInstance()->setLoadRelatedDataOrderBy( $order_by );
	}

	/**
	 * @param $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Related_Abstract
	 */
	public static function _getDataModelDefinitionInstance( $data_model_class_name ) {
		return new DataModel_Definition_Model_Related_MtoN( $data_model_class_name );
	}


	/**
	 * @param DataModel_Interface $main_model_instance
	 * @param DataModel_Related_Interface $parent_model_instance (optional)
	 *
	 * @throws DataModel_Exception
	 */
	public function setupParentObjects(DataModel_Interface $main_model_instance, DataModel_Related_Interface $parent_model_instance = null)
	{
		$this_main_model_instance = &DataModel_ObjectState::getVar($this, 'main_model_instance');
		$this_parent_model_instance = &DataModel_ObjectState::getVar($this, 'parent_model_instance');

		$this_main_model_instance = $main_model_instance;
		$this_parent_model_instance = $parent_model_instance;

		if( $parent_model_instance ) {
			$M_instance = $parent_model_instance;
		} else {
			$M_instance = $main_model_instance;
		}


		/**
		 * @var DataModel $M_instance
		 */
		$M_model_name = $M_instance->getDataModelDefinition()->getModelName();

		/**
		 * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
		 */
		$data_model_definition = DataModel::getDataModelDefinition($this->item_class_name);

		if(!$data_model_definition->getRelatedModelDefinition($M_model_name)  ) {
			throw new DataModel_Exception(
				'Class \''.get_class($M_instance).'\' (model name: \''.$M_model_name.'\') is not related to \''.get_class($this).'\'  (class: \''.get_called_class().'\') ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$N_model_name = $data_model_definition->getNModelName($M_model_name);


		$this_M_model_name = &DataModel_ObjectState::getVar($this, 'M_model_name' );
		$this_M_model_name = $M_model_name;

		$this_N_model_name = &DataModel_ObjectState::getVar($this, 'N_model_name' );
		$this_N_model_name = $N_model_name;

		$this_M_model_class_name = &DataModel_ObjectState::getVar($this, 'M_model_class_name' );
		$this_M_model_class_name = $data_model_definition->getRelatedModelDefinition($M_model_name)->getClassName();

		$this_N_model_class_name = &DataModel_ObjectState::getVar($this, 'N_model_class_name' );
		$this_N_model_class_name = $data_model_definition->getRelatedModelDefinition($N_model_name)->getClassName();


		$this_M_instance = &DataModel_ObjectState::getVar($this, 'M_instance');
		$this_M_instance = $M_instance;

		$this_M_ID = &DataModel_ObjectState::getVar($this, 'M_ID');
		$this_M_ID = $M_instance->getID();

		$this->_getEmptyItemInstance()->setupParentObjects($main_model_instance, $parent_model_instance);

		if($this->items) {
			foreach( $this->items as $item ) {
				$item->setupParentObjects( $main_model_instance, $parent_model_instance );
			}

		}

	}

	/**
	 *
	 * @throws Exception
	 * @throws DataModel_Exception
	 */
	public function save() {
		$this_deleted_items = &DataModel_ObjectState::getVar($this, 'deleted_items', []);

		foreach($this_deleted_items as $item) {
			/**
			 * @var DataModel_Related_MtoN $item
			 */
			if($item->getIsSaved()) {
				$item->delete();
			}
		}

		if( !$this->items ) {
			return;
		}
		$this_main_model_instance = &DataModel_ObjectState::getVar($this, 'main_model_instance');
		$this_parent_model_instance = &DataModel_ObjectState::getVar($this, 'parent_model_instance');

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
		 * @var DataModel_Related_MtoN[] $this_deleted_items
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
	 *
	 */
	public function removeAllItems() {
		if($this->items) {
			$this_deleted_items = &DataModel_ObjectState::getVar($this, 'deleted_items', []);
			$this_deleted_items = $this->items;
		}
		$this->items = [];
	}

	/**
	 * @param DataModel[] $N_instances
	 *
	 * @throws DataModel_Exception
	 */
	public function addItems( $N_instances ) {
		foreach( $N_instances as $N_instance ) {
			$this->offsetSet(null, $N_instance );
		}
	}

	/**
	 * @param DataModel[] $N_instances
	 *$this->_items
	 * @throws DataModel_Exception
	 */
	public function setItems( $N_instances ) {

		$add_items = [];

		foreach( $this->items as $i=>$item ) {
			$exists = false;
			foreach( $N_instances as $N_instance ) {
				if($item->getNID()->toString()==$N_instance->getID()->toString()) {
					$exists = true;
					break;
				}
			}

			if(!$exists) {
				$this->offsetUnset($i);
			}

		}

		foreach( $N_instances as $N_instance ) {
			$exists = false;
			foreach( $this->items as $item ) {
				if($item->getNID()->toString()==$N_instance->getID()->toString()) {
					$exists = true;
					break;
				}
			}

			if(!$exists) {
				$add_items[] = $N_instance;
			}
		}


		if($add_items) {
			$this->addItems( $add_items );
		}

	}

	/**
	 * @return DataModel_ID_Abstract[]
	 */
	public function getIDs() {
		$IDs = [];

		foreach( $this->items as $item ) {
			$IDs[] = $item->getNID();
		}

		return $IDs;
	}

	/**
	 * @return DataModel_Related_Interface
	 */
	public function createNewRelatedDataModelInstance()
	{
		return $this;
	}

	/**
	 * @return array
	 */
	public function loadRelatedData()
	{
		return $this->_getEmptyItemInstance()->loadRelatedData();
	}

	/**
	 * @param array &$loaded_related_data
	 * @return mixed
	 */
	public function loadRelatedInstances(array &$loaded_related_data)
	{

		$this_deleted_items = &DataModel_ObjectState::getVar($this, 'deleted_items', []);
		$this_deleted_items = [];

		$this->items = $this->_getEmptyItemInstance()->loadRelatedInstances($loaded_related_data);

		return $this;
	}


	/**
	 * @return array
	 */
	public function getCommonFormPropertiesList() {
		return [];
	}


	/**
	 *
	 * @param DataModel_Definition_Property_Abstract $parent_property_definition
	 * @param array $properties_list
	 *
	 * @return Form_Field_Abstract[]
	 */
	public function getRelatedFormFields( DataModel_Definition_Property_Abstract $parent_property_definition, array $properties_list ) {
		return [];
	}

	/**
	 * @param array $values
	 *
	 * @return bool
	 */
	public function catchRelatedForm(array $values)
	{
		return true;
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
				 * @var DataModel_Related_MtoN $d
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
		return ['item_class_name'];
	}

	public function __wakeup() {
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
	 * @return DataModel|DataModel_Related_MtoN
	 */
	public function offsetGet( $offset ) {
		return $this->_getCurrentItem($this->items[$offset]);
	}

	/**
	 *
	 * @see ArrayAccess
	 *
	 * @param int $offset
	 * @param DataModel $value
	 *
	 * @throws DataModel_Exception
	 */
	public function offsetSet( $offset , $value ) {
		$this_N_model_class_name = &DataModel_ObjectState::getVar($this, 'N_model_class_name');

		$valid_class_name = $this_N_model_class_name;

		if(!is_object($value)) {
			throw new DataModel_Exception(
				'Value instance must be instance of \''.$valid_class_name.'\'.'
			);

		}

		if(! ($value instanceof $valid_class_name) ) {
			throw new DataModel_Exception(
				'Value instance must be instance of \''.$valid_class_name.'\'. \''.get_class($value).'\' given '
			);
		}

		/**
		 * @var DataModel $value
		 */
		if(!$value->getIsSaved()) {
			throw new DataModel_Exception(
				'Object instance must be saved '
			);
		}

		$this_main_model_instance = &DataModel_ObjectState::getVar($this, 'main_model_instance');
		$this_parent_model_instance = &DataModel_ObjectState::getVar($this, 'parent_model_instance');

		/**
		 * @var DataModel_Related_MtoN $item
		 */
		$item = new $this->item_class_name();
		$item->setupParentObjects( $this_main_model_instance, $this_parent_model_instance );
		$item->setIsNew();

		$item->_setNDataModelInstance( $value );


		if(is_null($offset)) {
			/**
			 * @var DataModel_Related_1toN $value
			 */
			$offset = $item->getArrayKeyValue();
			if(is_object($offset)) {
				$offset = (string)$offset;
			}
		}

		if(!$offset) {
			$this->items[] = $item;
		} else {
			$this->items[$offset] = $item;
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
	 * @return DataModel|DataModel_Related_MtoN
	 */
	public function current() {
		if( $this->items===null ) {
			return null;
		}
		$current = current($this->items);

		return $this->_getCurrentItem($current);
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
	 * @param DataModel_Related_MtoN $item
	 *
	 * @return DataModel_Related_MtoN
	 */
	protected function _getCurrentItem( DataModel_Related_MtoN $item ) {
		return $item->getInstanceOfN();
	}

	/**
	 * @param mixed $key
	 *
	 * @return DataModel_Related_MtoN
	 */
	public function getGlueItem( $key ) {
		return $this->items[$key];
	}

}