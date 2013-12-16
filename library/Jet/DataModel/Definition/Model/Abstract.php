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
	protected $model_name = "";

	/**
	 * DataModel class name
	 *
	 * @var string
	 */
	protected $class_name = "";

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
	 * @var DataModel_Query_Relation_Outer[]
	 */
	protected $outer_relations = array();


	/**
	 *
	 * @param DataModel $data_model
	 */
	public function  __construct( DataModel $data_model ) {
		$properties_definition_data = $this->_mainInit($data_model);
		$this->_definePropertiesAndSetupRelations( $properties_definition_data );
	}

	/**
	 * @param DataModel $data_model
	 *
	 * @return array
	 * @throws DataModel_Exception
	 */
	protected function _mainInit( DataModel $data_model ) {

		$class = get_class($data_model);

		$this->class_name = $class;

		$this->model_name = $data_model->getDataModelName();

		if(
			!is_string($this->model_name) ||
			!$this->model_name
		) {
			throw new DataModel_Exception(
					"DataModel '{$class}' doesn't have model name! ({$class}::getDataModelName() returns false.) ",
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
		}

		$properties_definition_data = $data_model->getDataModelPropertiesDefinitionData();
		
		if(
			!is_array($properties_definition_data) ||
			!$properties_definition_data
		) {
			throw new DataModel_Exception(
					"DataModel '{$class}' doesn't have properties definition! ({$class}::getPropertiesDefinition() returns false.) ",
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
		}



		return $properties_definition_data;
	}

	/**
	 *
	 * @param array $properties_definition_data
	 */
	protected function _definePropertiesAndSetupRelations( array $properties_definition_data ) {
		$properties = $this->_mainPropertiesInit($properties_definition_data);

		//We must secure the proper position. IDs, relation IDs and other ...
		foreach($this->ID_properties as $ID_property) {
			$this->properties[$ID_property->getName()] = $ID_property;
		}

		foreach($properties as $property) {
			$this->properties[$property->getName()] = $property;
		}
	}

	/**
	 * @param array $properties_definition_data
	 *
	 * @throws DataModel_Exception
	 * @return DataModel_Definition_Property_Abstract[]
	 */
	protected function _mainPropertiesInit( array $properties_definition_data ) {

		$properties = array();

		$has_ID_property = false;

		foreach( $properties_definition_data as $property_name=>$property_dd ) {
			$property_definition = DataModel_Factory::getPropertyDefinitionInstance($this, $property_name, $property_dd);

			if($property_definition->getIsID()) {
				$has_ID_property = true;
				$this->ID_properties[$property_definition->getName()] = $property_definition;
			} else {
				$properties[] = $property_definition;
			}
		}


		if(!$has_ID_property) {
			throw new DataModel_Exception(
				"There are not any ID properties in DataModel '".$this->getClassName()."' definition ...",
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		return $properties;
	}

	/**
	 * @return string
	 */
	public function getModelName() {
		return $this->model_name;
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
}