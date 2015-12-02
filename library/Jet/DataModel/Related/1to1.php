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


        $parent_ID_values = array();
        if($this->__parent_model_instance) {
            $parent_ID = $this->__parent_model_instance->getID();

            $definition = $this->getDataModelDefinition();
            foreach( $definition->getParentModelRelationIDProperties() as $property ) {

                /**
                 * @var DataModel_Definition_Property_Abstract $property
                 */
                $parent_ID_values[$property->getName()] = $parent_ID[$property->getRelatedToPropertyName()];

            }
        }

        $class_name = get_called_class();
        if(!empty($loaded_related_data[$class_name])) {
            foreach( $loaded_related_data[$class_name] as $i=>$dat ) {
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

                $this->setupParentObjects( $loaded_instance );

                unset($loaded_related_data[$class_name][$i]);

                return $loaded_instance;
            }
        }

        return null;
    }


    /**
     * @param string $parent_field_name
     * @param string$related_form_getter_method_name
     *
     * @return Form_Field_Abstract[]
     */
    public function getRelatedFormFields( $parent_field_name, $related_form_getter_method_name ) {

        $fields = array();

        /**
         * @var Form $related_form
         */
        $related_form = $this->{$related_form_getter_method_name}();

        foreach($related_form->getFields() as $field) {

            if(
                $field instanceof Form_Field_Hidden
            ) {
                continue;
            }

            $field->setName('/'.$parent_field_name.'/'.$field->getName() );

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