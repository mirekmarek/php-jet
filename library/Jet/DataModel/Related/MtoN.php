<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
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

abstract class DataModel_Related_MtoN extends DataModel implements \ArrayAccess, \Iterator, \Countable  {

	/**
	 * @var string|null
	 */
	protected $__data_model_M_model_class_name = null;
	/**
	 * @var string|null
	 */
	protected $__data_model_N_model_class_name = null;

	/**
	 * @var array
	 */
	protected static $__data_model_properties_definition = array();

	/**
	 * @var DataModel_ID_Abstract
	 */
	protected $M_ID = null;

	/**
	 * @var DataModel_ID_Abstract
	 */
	protected $empty_N_ID = null;
	/**
	 * Data items
	 *
	 * @var DataModel_ID_Abstract[]
	 */
	protected $N_IDs = null;

	/**
	 * @var DataModel[]
	 */
	protected $N_data = array();

	/**
	 * @var DataModel_Definition_Model_Related_MtoN
	 */
	protected $definition = null;

	/**
	 * Do nothing
	 *
	 * @param bool $called_after_save (optional)
	 * @param bool $backend_save_result (optional)
	 */
	protected function generateID(  $called_after_save = false, $backend_save_result = null  ) {
	}

	/**
	 * @return string
	 */
	public function getNModelClassName() {
		return $this->__data_model_N_model_class_name;
	}

	/**
	 * @return string
	 */
	public function getMModelClassName() {
		return $this->__data_model_M_model_class_name;
	}

	/**
	 * Returns model definition
	 *
	 * @return DataModel_Definition_Model_Related_MtoN
	 */
	public function getDataModelDefinition()  {
		if($this->definition===null) {
			/**
			 * @var DataModel $M_model_instance
			 */
			$M_model_instance = Factory::getInstance( $this->__data_model_M_model_class_name );

			/**
			 * @var DataModel $N_model_instance
			 */
			$N_model_instance = Factory::getInstance( $this->__data_model_N_model_class_name );

			$this->definition = new DataModel_Definition_Model_Related_MtoN(
				$this,
				$M_model_instance->getDataModelDefinition(),
				$N_model_instance->getDataModelDefinition()
			);

		}

		return $this->definition;
	}


	/**
	 * Returns backend type (example: MySQL)
	 *
	 * @return string
	 */
	final public function getBackendType() {
		/**
		 * @var DataModel $class_name
		 */
		$class_name = Factory::getClassName( $this->__data_model_M_model_class_name );

		$pi = new $class_name();

		/**
		 * @var DataModel $pi
		 */
		return $pi->getBackendType();
	}

	/**
	 * Returns Backend options
	 *
	 * @return array
	 */
	final public function getBackendConfig() {
		/**
		 * @var DataModel $class_name
		 */
		$class_name = Factory::getClassName( $this->__data_model_M_model_class_name );

		$pi = new $class_name();

		/**
		 * @var DataModel $pi
		 */
		return $pi->getBackendConfig();
	}

	/**
	 * @param DataModel $M_instance
	 *
	 * @throws DataModel_Exception
	 */
	public function setMRelatedModel( DataModel $M_instance ) {


		if( $M_instance instanceof $this->__data_model_M_model_class_name ) {
			/**
			 * @var DataModel $M_instance
			 */
			$this->M_ID = $M_instance->getID();
			return;
		}

		if(
			!($M_instance instanceof $this->__data_model_M_model_class_name) &&
			!($M_instance instanceof $this->__data_model_N_model_class_name)
		) {
			throw new DataModel_Exception(
							"M DataModel must be instance of ".$this->__data_model_M_model_class_name." or ".$this->__data_model_N_model_class_name." class",
							DataModel_Exception::CODE_DEFINITION_NONSENSE
					);
		}

		/**
		 * @var DataModel $M_instance
		 */
		$this->M_ID = $M_instance->getID();

		$this->N_IDs = null;
		$this->N_data = array();

		$new_M_class_name = $this->__data_model_N_model_class_name;
		$this->__data_model_N_model_class_name = $this->__data_model_M_model_class_name;
		$this->__data_model_M_model_class_name = $new_M_class_name;


		/**
		 * @var DataModel $N_instance
		 */
		$N_instance = Factory::getInstance($this->__data_model_N_model_class_name);

		$this->getDataModelDefinition()->setupRelation(
				$M_instance->getDataModelDefinition(),
				$N_instance->getDataModelDefinition()
			);

	}

