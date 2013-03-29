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
	 * @return DataModel_Definition_Property_Abstract[]
	 */
	protected function _mainPropertiesInit( array $properties_definition_data ) {

		$properties = array();

		$has_main_ID_property = false;

		foreach( $properties_definition_data as $property_name=>$property_dd ) {
			$property_definition = DataModel_Factory::getPropertyDefinitionInstance($this, $property_name, $property_dd);

			if($property_definition->getIsID()) {
				//There can be many of ID properties, but one with type=TYPE_ID must always exists. Otherwise auto. add ...
				if($property_definition->getType()==DataModel::TYPE_ID) {
					$has_main_ID_property = true;
				}
				$this->ID_properties[$property_definition->getName()] = $property_definition;
			} else {
				$properties[] = $property_definition;
			}
		}


		if(!$has_main_ID_property) {
			//There can be many of ID properties, but one with type=TYPE_ID must always exists. Otherwise auto. add ...
			$main_ID_property = DataModel_Factory::getPropertyDefinitionInstance(
							$this,
							DataModel::DEFAULT_ID_COLUMN_NAME,
							array(
								"type" => DataModel::TYPE_ID
							)
					);

			//ID on beginning
			$_ID_properties = $this->ID_properties;
			$this->ID_properties = array();
			$this->ID_properties[$main_ID_property->getName()] = $main_ID_property;
			foreach($_ID_properties as $pn=>$pd) {
				$this->ID_properties[$pn]  = $pd;
			}
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