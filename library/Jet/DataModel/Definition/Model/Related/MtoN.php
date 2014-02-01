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


class DataModel_Definition_Model_Related_MtoN extends DataModel_Definition_Model_Abstract {

	/**
	 * @var string
	 */
	protected $M_related_model_class_name = '';


	/**
	 * @var string
	 */
	protected $N_related_model_class_name = '';

	/**
	 *
	 * @var DataModel_Definition_Model_Abstract
	 */
	protected $M_related_model_definition;

	/**
	 *
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $M_model_relation_ID_properties = array();

	/**
	 *
	 * @var DataModel_Definition_Model_Abstract
	 */
	protected $N_related_model_definition;

	/**
	 *
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $N_model_relation_ID_properties = array();


	/**
	 * @var DataModel_Definition_Relation_JoinBy_Item[]
	 */
	protected $M_model_relation_join_items = array();

	/**
	 * @var DataModel_Definition_Relation_JoinBy_Item[]
	 */
	protected $N_model_relation_join_items = array();


	/**
	 *
	 * @param string $data_model_class_name
	 *
	 * @throws DataModel_Exception
	 *
	 */
	public function  __construct( $data_model_class_name ) {

		$M_model_class_name = $data_model_class_name::getDataModelDefinitionMModelClassName();
		if(!$M_model_class_name) {
			throw new DataModel_Exception(
				$data_model_class_name.' @JetDataModel:M_model_class_name is not defined!',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$this->M_related_model_class_name = $M_model_class_name;

		$N_model_class_name = $data_model_class_name::getDataModelDefinitionNModelClassName();
		if(!$N_model_class_name) {
			throw new DataModel_Exception(
				$data_model_class_name.' @JetDataModel:N_model_class_name is not defined!',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$this->N_related_model_class_name = $N_model_class_name;

		$M_related_model_definition = $M_model_class_name::getDataModelDefinition();
		$N_related_model_definition = $N_model_class_name::getDataModelDefinition();

		$this->class_name = $data_model_class_name;

		$this->model_name = $data_model_class_name::getDataModelName();

		if(
			!is_string($this->model_name) ||
			!$this->model_name
		) {
			throw new DataModel_Exception(
					'DataModel \''.$data_model_class_name.'\' doesn\'t have model name! ('.$data_model_class_name.'::getModelName() returns false.) ',
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


		$this->setupRelation($M_related_model_definition, $N_related_model_definition);

		$properties_definition_data = $data_model_class_name::getDataModelPropertiesDefinitionData();
		if(!$properties_definition_data) {
			$properties_definition_data = array();
		}

		$this->_definePropertiesAndSetupRelations( $properties_definition_data );
	}

	/**
	 *
	 * @param array $properties_definition_data
	 */
	protected function _definePropertiesAndSetupRelations( array $properties_definition_data ) {
	}

	/**
	 * @param string $parent_model_class_name
	 *
	 * @return DataModel_Definition_Relation_Internal[]
	 */
	public function getInternalRelations( $parent_model_class_name ) {
		$definition_M_data_model_class_name = $this->getMRelatedModelClassName();
		$definition_N_data_model_class_name = $this->getNRelatedModelClassName();

		/** @noinspection PhpUndefinedVariableInspection */
		if(
			$parent_model_class_name!=$definition_M_data_model_class_name &&
			$parent_model_class_name!=$definition_N_data_model_class_name &&
			!is_subclass_of($parent_model_class_name, $definition_M_data_model_class_name) &&
			!is_subclass_of($parent_model_class_name, $definition_N_data_model_class_name)
		) {
			//TODO: vynadat
			die("!");
		}

		if( $parent_model_class_name==$definition_N_data_model_class_name ) {
			$_tmp = $definition_N_data_model_class_name;
			$definition_N_data_model_class_name = $definition_M_data_model_class_name;
			$definition_M_data_model_class_name = $_tmp;

		}

		$N_model_definition = DataModel::getDataModelDefinition( $definition_N_data_model_class_name );
		$M_model_definition = DataModel::getDataModelDefinition( $definition_M_data_model_class_name );

		$this->setupRelation( $M_model_definition, $N_model_definition );

		$relations = array();


		$main_glue_relation_join_by = $this->M_model_relation_join_items;
		$relations[ $this->getModelName() ] = new DataModel_Definition_Relation_Internal( $this, $main_glue_relation_join_by );

		$glue_n_relation_join_by = $this->N_model_relation_join_items;
		$relations[ $N_model_definition->getModelName() ] = new DataModel_Definition_Relation_Internal( $N_model_definition, $glue_n_relation_join_by );
		$relations[ $N_model_definition->getModelName() ]->setRequiredRelations( array( $this->getModelName() ) );


		return $relations;
	}


	/**
	 * @param DataModel_Definition_Model_Abstract $M_related_model_definition
	 * @param DataModel_Definition_Model_Abstract $N_related_model_definition
	 */
	public function setupRelation(
				DataModel_Definition_Model_Abstract $M_related_model_definition,
				DataModel_Definition_Model_Abstract $N_related_model_definition
			) {
		$this->M_related_model_definition = $M_related_model_definition;
		$this->N_related_model_definition = $N_related_model_definition;


		$this->M_model_relation_ID_properties = array();
		$this->N_model_relation_ID_properties = array();
		$this->M_model_relation_join_items = array();
		$this->N_model_relation_join_items = array();

		$ID_properties = $this->M_related_model_definition->getIDProperties();

		foreach( $ID_properties as $ID_property_definition ) {

			$relation_ID_property_name = DataModel::getRelationIDPropertyName(
							$this->M_related_model_definition,
							$ID_property_definition
						);

			$relation_ID_property = DataModel_Factory::getPropertyDefinitionInstance(
							$this,
							$relation_ID_property_name,
							array(
								'type' => $ID_property_definition->getType(),
								'is_ID' => true
							)
					);

			//DataModel_Definition_Property_Abstract::cloneProperty( $ID_property_definition, $relation_ID_property );

			$this->M_model_relation_join_items[] = new DataModel_Definition_Relation_JoinBy_Item( $relation_ID_property, $ID_property_definition );

			//TODO: pryc
			$relation_ID_property->setUpRelation($ID_property_definition);

			$this->M_model_relation_ID_properties[$relation_ID_property_name] = $relation_ID_property;
		}

		$ID_properties = $this->N_related_model_definition->getIDProperties();

		foreach( $ID_properties as $ID_property_definition ) {
			/**
			 * @var DataModel_Definition_Property_Abstract $ID_property_definition
			 */

			$relation_ID_property_name = DataModel::getRelationIDPropertyName(
							$this->N_related_model_definition,
							$ID_property_definition
						);

			$relation_ID_property = DataModel_Factory::getPropertyDefinitionInstance(
							$this,
							$relation_ID_property_name,
							array(
								'type' => $ID_property_definition->getType(),
								'is_ID' => true
							)
					);

			//DataModel_Definition_Property_Abstract::cloneProperty( $ID_property_definition, $relation_ID_property );

			$this->N_model_relation_join_items[] = new DataModel_Definition_Relation_JoinBy_Item( $relation_ID_property, $ID_property_definition );

			//TODO: pryc
			$relation_ID_property->setUpRelation($ID_property_definition);

			$this->N_model_relation_ID_properties[$relation_ID_property_name] = $relation_ID_property;
		}

		foreach($this->M_model_relation_ID_properties as $ID_property) {
			/**
			 * @var DataModel_Definition_Property_Abstract $ID_property
			 */
			$this->properties[$ID_property->getName()] = $ID_property;
		}

		foreach($this->N_model_relation_ID_properties as $ID_property) {
			/**
			 * @var DataModel_Definition_Property_Abstract $ID_property
			 */
			$this->properties[$ID_property->getName()] = $ID_property;
		}

	}

	/**
	 * @return string
	 */
	public function getMRelatedModelClassName() {
		return $this->M_related_model_class_name;
	}

	/**
	 * @return string
	 */
	public function getNRelatedModelClassName() {
		return $this->N_related_model_class_name;
	}


	/**
	 * @return DataModel_Definition_Model_Abstract
	 */
	public function getMRelatedModelDefinition() {
		return $this->M_related_model_definition;
	}

	/**
	 * @return DataModel_Definition_Model_Abstract
	 */
	public function getNRelatedModelDefinition() {
		return $this->N_related_model_definition;
	}

	/**
	 * @return array|DataModel_Definition_Property_Abstract[]
	 */
	public function getMModelRelationIDProperties() {
		return $this->M_model_relation_ID_properties;
	}

	/**
	 * @return array|DataModel_Definition_Property_Abstract[]
	 */
	public function getNModelRelationIDProperties() {
		return $this->N_model_relation_ID_properties;
	}

}