	/**
	 * @param DataModel[] $N_instances
	 *
	 * @throws DataModel_Exception
	 */
	public function setItems( $N_instances ) {

		$this->N_data = array();
		$this->N_IDs = array();

		$valid_class_name =$this->__data_model_N_model_class_name;
		foreach($N_instances as $N) {
			/**
			 * @var DataModel $N
			 */
			if(! ($N instanceof $valid_class_name) ) {
				throw new DataModel_Exception(
					"N instance must be instance of '{$valid_class_name}'. '".get_class($N)."' given ",
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}
			$this->N_IDs[] = $N->getID();
		}
	}

	/**
	 * @param DataModel $M_model_instance
	 * @param DataModel $N_model_instance
	 * @param bool $get_relation_data
	 *
	 * @return DataModel_Query_Relation_Item[]|bool
	 */
	public function checkIsRelevantMtoNRelation( DataModel $M_model_instance, DataModel $N_model_instance, $get_relation_data=false ) {
		//query is seeking for MtoN relation, so relevant MtoN relation class/table is needed  ...
		/** @noinspection PhpUndefinedVariableInspection */
		if(
			!($M_model_instance instanceof $this->__data_model_M_model_class_name) &&
			!($M_model_instance instanceof $this->__data_model_N_model_class_name)
		) {
			return false;
		}

		/** @noinspection PhpUndefinedVariableInspection */
		if(
			!($N_model_instance instanceof $this->__data_model_M_model_class_name) &&
			!($N_model_instance instanceof $this->__data_model_N_model_class_name)
		) {
			return false;
		}

		if(!$get_relation_data) {
			return true;
		}
		/**
		 * @var DataModel $N_model_instance
		 */

		//YES - THIS class is relevant for us!
		$this->setMRelatedModel( $M_model_instance );

		$this_definition = $this->getDataModelDefinition();
		$N_model_definition = $N_model_instance->getDataModelDefinition();

		$M_ID_properties = $this_definition->getMModelRelationIDProperties();
		$N_ID_properties = $this_definition->getNModelRelationIDProperties();

		$m2n_class_2_n_class_relation = array();

		foreach( $N_model_definition->getIDProperties() as $g_ID_p ) {

			$relation_ID_property_name = DataModel::getRelationIDPropertyName( $N_model_definition, $g_ID_p );

			$g_ID_p = clone $g_ID_p;
			$g_ID_p->setUpRelation( $N_ID_properties[$relation_ID_property_name] );

			$m2n_class_2_n_class_relation[$g_ID_p->getName()] = $g_ID_p;
		}

		$query_related_data = array();
		$query_related_data[$this_definition->getModelName()] =  new DataModel_Query_Relation_Item( $this_definition, $M_ID_properties );
		$query_related_data[$N_model_definition->getModelName()]  = new DataModel_Query_Relation_Item( $N_model_instance->getDataModelDefinition(), $m2n_class_2_n_class_relation);

		return $query_related_data;

	}


	/**
	 * Loads DataModel.
	 *
	 * @param DataModel $main_model_instance
	 * @param DataModel_Related_Abstract $parent_model_instance
	 *
	 * @return DataModel
	 */
	public function loadRelated( DataModel $main_model_instance, DataModel_Related_Abstract $parent_model_instance=null  ) {

		if($parent_model_instance) {
			$M = $parent_model_instance;
		} else {
			$M = $main_model_instance;
		}
		$this->setMRelatedModel( $M );

		return $this;
	}

	/**
	 * Do nothing
	 *
	 * @return bool
	 */
	public function validateData() {
		return true;
	}

	/**
	 *
	 * @param DataModel $main_model_instance (optional)
	 * @param DataModel_Related_Abstract $parent_model_instance (optional)
	 *
	 * @throws Exception
	 * @throws DataModel_Exception
	 *
	 */
	public function saveRelated( DataModel $main_model_instance, DataModel_Related_Abstract $parent_model_instance=null ) {

		if($parent_model_instance) {
			$M_instance = $parent_model_instance;
		} else {
			$M_instance = $main_model_instance;
		}

		$this->setMRelatedModel($M_instance);

		$this->_fetchNIDs();

		$definition = $this->getDataModelDefinition();
		$backend = $this->getBackendInstance();

		$q_M = $this->_getMIdQuery();

		$backend->delete( $q_M );

		if(!$this->N_IDs) {
			return;
		}

		$main_record = new DataModel_RecordData($definition);

		foreach($definition->getMModelRelationIDProperties() as $property) {
			/**
			 * @var DataModel_Definition_Property_Abstract $property
			 */
			$main_record->addItem(
				$property,
				$this->M_ID[$property->getRelatedToProperty()->getName()]
			);
		}

		$N_ID_map = array();

		foreach($definition->getNModelRelationIDProperties() as $property) {
			/**
			 * @var DataModel_Definition_Property_Abstract $property
			 */
			$N_ID_map[$property->getRelatedToProperty()->getName()] = $property;
		}

		foreach($this->N_IDs as $N_ID) {
			$record = clone $main_record;

			foreach($N_ID_map as $name=>$property) {
				$record->addItem($property, $N_ID[$name] );
			}
			$backend->save($record);
		}
	}

	/**
	 * Delete object
	 *
	 * @throws DataModel_Exception
	 */
	public function delete() {
		if( !$this->M_ID ) {
			throw new DataModel_Exception(
				"Nothing to delete... Object was not loaded.",
				DataModel_Exception::CODE_NOTHING_TO_DELETE
			);
		}

		$this->getBackendInstance()->delete( $this->_getMIdQuery() );
	}

	/**
	 * @return array
	 */
	public function jsonSerialize() {
		$this->_fetchNIDs();

		$result = array();
		foreach($this->N_IDs as $ID) {
			$result[] = (string)$ID;
		}

		return $result;
	}

	/**
	 * @param string $prefix
	 *
	 * @return string
	 */
	protected function _XMLSerialize($prefix="" ) {
		/**
		 * @var DataModel $N_model_instance
		 */
		$N_model_instance = Factory::getInstance( $this->__data_model_N_model_class_name );
		$N_model_definition = $N_model_instance->getDataModelDefinition();
		$N_class_name =$N_model_definition->getModelName();

		$this->_fetchNIDs();

		$result = "";

		foreach($this->N_IDs as $ID_value) {
			$result .= $prefix . "\t<$N_class_name>\n";
			foreach($ID_value as $ID_k=>$ID_v) {
				$result .= $prefix . "\t\t<$ID_k>".htmlspecialchars($ID_v)."</$ID_k>\n";
			}
			$result .= $prefix . "\t</$N_class_name>\n";
		}

		return $result;
	}

	/**
	 * @return DataModel_Query
	 */
	protected function _getMIdQuery() {
		$query = new DataModel_Query( $this );
		$query->setWhere(array());
		$where = $query->getWhere();

		foreach($this->getDataModelDefinition()->getMModelRelationIDProperties() as $pr_property) {
			/**
			 * @var DataModel_Definition_Property_Abstract $pr_property
			 * @var DataModel_Definition_Property_Abstract $rt_property
			 */
			$rt_property = $pr_property->getRelatedToProperty();
			$pr_property_name = $rt_property->getName();
			$value = $this->M_ID[$pr_property_name];

			if($value===null)  {
				continue;
			}

			$where->addAND();
			$where->addExpression( $pr_property, DataModel_Query::O_EQUAL, $value);
		}
		$query->setSelect($this->getDataModelDefinition()->getNModelRelationIDProperties());

		return $query;
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
	 * @see Countable
	 *
	 * @return int
	 */
	public function count() {
		$this->_fetchNIDs();
		return count($this->N_IDs);
	}

	/**
	 * @see ArrayAccess
	 * @param int $offset
	 * @return bool
	 */
	public function offsetExists( $offset  ) {
		$this->_fetchNIDs();
		return isset($this->N_IDs[(int)$offset]);
	}
	/**
	 * @see ArrayAccess
	 * @param string $offset
	 *
	 * @return DataModel
	 */
	public function offsetGet( $offset ) {
		$this->_fetchNIDs();
		return $this->_get($this->N_IDs[(int)$offset]);
	}

	/**
	 *
	 * @see ArrayAccess
	 *
	 * @param int $offset
	 * @param mixed $value
	 *
	 * @throws DataModel_Exception
	 */
	public function offsetSet( $offset , $value ) {
		throw new DataModel_Exception(
			"Please do not change MtoN model directly. Use ".get_class($this)."->setNIDs()",
			DataModel_Exception::CODE_PERMISSION_DENIED
		);
	}

	/**
	 * @see ArrayAccess
	 * @param int $offset
	 */
	public function offsetUnset( $offset )	{
		$this->_fetchNIDs();
		unset( $this->N_IDs[(int)$offset] );
	}

	/**
	 * @see Iterator
	 *
	 * @return DataModel
	 */
	public function current() {
		$this->_fetchNIDs();

		return $this->_get( current($this->N_IDs) );
	}
	/**
	 * @see Iterator
	 *
	 * @return string
	 */
	public function key() {
		$this->_fetchNIDs();
		return key($this->N_IDs);
	}
	/**
	 * @see Iterator
	 */
	public function next() {
		$this->_fetchNIDs();
		return next($this->N_IDs);
	}
	/**
	 * @see Iterator
	 */
	public function rewind() {
		$this->_fetchNIDs();
		reset($this->N_IDs);
	}
	/**
	 * @see Iterator
	 * @return bool
	 */
	public function valid()	{
		$this->_fetchNIDs();
		return key($this->N_IDs)!==null;
	}


	/**
	 * Fetches IDs...
	 *
	 * @return void
	 */
	protected function _fetchNIDs() {
		if($this->N_IDs!==NULL) {
			return;
		}


		/**
		 * @var DataModel $N_model_instance
		 */
		$N_model_instance = Factory::getInstance($this->__data_model_N_model_class_name);
		$this->empty_N_ID = $N_model_instance->getEmptyIDInstance();

		$this->N_IDs = array();
		$this->N_data = array();

		if(!$this->M_ID) {
			return;
		}

		$ID_q = $this->_getMIdQuery();

		$IDs = $this->getBackendInstance()->fetchAll( $ID_q );

		foreach($IDs as $ID) {

			$N_ID = clone $this->empty_N_ID;

			foreach( $this->getDataModelDefinition()->getNModelRelationIDProperties() as $N_ID_prop_name=>$N_ID_prop) {
				$N_ID[$N_ID_prop->getRelatedToProperty()->getName()] = $ID[$N_ID_prop_name];
			}

			$this->N_IDs[] = $N_ID;
		}

		foreach($this->N_IDs as $N_ID) {
			$this->N_data[(string)$N_ID] = null;
		}

	}

	/**
	 * @param DataModel_ID_Abstract $ID
	 * @return DataModel
	 */
	protected function _get( DataModel_ID_Abstract $ID ) {
		$s_ID = (string)$ID;
		if(isset($this->N_data[$s_ID])) {
			return $this->N_data[$s_ID];
		}


		$this->N_data[$s_ID] = Factory::getInstance( $this->__data_model_N_model_class_name );
		$this->N_data[$s_ID] = $this->N_data[$s_ID]->load( $ID );

		return $this->N_data[$s_ID];
	}

	/**
	 *
	 * @return array
	 */
	public function __sleep() {

		return array(
			"__data_model_M_model_class_name",
			"__data_model_N_model_class_name",
			"M_ID",
			"N_IDs"
		);
	}

	public function __wakeup() {
	}

}