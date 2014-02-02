<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2014 Miroslav Marek <mirek.marek.2m@gmail.com>
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

abstract class DataModel_Related_MtoN extends DataModel implements \ArrayAccess, \Iterator, \Countable  {

	/**
	 * @var string|null
	 */
	private $__data_model_current_M_model_class_name = null;
	/**
	 * @var string|null
	 */
	private $__data_model_current_N_model_class_name = null;

	/**
	 * @var string|null
	 */
	private $__data_model_current_M_model_name = null;
	/**
	 * @var string|null
	 */
	private $__data_model_current_N_model_name = null;


	/**
	 * @var DataModel_ID_Abstract
	 */
	private $M_ID = null;

	/**
	 * @var DataModel_ID_Abstract
	 */
	private $empty_N_ID = null;
	/**
	 * Data items
	 *
	 * @var DataModel_ID_Abstract[]
	 */
	private $N_IDs = null;

	/**
	 * @var DataModel[]
	 */
	private $N_data = array();

	/**
	 * @var DataModel_Definition_Model_Related_MtoN
	 */
	private $definition = null;


	/**
	 * @return string
	 */
	public static function getDataModelDefinitionNModelClassName() {
		return Object_Reflection::get( get_called_class(), 'N_model_class_name', null );
	}

	/**
	 * @return string
	 */
	public static function getDataModelDefinitionMModelClassName() {
		return Object_Reflection::get( get_called_class(), 'M_model_class_name', null );
	}

	/**
	 * @param $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Related_Abstract
	 */
	protected static function _getDataModelDefinitionInstance( $data_model_class_name ) {
		return new DataModel_Definition_Model_Related_MtoN( $data_model_class_name );
	}


	/**
	 * Returns backend type (example: MySQL)
	 *
	 * @return string
	 */
	final public static function getBackendType() {
		/**
		 * @var DataModel $class_name
		 */
		$class_name = Factory::getClassName( static::getDataModelDefinitionMModelClassName() );

		return $class_name::getBackendType();
	}

	/**
	 * Returns Backend options
	 *
	 * @return array
	 */
	final public static function getBackendConfig() {
		/**
		 * @var DataModel $class_name
		 */
		$class_name = Factory::getClassName( static::getDataModelDefinitionMModelClassName() );

		return $class_name::getBackendConfig();
	}

