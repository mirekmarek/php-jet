<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
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

class DataModel_Related_1toN_Iterator extends BaseObject implements DataModel_Related_1toN_Iterator_Interface {

	/**
	 * @var DataModel_Definition_Model_Related_1toN
	 */
	protected $item_definition;

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
	 * @var DataModel_Interface
	 */
	private $_main_model_instance;

	/**
	 * @var DataModel_Load_OnlyProperties
	 */
	private $_load_only_properties;

	/**
	 * @var DataModel_Interface
	 */
	private $_parent_model_instance;

	/**
	 * @var array
	 */
	private $_load_related_data_order_by = [];

	/**
	 * @param DataModel_Definition_Model_Related_1toN $item_definition
	 */
	public function __construct( DataModel_Definition_Model_Related_1toN $item_definition ) {

		$this->item_definition = $item_definition;
	}


	/**
	 * @return DataModel_Related_1toN
	 */
	protected function _getEmptyItemInstance() {

		if(!$this->_empty_item_instance) {

			$class_name = $this->item_definition->getClassName();
			$this->_empty_item_instance = new $class_name();

			$this->_empty_item_instance->setLoadOnlyProperties($this->_load_only_properties);
		}

		return $this->_empty_item_instance;

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

		$this->_main_model_instance = $main_model_instance;
		$this->_parent_model_instance = $parent_model_instance;

		$this->_getEmptyItemInstance()->setupParentObjects($this->_main_model_instance, $this->_parent_model_instance);

		if($this->items) {
			foreach( $this->items as $item ) {
				$item->setupParentObjects( $this->_main_model_instance, $this->_parent_model_instance );
			}
		}


	}


	/**
	 * @param array $order_by
	 */
	public function setLoadRelatedDataOrderBy(array $order_by)
	{
		$this->_load_related_data_order_by = $order_by;
	}

	/**
	 * @return array
	 */
	public function getLoadRelatedDataOrderBy()
	{
		return $this->_load_related_data_order_by ? $this->_load_related_data_order_by : $this->item_definition->getDefaultOrderBy();
	}

	/**
	 * @param DataModel_Load_OnlyProperties|null $load_only_properties
	 *
	 * @return array
	 */
	public function loadRelatedData( DataModel_Load_OnlyProperties $load_only_properties=null )
    {
    	if($load_only_properties) {
		    if(
			    !$load_only_properties->getAllowToLoadModel( $this->item_definition->getModelName() )
		    ) {
			    return [];
		    }

		    $this->_load_only_properties = $load_only_properties;
	    }

	    $query = $this->getLoadRelatedDataQuery();

	    return $this->_getEmptyItemInstance()->getBackendInstance()->fetchAll($query);
	}

	/**
	 * @return DataModel_Query
	 * @throws DataModel_Exception
	 */
	protected function getLoadRelatedDataQuery()
	{

		/**
		 * @var DataModel_Interface|DataModel_Related_Interface $this
		 */

		$query = new DataModel_Query( $this->item_definition );

		$select = DataModel_Load_OnlyProperties::getSelectProperties( $this->item_definition, $this->_load_only_properties );

		$query->setSelect( $select );
		$query->setWhere([]);

		$where = $query->getWhere();

		if( $this->_main_model_instance ) {
			$main_model_ID = $this->_main_model_instance->getIdObject();

			foreach( $this->item_definition->getMainModelRelationIDProperties() as $property ) {
				/**
				 * @var DataModel_Definition_Property_Abstract $property
				 */
				$property_name = $property->getRelatedToPropertyName();
				$value = $main_model_ID[ $property_name ];

				$where->addAND();
				$where->addExpression(
					$property,
					DataModel_Query::O_EQUAL,
					$value

				);

			}
		} else {
			if( $this->_parent_model_instance ) {
				$parent_model_ID = $this->_parent_model_instance->getIdObject();

				foreach( $this->item_definition->getParentModelRelationIDProperties() as $property ) {
					/**
					 * @var DataModel_Definition_Property_Abstract $property
					 */
					$property_name = $property->getRelatedToPropertyName();
					$value = $parent_model_ID[ $property_name ];

					$where->addAND();
					$where->addExpression(
						$property,
						DataModel_Query::O_EQUAL,
						$value

					);

				}

			} else {
				throw new DataModel_Exception('Parents are not set!');
			}
		}

		$order_by = $this->getLoadRelatedDataOrderBy();
		if($order_by) {
			$query->setOrderBy( $order_by );
		}


		return $query;
	}


	/**
	 * @param array &$loaded_related_data
	 * @return mixed
	 */
	public function loadRelatedInstances(array &$loaded_related_data ) {
		$this->_deleted_items = [];

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
			 */
			$related_form_fields = $related_instance->getRelatedFormFields( $parent_property_definition, $properties_list );

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
			$item->setupParentObjects($this->_main_model_instance, $this->_parent_model_instance);
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


		$valid_class = $this->item_definition->getClassName();

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