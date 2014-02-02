<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Definition
 */
namespace Jet;

abstract class DataModel_Definition_Model_Abstract extends Object {

	/**
	 * DataModel name
	 *
	 * @var string
	 */
	protected $model_name = '';

	/**
	 * Database table name
	 *
	 * @var string
	 */
	protected $database_table_name = '';

	/**
	 * DataModel class name
	 *
	 * @var string
	 */
	protected $class_name = '';

	/**
	 *
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $ID_properties = array();

	
	/**
	 *
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $properties = array();

	/**
	 * @var DataModel_Definition_Key[]
	 */
	protected $keys = array();

	/**
	 * @var DataModel_Definition_Relation_Abstract[]
	 */
	protected $relations;


	/**
	 *
	 * @param $data_model_class_name
	 *
	 * @internal param string $data_model
	 */
	public function  __construct( $data_model_class_name ) {

		$this->_mainInit( $data_model_class_name );
		$this->_initProperties();
	}

	/**
	 * @param string $data_model_class_name
	 *
	 * @return array
	 * @throws DataModel_Exception
	 */
	protected function _mainInit( $data_model_class_name ) {

		$this->class_name = (string)$data_model_class_name;

		/**
		 * @var DataModel $data_model_class_name
		 */
		$this->model_name = $data_model_class_name::getDataModelName();

		if(
			!is_string($this->model_name) ||
			!$this->model_name
		) {
			throw new DataModel_Exception(
					'DataModel \''.$data_model_class_name.'\' doesn\'t have model name! Please specify it by @JetDataModel:name ',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
		}

		$this->database_table_name = $data_model_class_name::getDbTableName();

		if(
			!is_string($this->database_table_name) ||
			!$this->database_table_name
		) {
			throw new DataModel_Exception(
				'DataModel \''.$data_model_class_name.'\' doesn\'t have database table name! Please specify it by @JetDataModel:database_table_name ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}


	}

	/**
	 *
	 */
	protected function _initProperties() {

		$class_name = $this->class_name;

		/**
		 * @var DataModel $class_name
		 */
		$properties_definition_data = $class_name::getDataModelPropertiesDefinitionData();

		if(
			!is_array($properties_definition_data) ||
			!$properties_definition_data
		) {
			throw new DataModel_Exception(
				'DataModel \''.$class_name.'\' doesn\'t have properties definition! ('.$class_name.'::getPropertiesDefinition() returns false.) ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$this->properties = array();

		$has_ID_property = false;

		foreach( $properties_definition_data as $property_name=>$property_dd ) {
			if(isset($property_dd['related_to'])) {
				$this->_initGlueProperty($property_name, $property_dd['related_to']);
				continue;
			}

			$property_definition = DataModel_Factory::getPropertyDefinitionInstance($this, $property_name, $property_dd);

			if($property_definition->getIsID()) {
				$has_ID_property = true;
				$this->ID_properties[$property_definition->getName()] = $property_definition;
			}

			$property_name = $property_definition->getName();

			$this->properties[$property_name] = $property_definition;

			if( $property_definition->getIsKey() ) {
				$this->addKey(
					$property_name,
					$property_definition->getIsUnique() ? DataModel::KEY_TYPE_UNIQUE : DataModel::KEY_TYPE_INDEX,
					array( $property_name )
				);
			}
		}


		if(!$has_ID_property) {
			throw new DataModel_Exception(
				'There are not any ID properties in DataModel \''.$this->getClassName().'\' definition',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$this->addKey( $this->model_name.'_pk', DataModel::KEY_TYPE_PRIMARY, array_keys($this->ID_properties) );

		$keys_definition_data = $class_name::getDataModelKeysDefinitionData();

		foreach( $keys_definition_data as $kd ) {
			$this->addKey( $kd['name'], $kd['type'], $kd['property_names'] );

		}
	}

	/**
	 * @param string $property_name
	 * @param string $related_to
	 *
	 * @throws DataModel_Exception
	 */
	protected function _initGlueProperty( $property_name, $related_to ) {
		throw new DataModel_Exception(
			'It is not possible to define related property in Main DataModel  (\''.$this->class_name.'\'::'.$property_name.') ',
			DataModel_Exception::CODE_DEFINITION_NONSENSE
		);

	}


	/**
	 * @return string
	 */
	public function getModelName() {
		return $this->model_name;
	}

	/**
	 * @return string
	 */
	public function getDatabaseTableName() {
		return $this->database_table_name;
	}


	
	/**
	 * Returns DataModel class name
	 *
	 * @return string
	 */
	public function getClassName() {
		return $this->class_name;
	}


	/**
	 *
	 * @return DataModel_Definition_Property_Abstract[]
	 */
	public function getIDProperties() {
		return $this->ID_properties;
	}


	/**
	 *
	 * @return DataModel_Definition_Property_Abstract[]
	 */
	public function getProperties() {
		return $this->properties;
	}

	/**
	 *
	 * @throws DataModel_Exception
	 *
	 * @return DataModel_Definition_Relation_Abstract[]
	 */
	public function getRelations() {

		if($this->relations!==null) {
			return $this->relations;
		}

		$this->relations = array();

		$class = $this->class_name;

		/**
		 * @var DataModel $class
		 */
		$relations_definitions_data = $class::getDataModelOuterRelationsDefinitionData();

		foreach( $relations_definitions_data as $definition_data ) {
			$relation = new DataModel_Definition_Relation_External( $definition_data );

			$related_model_name = $relation->getRelatedDataModelName();

			$this->addRelation($related_model_name, $relation);
		}


		foreach( $this->properties as $property ) {
			if(!$property->getIsDataModel()) {
				continue;
			}

			/**
			 * @var DataModel_Definition_Model_Related_Abstract $related_data_model_definition
			 */
			$related_data_model_definition = DataModel::getDataModelDefinition( $property->getDataModelClass() );

			$internal_relations = $related_data_model_definition->getInternalRelations( $this->class_name );

			foreach( $internal_relations as $related_model_name=>$relation ) {
				$this->addRelation($related_model_name, $relation);
			}

			continue;


		}

		return $this->relations;
	}

	/**
	 * @param string $related_model_name
	 * @param DataModel_Definition_Relation_Abstract $relation
	 *
	 * @throws DataModel_Exception
	 */
	protected function addRelation( $related_model_name, DataModel_Definition_Relation_Abstract $relation) {
		if(isset($this->relations[ $related_model_name ])) {
			throw new DataModel_Exception(
				'Class \''.$this->getClassName().'\': duplicit relation \''.$related_model_name.'\' ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$this->relations[$related_model_name] = $relation;

	}

	/**
	 * @return DataModel_Definition_Key[]
	 */
	public function getKeys() {
		return $this->keys;
	}

	/**
	 * @param string $name
	 * @param string $type
	 * @param array $key_properties
	 *
	 * @throws DataModel_Exception
	 */
	public function addKey( $name, $type, array $key_properties ) {

		if(isset($this->keys[$name])) {
			throw new DataModel_Exception(
				'Class \''.$this->getClassName().'\': duplicit key \''.$name.'\' ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$my_properties = $this->getProperties();

		foreach( $key_properties as $property_name ) {
			if(!isset($my_properties[$property_name])) {
				throw new DataModel_Exception(
					'Unknown key property \''.$property_name.'\'. Class: \''.$this->class_name.'\' Key: \''.$name.'\' ',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);

			}
		}

		$this->keys[$name] = new DataModel_Definition_Key( $name, $type, $key_properties );
	}

	/**
	 * Default serialize rules (don't serialize __* properties)
	 *
	 * @return array
	 */
	public function __sleep(){
		$this->getRelations();

		parent::__sleep();
	}


}