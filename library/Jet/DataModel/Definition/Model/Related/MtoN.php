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
	 *
	 * @param DataModel $data_model
	 * @param DataModel_Definition_Model_Abstract $M_related_model_definition
	 * @param DataModel_Definition_Model_Abstract $N_related_model_definition
	 *
	 * @throws DataModel_Exception
	 */
	public function  __construct(
					DataModel $data_model,
					DataModel_Definition_Model_Abstract $M_related_model_definition,
					DataModel_Definition_Model_Abstract $N_related_model_definition
				) {
		
		$class = get_class($data_model);

		$this->class_name = $class;

		$this->model_name = $data_model->getDataModelName();

		if(
			!is_string($this->model_name) ||
			!$this->model_name
		) {
			throw new DataModel_Exception(
					'DataModel \''.$class.'\' doesn\'t have model name! ('.$class.'::getModelName() returns false.) ',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
		}


		$this->setupRelation($M_related_model_definition, $N_related_model_definition);

		$properties_definition_data = $data_model->getDataModelPropertiesDefinitionData();

		$this->_definePropertiesAndSetupRelations( $properties_definition_data );
	}

	/**
	 *
	 * @param array $properties_definition_data
	 */
	protected function _definePropertiesAndSetupRelations( array $properties_definition_data ) {
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