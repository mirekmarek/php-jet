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

class DataModel_Definition_Model_Related_Abstract extends DataModel_Definition_Model_Abstract {

	/**
	 *
	 * @var bool
	 */
	protected $_is_sub_related_model = false;

	/**
	 *
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $_main_model_relation_ID_properties = array();

	/**
	 *
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $_parent_model_relation_ID_properties = array();

	/**
	 *
	 * @var DataModel_Definition_Model_Main
	 */
	protected $_main_model_definition = null;

	/**
	 *
	 * @var DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN
	 */
	protected $_parent_related_model_definition = null;


	/**
	 * @param DataModel $data_model
	 * @param DataModel_Definition_Model_Abstract $parent_related_model_definition
	 */
	public function  __construct(
		DataModel $data_model,
		DataModel_Definition_Model_Abstract $parent_related_model_definition
	) {

		$properties_definition_data = $this->_mainInit($data_model);

		$this->_parent_related_model_definition = $parent_related_model_definition;

		$main_model_definition = $parent_related_model_definition;

		while( !($main_model_definition instanceof DataModel_Definition_Model_Main) ) {
			/**
			 * Temporary ... Traversing and seeking for main model
			 *
			 * @var DataModel_Definition_Model_Related_1to1 $main_model_definition
			 */
			$main_model_definition = $main_model_definition->getParentRelatedModelDefinition();

			$this->_is_sub_related_model = true;
		}

		$this->_main_model_definition = $main_model_definition;

		$this->_definePropertiesAndSetupRelations( $properties_definition_data );
	}

	/**
	 *
	 * @param array $properties_definition_data
	 */
	protected function _definePropertiesAndSetupRelations( array $properties_definition_data ) {
		$properties = $this->_mainPropertiesInit($properties_definition_data);

		$main_ID_properties = $this->_main_model_definition->getIDProperties();

		foreach( $main_ID_properties as $main_ID_property ) {
			/**
			 * @var DataModel_Definition_Property_Abstract $main_ID_property
			 */

			$relation_ID_property_name = DataModel::getRelationIDPropertyName( $this->_main_model_definition, $main_ID_property );

			$relation_ID_property = DataModel_Factory::getPropertyDefinitionInstance(
				$this,
				$relation_ID_property_name,
				array(
					'type' => $main_ID_property->getType(),
					'is_ID' => true
				)
			);

			DataModel_Definition_Property_Abstract::cloneProperty( $main_ID_property, $relation_ID_property );

			$relation_ID_property->setUpRelation($main_ID_property);

			$this->_main_model_relation_ID_properties[$relation_ID_property_name] = $relation_ID_property;

		}

		if($this->_is_sub_related_model) {
			$parent_ID_properties = $this->_parent_related_model_definition->getIDProperties();

			foreach( $parent_ID_properties as $parent_ID_property ) {
				/**
				 * @var DataModel_Definition_Property_Abstract $parent_ID_property
				 */

				$relation_ID_property_name = DataModel::getRelationIDPropertyName(
					$this->_parent_related_model_definition,
					$parent_ID_property
				);

				$relation_ID_property = DataModel_Factory::getPropertyDefinitionInstance(
					$this,
					$relation_ID_property_name,
					array(
						'type' => $parent_ID_property->getType(),
						'is_ID' => true
					)
				);

				DataModel_Definition_Property_Abstract::cloneProperty( $parent_ID_property, $relation_ID_property );

				$relation_ID_property->setUpRelation($parent_ID_property);

				$this->_parent_model_relation_ID_properties[$relation_ID_property_name] = $relation_ID_property;
			}
		}


		//We must secure the proper position. IDs, relation IDs and other ...
		foreach($this->ID_properties as $property) {
			$this->properties[$property->getName()] = $property;
		}

		foreach($this->_main_model_relation_ID_properties as $property) {
			$this->properties[$property->getName()] = $property;
		}

		foreach($this->_parent_model_relation_ID_properties as $property) {
			$this->properties[$property->getName()] = $property;
		}

		foreach($properties as $property) {
			/**
			 * @var DataModel_Definition_Property_Abstract $property
			 */
			$this->properties[$property->getName()] = $property;
		}

	}

	/**
	 *
	 * @return DataModel_Definition_Property_Abstract[]
	 */
	public function getMainModelRelationIDProperties() {
		return $this->_main_model_relation_ID_properties;
	}

	/**
	 *
	 * @return DataModel_Definition_Property_Abstract[]
	 */
	public function getParentModelRelationIDProperties() {
		return $this->_parent_model_relation_ID_properties;
	}

	/**
	 *
	 * @return DataModel_Definition_Model_Main
	 */
	public function getMainModelDefinition() {
		return $this->_main_model_definition;
	}

	/**
	 *
	 * @return DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN
	 */
	public function getParentRelatedModelDefinition() {
		return $this->_parent_related_model_definition;
	}
}