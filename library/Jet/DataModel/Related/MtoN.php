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
	 * @param $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Related_Abstract
	 */
	public static function _getDataModelDefinitionInstance( $data_model_class_name ) {
		return new DataModel_Definition_Model_Related_MtoN( $data_model_class_name );
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

		if(!$this->getDataModelDefinition()->getRelatedModelDefinition($M_model_name)  ) {
			throw new DataModel_Exception(
				'Class \''.get_class($M_instance).'\' (model name: \''.$M_model_name.'\') is not related to \''.get_class($this).'\'  (class: \''.get_called_class().'\') ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		if(
			$M_model_name==$this->__data_model_current_M_model_name
		) {
			$this->M_ID = $M_instance->getID();
			return;
		}


		$N_model_name = $this->getDataModelDefinition()->getNModelName($M_model_name);


		$this->__data_model_current_M_model_name = $M_model_name;
		$this->__data_model_current_N_model_name = $N_model_name;


		$this->__data_model_current_M_model_class_name = $this->getDataModelDefinition()->getRelatedModelDefinition($M_model_name)->getClassName();
		$this->__data_model_current_N_model_class_name = $this->getDataModelDefinition()->getRelatedModelDefinition($N_model_name)->getClassName();

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

		foreach($N_instances as $N) {
            $this->offsetSet( $N->getID()->toString(), $N );
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
	 * @param DataModel $main_model_instance
	 * @param DataModel_Related_Abstract $parent_model_instance
	 */
	public function wakeUp( DataModel $main_model_instance, DataModel_Related_Abstract $parent_model_instance=null  ) {

		if($parent_model_instance) {
			$M = $parent_model_instance;
		} else {
			$M = $main_model_instance;
		}

		$this->setMRelatedModel( $M );
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

		$this->_fetchNData();

		$definition = $this->getDataModelDefinition();
		$backend = $this->getBackendInstance();

		$q_M = $this->_getMIdQuery();

		$backend->delete( $q_M );

		if(!$this->N_IDs) {
			return;
		}

		$M_ID_properties = $this->getDataModelDefinition()->getRelationIDProperties($this->__data_model_current_M_model_name);
		$N_ID_properties = $this->getDataModelDefinition()->getRelationIDProperties($this->__data_model_current_N_model_name);

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
		$this->_fetchNData();

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

		$this->_fetchNData();

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
		$query = new DataModel_Query( $this->getDataModelDefinition() );
		$query->setWhere(array());
		$where = $query->getWhere();


		$M_ID_properties = $this->getDataModelDefinition()->getRelationIDProperties($this->__data_model_current_M_model_name);
		$N_ID_properties = $this->getDataModelDefinition()->getRelationIDProperties($this->__data_model_current_N_model_name);

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
		$this->_fetchNData();
		return count($this->N_IDs);
	}

	/**
	 * @see ArrayAccess
	 * @param int $offset
     *
	 * @return bool
	 */
	public function offsetExists( $offset  ) {
		$this->_fetchNData();
		return isset($this->N_IDs[(string)$offset]);
	}
	/**
	 * @see ArrayAccess
	 * @param string $offset
	 *
	 * @return DataModel
	 */
	public function offsetGet( $offset ) {
		$this->_fetchNData();
		return $this->_get($this->N_IDs[(string)$offset]);
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
        Factory::getInstance($this->__data_model_current_N_model_class_name);

        $valid_class_name = Factory::getClassName( $this->__data_model_current_N_model_class_name );

        if(!is_object($value)) {
            throw new DataModel_Exception(
                'Value instance must be instance of \''.$valid_class_name.'\'.'
            );

        }

        /**
         * @var DataModel $value
         */
        if(! ($value instanceof $valid_class_name) ) {
            throw new DataModel_Exception(
                'Value instance must be instance of \''.$valid_class_name.'\'. \''.get_class($value).'\' given '
            );
        }

        if(!$value->getIsSaved()) {
            throw new DataModel_Exception(
                'Object instance must be saved '
            );
        }

        $ID = $value->getID();
        $s_ID = $ID->toString();

        if( $s_ID!=$offset ) {
            throw new DataModel_Exception(
                'The offset must equal object ID (offset is: \''.$offset.'\', ID is \''.$s_ID.'\') '
            );
        }

        $this->N_IDs[$s_ID] = $ID;
        $this->N_data[$s_ID] = $value;

	}

	/**
	 * @see ArrayAccess
	 * @param string $offset
	 */
	public function offsetUnset( $offset )	{
		$this->_fetchNData();
		unset( $this->N_IDs[(string)$offset] );
        unset( $this->N_data[(string)$offset] );
	}

	/**
	 * @see Iterator
	 *
	 * @return DataModel
	 */
	public function current() {
		$this->_fetchNData();

		return $this->_get( current($this->N_IDs) );
	}
	/**
	 * @see Iterator
	 *
	 * @return string
	 */
	public function key() {
		$this->_fetchNData();
		return key($this->N_IDs);
	}
	/**
	 * @see Iterator
	 */
	public function next() {
		$this->_fetchNData();
		return next($this->N_IDs);
	}
	/**
	 * @see Iterator
	 */
	public function rewind() {
		$this->_fetchNData();
		reset($this->N_IDs);
	}
	/**
	 * @see Iterator
	 * @return bool
	 */
	public function valid()	{
		$this->_fetchNData();
		return key($this->N_IDs)!==null;
	}


	/**
	 * Fetches IDs...
	 *
	 */
	protected function _fetchNData() {
		if($this->N_IDs!==null) {
			return;
		}

		$N_ID_properties = $this->getDataModelDefinition()->getRelationIDProperties($this->__data_model_current_N_model_name);


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

            $s_N_ID = (string)$N_ID;

			$this->N_IDs[$s_N_ID] = $N_ID;
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
        	return array();
	}

	public function __wakeup() {
	}

}