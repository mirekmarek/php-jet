<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2015 Miroslav Marek <mirek.marek.2m@gmail.com>
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
 * Class DataModel_Related_1toN
 */
abstract class DataModel_Related_1toN extends DataModel_Related_Abstract {


    /**
     * @return DataModel_Related_Interface
     */
    public function createNewRelatedDataModelInstance() {
        $i = new DataModel_Related_1toN_Iterator( get_called_class() );

        return $i;
    }

	/**
	 * @param $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Related_Abstract
	 */
	public static function _getDataModelDefinitionInstance( $data_model_class_name ) {
		return new DataModel_Definition_Model_Related_1toN( $data_model_class_name );
	}

    /**
     * @return array
     */
    public function loadRelatedData() {
        $data_model_definition = $this->getDataModelDefinition();

        $query = $this->getLoadRelatedDataQuery();

        $order_by = $data_model_definition->getDefaultOrderBy();
        if($order_by) {
            $query->setOrderBy( $order_by );
        }

        return $this->getBackendInstance()->fetchAll( $query );
    }

    /**
     * @param array &$loaded_related_data
     * @return mixed
     */
    public function createRelatedInstancesFromLoadedRelatedData( array &$loaded_related_data ) {

        $items = array();

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
                 * @var DataModel_Related_1toN $loaded_instance
                 */
                $loaded_instance = static::createInstanceFromData( $dat );
                $loaded_instance->setupParentObjects($this->__main_model_instance, $this->__parent_model_instance);
                $loaded_instance->initRelatedProperties( $loaded_related_data );


                unset($loaded_related_data[$class_name][$i]);

                /**
                 * @var DataModel_Related_1toN $loaded_instance
                 */
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
        }

        return $items;
    }



	/**
	 * @return mixed|null
	 */
	public function getArrayKeyValue() {
		return null;
	}

    /**
     * @param string $parent_field_name
     * @param string$related_form_getter_method_name
     *
     * @return Form_Field_Abstract[]
     */
    public function getRelatedFormFields( $parent_field_name, $related_form_getter_method_name ) {
        return array();
    }

    /**
     * @param array $values
     *
     * @return bool
     */
    public function catchRelatedForm( array $values ) {
        return false;
    }

}