	/**
	 * @param DataModel $M_instance
	 *
	 * @throws DataModel_Exception
	 */
	public function setMRelatedModel( DataModel $M_instance ) {

		/**
		 * @var DataModel $M_instance
		 */
		$M_model_name = $M_instance->getDataModelDefinition()->getModelName();
		if(!$M_model_name) {
			throw new DataModel_Exception(
				'Class \''.get_class($M_instance).'\' is not related to \''.get_class($this).'\' ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		if($M_model_name==$this->__data_model_current_M_model_name) {
			$this->M_ID = $M_instance->getID();
			return;
		}


		$N_model_name = $this->definition->getNModelName($M_model_name);


		$this->__data_model_current_M_model_name = $M_model_name;
		$this->__data_model_current_N_model_name = $N_model_name;

		$this->__data_model_current_M_model_class_name = $this->definition->getRelatedModelDefinition($M_model_name)->getClassName();
		$this->__data_model_current_N_model_class_name = $this->definition->getRelatedModelDefinition($N_model_name)->getClassName();

		/**
		 * @var DataModel $M_instance
		 */
		$this->M_ID = $M_instance->getID();

		$this->N_IDs = null;
		$this->N_data = array();

	}

	/**
	 * @param DataModel[] $N_instances
	 *
	 * @throws DataModel_Exception
	 */
	public function setItems( $N_instances ) {

		$this->N_data = array();
		$this->N_IDs = array();

		$valid_class_name =$this->__data_model_current_N_model_class_name;
		foreach($N_instances as $N) {
			/**
			 * @var DataModel $N
			 */
			if(! ($N instanceof $valid_class_name) ) {
				throw new DataModel_Exception(
					'N instance must be instance of \''.$valid_class_name.'\'. \''.get_class($N).'\' given ',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}
			$this->N_IDs[] = $N->getID();
		}
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
	public function validateProperties() {
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

		$M_ID_properties = $this->definition->getRelationIDProperties($this->__data_model_current_M_model_name);
		$N_ID_properties = $this->definition->getRelationIDProperties($this->__data_model_current_N_model_name);

		$main_record = new DataModel_RecordData($definition);

		foreach($M_ID_properties as $property) {
			/**
			 * @var DataModel_Definition_Property_Abstract $property
			 */
			$main_record->addItem(
				$property,
				$this->M_ID[$property->getRelatedToPropertyName()]
			);
		}

		$N_ID_map = array();

		foreach($N_ID_properties as $property) {
			/**
			 * @var DataModel_Definition_Property_Abstract $property
			 */
			$N_ID_map[$property->getRelatedToPropertyName()] = $property;
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
				'Nothing to delete... Object was not loaded.',
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
	protected function _XMLSerialize($prefix='' ) {
		/**
		 * @var DataModel $N_model_instance
		 */
		$N_model_instance = Factory::getInstance( $this->__data_model_current_N_model_class_name );
		$N_model_definition = $N_model_instance->getDataModelDefinition();
		$N_class_name =$N_model_definition->getModelName();

		$this->_fetchNIDs();

		$result = '';

		foreach($this->N_IDs as $ID_value) {
			$result .= $prefix . JET_TAB.'<'.$N_class_name.'>'.JET_EOL;
			foreach($ID_value as $ID_k=>$ID_v) {
				$result .= $prefix . JET_TAB.JET_TAB.'<'.$ID_k.'>'.htmlspecialchars($ID_v).'</'.$ID_k.'>'.JET_EOL;
			}
			$result .= $prefix . JET_TAB.'</'.$N_class_name.'>'.JET_EOL;
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


		$M_ID_properties = $this->definition->getRelationIDProperties($this->__data_model_current_M_model_name);
		$N_ID_properties = $this->definition->getRelationIDProperties($this->__data_model_current_N_model_name);

		foreach($M_ID_properties as $M_ID_property) {
			/**
			 * @var DataModel_Definition_Property_Abstract $M_ID_property
			 */
			$value = $this->M_ID[$M_ID_property->getRelatedToPropertyName()];

			if($value===null)  {
				continue;
			}

			$where->addAND();
			$where->addExpression( $M_ID_property, DataModel_Query::O_EQUAL, $value);
		}

		$query->setSelect( $N_ID_properties );

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
			'Please do not change MtoN model directly. Use '.get_class($this).'->setNIDs()',
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
	 */
	protected function _fetchNIDs() {

		if($this->N_IDs!==null) {
			return;
		}

		$N_ID_properties = $this->definition->getRelationIDProperties($this->__data_model_current_N_model_name);


		/**
		 * @var DataModel $N_model_instance
		 */
		$N_model_instance = Factory::getInstance($this->__data_model_current_N_model_class_name);
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

			foreach( $N_ID_properties as $N_ID_prop_name=>$N_ID_prop) {
				$N_ID[$N_ID_prop->getRelatedToPropertyName()] = $ID[$N_ID_prop_name];
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


		$this->N_data[$s_ID] = Factory::getInstance( $this->__data_model_current_N_model_class_name );
		$this->N_data[$s_ID] = $this->N_data[$s_ID]->load( $ID );

		return $this->N_data[$s_ID];
	}

	/**
	 *
	 * @return array
	 */
	public function __sleep() {

		return array(
			'__data_model_current_M_model_class_name',
			'__data_model_current_N_model_class_name',
			'__data_model_current_M_model_name',
			'__data_model_current_N_model_name',
			'M_ID'
		);
	}

	public function __wakeup() {
	}

}