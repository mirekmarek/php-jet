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
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Related
 */
namespace Jet;

class DataModel_Related_MtoN_Iterator extends BaseObject implements DataModel_Related_MtoN_Iterator_Interface {

	/**
	 * @var DataModel_Definition_Model_Related_MtoN
	 */
	protected $item_definition;

	/**
	 * @var DataModel_Related_MtoN[]
	 */
	protected $items = [];

	/**
	 * @var DataModel_Related_MtoN
	 */
	protected $_empty_item_instance;

	/**
	 * @var DataModel
	 */
	private $_M_instance;

	/**
	 * @var DataModel_Interface
	 */
	private $_main_model_instance = null;

	/**
	 * @var DataModel_Related_Interface
	 */
	private $_parent_model_instance = null;

	/**
	 * @var array
	 */
	private $_load_related_data_where_query_part = [];

	/**
	 * @var array
	 */
	private $_load_related_data_order_by = [];

	/**
	 * @var DataModel_Load_OnlyProperties|null
	 */
	private $_load_only_properties;

	/**
	 * @var DataModel_Related_MtoN[]
	 */
	private $_deleted_items = [];

	/**
	 * @param DataModel_Definition_Model_Related_MtoN $item_definition
	 */
	public function __construct( DataModel_Definition_Model_Related_MtoN $item_definition ) {

		$this->item_definition = $item_definition;
	}

	/**
	 * @return DataModel_Related_MtoN
	 */
	protected function _getEmptyItemInstance() {

		if(!$this->_empty_item_instance) {
			$class_name = $this->item_definition->getClassName();
			$this->_empty_item_instance = new $class_name();
		}

		return $this->_empty_item_instance;

	}

	/**
	 * @param DataModel_Load_OnlyProperties|null $load_only_properties
	 *
	 * @return array
	 */
	public function loadRelatedData( DataModel_Load_OnlyProperties $load_only_properties=null )
	{
		if(
			$load_only_properties
		) {
			if(
				!$load_only_properties->getAllowToLoadModel( $this->item_definition->getModelName() ) &&
				!$load_only_properties->getAllowToLoadModel( $this->item_definition->getNModelName() )
			) {
				return [];
			}

			$this->_load_only_properties = $load_only_properties;
		}


		$query = $this->getLoadRelatedDataQuery( $load_only_properties );

		return $this->_getEmptyItemInstance()->getBackendInstance()->fetchAll($query);
	}

	/**
	 *
	 * @return DataModel_Query
	 */
	protected function getLoadRelatedDataQuery() {

		$query = new DataModel_Query( $this->item_definition );

		$query->setWhere($this->getLoadRelatedDataWhereQueryPart());

		$where = $query->getWhere();


		$M_ID_properties = $this->item_definition->getMRelationIDProperties();

		$M_ID = $this->_M_instance->getIdObject();

		foreach($M_ID_properties as $M_ID_property) {

			/**
			 * @var DataModel_Definition_Property_Abstract $M_ID_property
			 */
			$value = $M_ID[$M_ID_property->getRelatedToPropertyName()];

			$where->addAND();
			$where->addExpression( $M_ID_property, DataModel_Query::O_EQUAL, $value);
		}

		$query->setSelect( $this->item_definition->getProperties() );

		$relation = $this->item_definition->getRelationToN();
		$this_N_model_name = $this->item_definition->getNModelName();
		$query->addRelation($this_N_model_name, $relation);


		$order_by = $this->getLoadRelatedDataOrderBy();
		if($order_by) {
			$query->setOrderBy( $order_by );
		}

		return $query;
	}

	/**
	 * @param array $where
	 */
	public function setLoadRelatedDataWhereQueryPart(array $where)
	{
		$this->_load_related_data_where_query_part = $where;
	}

	/**
	 * @return array
	 */
	public function getLoadRelatedDataWhereQueryPart()
	{
		return $this->_load_related_data_where_query_part;
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
	 * @param DataModel_Interface $main_model_instance
	 * @param DataModel_Related_Interface $parent_model_instance (optional)
	 *
	 * @throws DataModel_Exception
	 */
	public function setupParentObjects(DataModel_Interface $main_model_instance, DataModel_Related_Interface $parent_model_instance = null)
	{

		$this->_main_model_instance = $main_model_instance;
		$this->_parent_model_instance = $parent_model_instance;

		if( $parent_model_instance ) {
			$this->_M_instance = $parent_model_instance;
		} else {
			$this->_M_instance = $main_model_instance;
		}

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


		foreach($this->_deleted_items as $item) {
			/**
			 * @var DataModel_Related_MtoN $item
			 */
			if($item->getIsSaved()) {
				$item->setupParentObjects($this->_main_model_instance, $this->_parent_model_instance);
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
	 *
	 */
	public function removeAllItems() {
		if($this->items) {
			$this->_deleted_items = $this->items;
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
				if($item->getNID()->toString()==$N_instance->getIdObject()->toString()) {
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
				if($item->getNID()->toString()==$N_instance->getIdObject()->toString()) {
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
	 * @param array &$loaded_related_data
	 * @return mixed
	 */
	public function loadRelatedInstances(array &$loaded_related_data)
	{

		$this->_deleted_items = [];

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
		return [];
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

		$valid_class_name = $this->item_definition->getNModelClassName();

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

		$class_name = $this->item_definition->getClassName();
		/**
		 * @var DataModel_Related_MtoN $item
		 */
		$item = new $class_name();
		$item->setupParentObjects( $this->_main_model_instance, $this->_parent_model_instance );
		$item->setIsNew();

		$item->setNInstance( $value );


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

		$this->_deleted_items[] = $this->items[$offset];

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
	 * @return DataModel
	 */
	protected function _getCurrentItem( DataModel_Related_MtoN $item ) {
		return $item->getNInstance( $this->_load_only_properties );
	}

}