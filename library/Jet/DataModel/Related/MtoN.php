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


/**
 * Available annotation:
 *
 * @JetDataModel:default_order_by = ['property_name','-next_property_name', '+some_property_name']
 */

/**
 *
 * @JetDataModel:ID_class_name = 'DataModel_ID_Passive'
 */
abstract class DataModel_Related_MtoN extends DataModel_Related_Abstract {

	/**
	 * @return DataModel_Related_Interface
	 */
	public function createNewRelatedDataModelInstance()
	{
		$i = new DataModel_Related_MtoN_Iterator( get_called_class() );

		return $i;
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
	 * @param DataModel $main_model_instance
	 * @param DataModel_Related_Abstract $parent_model_instance (optional)
	 *
	 * @throws DataModel_Exception
	 */
	public function setupParentObjects(DataModel $main_model_instance, DataModel_Related_Abstract $parent_model_instance = null)
	{

		$this_main_model_instance = &DataModel_ObjectState::getVar($this, 'main_model_instance');
		$this_main_model_instance = $main_model_instance;

		$this_parent_model_instance = &DataModel_ObjectState::getVar($this, 'parent_model_instance');
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
		$data_model_definition = $this->getDataModelDefinition();

		if(!$data_model_definition->getRelatedModelDefinition($M_model_name)  ) {
			throw new DataModel_Exception(
				'Class \''.get_class($M_instance).'\' (model name: \''.$M_model_name.'\') is not related to \''.get_class($this).'\'  ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}


		$N_model_name = $data_model_definition->getNModelName($M_model_name);

		$this_M_model_name = &DataModel_ObjectState::getVar($this, 'M_model_name' );
		$this_N_model_name = &DataModel_ObjectState::getVar($this, 'N_model_name' );

		$this_M_model_name = $M_model_name;
		$this_N_model_name = $N_model_name;


		$this_M_model_class_name = &DataModel_ObjectState::getVar($this, 'M_model_class_name' );
		$this_N_model_class_name = &DataModel_ObjectState::getVar($this, 'N_model_class_name' );

		$this_M_model_class_name = $data_model_definition->getRelatedModelDefinition($M_model_name)->getClassName();
		$this_N_model_class_name = $data_model_definition->getRelatedModelDefinition($N_model_name)->getClassName();

		$this->_setMDataModelInstance( $M_instance );

	}



	/**
	 * @return null
	 */
	public function getArrayKeyValue() {
		return $this->getNID()->toString();
	}


	/**
	 * @return array
	 */
	public function loadRelatedData()
	{

		$query = $this->getLoadRelatedDataQuery();

		return $this->getBackendInstance()->fetchAll( $query );
	}

	/**
	 * @throws DataModel_Exception
	 * @return DataModel_Query
	 */
	protected function getLoadRelatedDataQuery() {
		$query = new DataModel_Query( $this->getDataModelDefinition() );

		$query->setWhere($this->getLoadRelatedDataWhereQueryPart());

		$where = $query->getWhere();

		/**
		 * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
		 */
		$data_model_definition = $this->getDataModelDefinition();

		$this_M_model_name = &DataModel_ObjectState::getVar($this, 'M_model_name' );
		$M_ID_properties = $data_model_definition->getRelationIDProperties($this_M_model_name);

		$this_M_ID = &DataModel_ObjectState::getVar($this, 'M_ID' );

		foreach($M_ID_properties as $M_ID_property) {
			/**
			 * @var DataModel_Definition_Property_Abstract $M_ID_property
			 */
			$value = $this_M_ID[$M_ID_property->getRelatedToPropertyName()];

			if($value===null)  {
				continue;
			}

			$where->addAND();
			$where->addExpression( $M_ID_property, DataModel_Query::O_EQUAL, $value);
		}

		$query->setSelect( $data_model_definition->getProperties() );

		$this_M_model_class_name = &DataModel_ObjectState::getVar($this, 'M_model_class_name' );

		$relation = $data_model_definition->getRelationToN( $this_M_model_class_name );
		$this_N_model_name = &DataModel_ObjectState::getVar($this, 'N_model_name' );
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
		$this_load_related_data_where_query_part = &DataModel_ObjectState::getVar($this, 'load_related_data_where_query_part' );
		$this_load_related_data_where_query_part = $where;
	}

	/**
	 * @return array
	 */
	protected function getLoadRelatedDataWhereQueryPart() {
		$this_load_related_data_where_query_part = &DataModel_ObjectState::getVar($this, 'load_related_data_where_query_part', [] );

		return $this_load_related_data_where_query_part;
	}

	/**
	 * @param array $order_by
	 */
	public function setLoadRelatedDataOrderBy(array $order_by)
	{
		$this_load_related_data_order_by = &DataModel_ObjectState::getVar($this, 'load_related_data_order_by' );
		$this_load_related_data_order_by = $order_by;
	}

	/**
	 * @return array
	 */
	public function getLoadRelatedDataOrderBy()
	{
		/**
		 * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
		 */
		$data_model_definition = $this->getDataModelDefinition();

		$this_load_related_data_order_by = &DataModel_ObjectState::getVar($this, 'load_related_data_order_by' );

		return $this_load_related_data_order_by ? $this_load_related_data_order_by : $data_model_definition->getDefaultOrderBy();
	}



	/**
	 * @param array &$loaded_related_data
	 * @return mixed
	 */
	public function loadRelatedInstances(array &$loaded_related_data)
	{

		/**
		 * @var DataModel_Definition_Model_Related_1toN $data_model_definition
		 */
		$data_model_definition = $this->getDataModelDefinition();


		$model_name = $data_model_definition->getModelName();
		$items = [];

		if(empty($loaded_related_data[$model_name])) {
			return $items;
		}

		$this_main_model_instance = &DataModel_ObjectState::getVar($this, 'main_model_instance');
		$this_parent_model_instance = &DataModel_ObjectState::getVar($this, 'parent_model_instance');
		$this_M_model_class_name = &DataModel_ObjectState::getVar($this, 'M_model_class_name' );
		$this_N_model_class_name = &DataModel_ObjectState::getVar($this, 'N_model_class_name' );
		$this_M_model_name = &DataModel_ObjectState::getVar($this, 'M_model_name' );
		$this_N_model_name = &DataModel_ObjectState::getVar($this, 'N_model_name' );

		foreach( $loaded_related_data[$model_name] as $i=>$dat ) {

			/**
			 * @var DataModel_Related_MtoN $loaded_instance
			 */
			$loaded_instance = new static();
			$loaded_instance->setupParentObjects($this_main_model_instance, $this_parent_model_instance);
			$loaded_instance->_setRelatedData( $dat );



			$loaded_instance_M_model_class_name = &DataModel_ObjectState::getVar($loaded_instance, 'M_model_class_name' );
			$loaded_instance_N_model_class_name = &DataModel_ObjectState::getVar($loaded_instance, 'N_model_class_name' );

			$loaded_instance_M_model_class_name = $this_M_model_class_name;
			$loaded_instance_N_model_class_name = $this_N_model_class_name;

			$loaded_instance_M_model_name = &DataModel_ObjectState::getVar($loaded_instance, 'M_model_name' );
			$loaded_instance_N_model_name = &DataModel_ObjectState::getVar($loaded_instance, 'N_model_name' );

			$loaded_instance_M_model_name = $this_M_model_name;
			$loaded_instance_N_model_name = $this_N_model_name;

			$this_M_ID = &DataModel_ObjectState::getVar($this, 'M_ID' );
			$loaded_instance_M_ID = &DataModel_ObjectState::getVar($loaded_instance, 'M_ID' );

			$loaded_instance_M_ID = $this_M_ID;

			$this_M_instance = &DataModel_ObjectState::getVar($this, 'M_instance' );
			$loaded_instance_M_instance = &DataModel_ObjectState::getVar($loaded_instance, 'M_instance' );

			$loaded_instance_M_instance = $this_M_instance;

			unset($loaded_related_data[$model_name][$i]);

			$key = $loaded_instance->getArrayKeyValue();
			if(is_object($key)) {
				$key = (string)$key;
			}

			if($key!==null) {
				$items[$key] = $loaded_instance;
			} else {
				$items[] = $loaded_instance;
			}

		}

		return $items;
	}

	/**
	 * @param DataModel $M_instance
	 */
	public function _setMDataModelInstance( DataModel $M_instance ) {

		$this_M_instance = &DataModel_ObjectState::getVar($this, 'M_instance' );
		$this_M_ID = &DataModel_ObjectState::getVar($this, 'M_ID' );

		$this_M_instance = $M_instance;
		$this_M_ID = $M_instance->getID();

		/**
		 * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
		 */
		$data_model_definition = $this->getDataModelDefinition();

		$this_M_model_name = &DataModel_ObjectState::getVar($this, 'M_model_name' );

		$ID_properties = $data_model_definition->getRelationIDProperties($this_M_model_name);

		$this_M_ID = &DataModel_ObjectState::getVar($this, 'M_ID' );

		foreach($ID_properties as $ID_property) {
			/**
			 * @var DataModel_Definition_Property_Abstract $ID_property
			 */

			if(!isset($this_M_ID[$ID_property->getRelatedToPropertyName()])) {
				continue;
			}

			$ID_value = $this_M_ID[$ID_property->getRelatedToPropertyName()];

			if(
				$this->getIsSaved() &&
				$this->{$ID_property->getName()} != $ID_value
			) {
				$this->setIsNew();
			}

			$this->{$ID_property->getName()} = $ID_value;

		}

	}

	/**
	 * @param DataModel $N_instance
	 */
	public function _setNDataModelInstance( DataModel $N_instance ) {
		$this_N_instance = &DataModel_ObjectState::getVar($this, 'N_instance' );
		$this_N_ID = &DataModel_ObjectState::getVar($this, 'N_ID' );

		$this_N_instance = $N_instance;
		$this_N_ID = $N_instance->getID();

		/**
		 * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
		 */
		$data_model_definition = $this->getDataModelDefinition();

		$this_N_model_name = &DataModel_ObjectState::getVar($this, 'N_model_name' );
		$ID_properties = $data_model_definition->getRelationIDProperties($this_N_model_name);


		foreach($ID_properties as $ID_property) {
			/**
			 * @var DataModel_Definition_Property_Abstract $ID_property
			 */

			if(!isset($this_N_ID[$ID_property->getRelatedToPropertyName()])) {
				continue;
			}

			$ID_value = $this_N_ID[$ID_property->getRelatedToPropertyName()];

			if(
				$this->getIsSaved() &&
				$this->{$ID_property->getName()} != $ID_value
			) {
				$this->setIsNew();
			}

			$this->{$ID_property->getName()} = $ID_value;

		}

	}

	/**
	 * @return DataModel|null
	 */
	public function getInstanceOfN() {
		$this_N_instance = &DataModel_ObjectState::getVar($this, 'N_instance' );

		if(!$this_N_instance) {

			$this_N_model_class_name = &DataModel_ObjectState::getVar($this, 'N_model_class_name' );

			$n_class_name = Factory::getClassName(  $this_N_model_class_name  );

			/** @noinspection PhpUndefinedMethodInspection */
			$this_N_instance = $n_class_name::load($this->getNID());

		}

		return $this_N_instance;
	}

	/**
	 * @return DataModel_ID_Abstract
	 */
	public function getNID() {
		$this_N_ID = &DataModel_ObjectState::getVar($this, 'N_ID' );

		if(!$this_N_ID) {
			/**
			 * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
			 */
			$data_model_definition = $this->getDataModelDefinition();

			/**
			 * @var DataModel_Definition_Property_Abstract[] $N_ID_properties
			 */
			$this_N_model_name = &DataModel_ObjectState::getVar($this, 'N_model_name' );
			$N_ID_properties = $data_model_definition->getRelationIDProperties($this_N_model_name);

			$this_N_model_class_name = &DataModel_ObjectState::getVar($this, 'N_model_class_name' );
			$n_class_name = Factory::getClassName(  $this_N_model_class_name  );

			/**
			 * @var DataModel $N_model_instance
			 */
			$N_model_instance = new $n_class_name();
			$this_N_ID = $N_model_instance->getEmptyIDInstance();

			foreach( $N_ID_properties as $N_ID_prop_name=>$N_ID_prop) {
				$this_N_ID[$N_ID_prop->getRelatedToPropertyName()] = $this->{$N_ID_prop_name};
			}

		}

		return $this_N_ID;
	}




	/**
	 * @param string $prefix
	 *
	 * @return string
	 */
	public function XMLSerialize($prefix='' ) {
		$this_N_model_name = &DataModel_ObjectState::getVar($this, 'N_model_name' );
		$N_class_name = $this_N_model_name;

		$result = '';

			$result .= $prefix . JET_TAB.'<'.$N_class_name.'>'.JET_EOL;
			foreach($this->getNID() as $ID_k=>$ID_v) {
				$result .= $prefix . JET_TAB.JET_TAB.'<'.$ID_k.'>'.Data_Text::htmlSpecialChars($ID_v).'</'.$ID_k.'>'.JET_EOL;
			}
			$result .= $prefix . JET_TAB.'</'.$N_class_name.'>'.JET_EOL;

		return $result;

	}

	/**
	 *
	 */
	public function __wakeup_relatedItems() {
	}

	/** @noinspection PhpMissingParentCallMagicInspection
	 *
	 * @return array
	 */
	public function __sleep() {
		return [];
	}

	/** @noinspection PhpMissingParentCallMagicInspection
	 *
	 */
	public function __wakeup() {
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

}