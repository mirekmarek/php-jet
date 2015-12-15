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
 * @abstract
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Related
 */
namespace Jet;

abstract class DataModel_Related_1to1 extends DataModel_Related_Abstract {

	/**
	 * @param $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Related_1to1
	 */
	public static function _getDataModelDefinitionInstance( $data_model_class_name ) {
		return new DataModel_Definition_Model_Related_1to1( $data_model_class_name );
	}

	/**
	 * @return array
	 */
	public function loadRelatedData() {

		$query = $this->getLoadRelatedDataQuery();

		return $this->getBackendInstance()->fetchAll( $query );
	}

	/**
	 * @param array &$loaded_related_data
	 * @return mixed
	 */
	public function createRelatedInstancesFromLoadedRelatedData( array &$loaded_related_data ) {

		/**
		 * @var DataModel_Definition_Model_Related_1to1 $definition
		 */
		$definition = $this->getDataModelDefinition();

		$parent_ID_values = array();
		if($this->__parent_model_instance) {
			$parent_ID = $this->__parent_model_instance->getID();

			foreach( $definition->getParentModelRelationIDProperties() as $property ) {

				/**
				 * @var DataModel_Definition_Property_Abstract $property
				 */
				$parent_ID_values[$property->getName()] = $parent_ID[$property->getRelatedToPropertyName()];

			}
		}

		$model_name = $definition->getModelName();
		if(!empty($loaded_related_data[$model_name])) {
			foreach( $loaded_related_data[$model_name] as $i=>$dat ) {
				if($parent_ID_values) {
					foreach($parent_ID_values as $k=>$v) {
						if($dat[$k]!=$v) {
							continue 2;
						}
					}
				}

				/**
				 * @var DataModel_Related_1to1 $loaded_instance
				 */
				$loaded_instance = static::createInstanceFromData( $dat );
				$loaded_instance->setupParentObjects( $this->__main_model_instance, $this->__parent_model_instance );
				$loaded_instance->initRelatedProperties( $loaded_related_data );

				unset($loaded_related_data[$model_name][$i]);

				return $loaded_instance;
			}
		}

		return null;
	}


	/**
	 *
	 * @param DataModel_Definition_Property_Abstract $parent_property_definition
	 * @param array $properties_list
	 *
	 * @return Form_Field_Abstract[]
	 */
	public function getRelatedFormFields( DataModel_Definition_Property_Abstract $parent_property_definition, array $properties_list ) {

		$fields = array();

		/**
		 * @var Form $related_form
		 */
		$related_form = $this->getForm('', $properties_list);

		foreach($related_form->getFields() as $field) {

			$field->setName('/'.$parent_property_definition->getName().'/'.$field->getName() );

			$fields[] = $field;
		}


		return $fields;
	}


	/**
	 * @param array $values
	 *
	 * @return bool
	 */
	public function catchRelatedForm( array $values ) {

		//$r_form = $this->getForm( '', array_keys($values) );
		$r_form = $this->getCommonForm();

		return $this->catchForm( $r_form, $values, true );
	}


}