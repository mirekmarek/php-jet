<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
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

trait DataModel_Related_1toN_Trait {
    use DataModel_Related_Trait;



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
         * @var DataModel_Definition_Model_Related_1toN $data_model_definition
         */
        $data_model_definition = $this->getDataModelDefinition();

        $this_load_related_data_order_by = &DataModel_ObjectState::getVar($this, 'load_related_data_order_by' );

        return $this_load_related_data_order_by ? $this_load_related_data_order_by : $data_model_definition->getDefaultOrderBy();
    }


    /**
     * @param array $load_only_related_properties
     * @return mixed
     */
    public function loadRelatedData( array $load_only_related_properties=[] ) {

        $query = $this->getLoadRelatedDataQuery( $load_only_related_properties );

        $order_by = $this->getLoadRelatedDataOrderBy();
        if($order_by) {
            $query->setOrderBy( $order_by );
        }

        return $this->getBackendInstance()->fetchAll( $query );
    }

    /**
     * @param array &$loaded_related_data
     * @return mixed
     */
    public function loadRelatedInstances(array &$loaded_related_data ) {

        /**
         * @var DataModel_Definition_Model_Related_1toN $data_model_definition
         */
        $data_model_definition = $this->getDataModelDefinition();

        /**
         * @var DataModel $this_main_model_instance
         */
        $this_main_model_instance = &DataModel_ObjectState::getVar($this, 'main_model_instance');
        /**
         * @var DataModel_Related_Interface|DataModel_Interface $this_parent_model_instance
         */
        $this_parent_model_instance = &DataModel_ObjectState::getVar($this, 'parent_model_instance');

        $parent_ID_values = [];
        if($this_parent_model_instance) {
            $parent_ID = $this_parent_model_instance->getIdObject();

            foreach( $data_model_definition->getParentModelRelationIDProperties() as $property ) {

                /**
                 * @var DataModel_Definition_Property_Abstract $property
                 */
                $parent_ID_values[$property->getName()] = $parent_ID[$property->getRelatedToPropertyName()];

            }
        }


        $model_name = $data_model_definition->getModelName();
        $items = [];

        if(empty($loaded_related_data[$model_name])) {
            return $items;
        }


        foreach( $loaded_related_data[$model_name] as $i=>$dat ) {
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
            $loaded_instance = new static();
            $loaded_instance->setupParentObjects($this_main_model_instance, $this_parent_model_instance);
            $loaded_instance->_setRelatedData( $dat, $loaded_related_data );


            unset($loaded_related_data[$model_name][$i]);

            $loaded_instance->afterLoad();

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

        return $items;
    }



    /**
     * @return mixed|null
     */
    public function getArrayKeyValue() {
        return null;
    }

    /**
     *
     * @param DataModel_Definition_Property_Abstract $parent_property_definition
     * @param array $properties_list
     *
     * @return Form_Field_Abstract[]
     */
    public function getRelatedFormFields(
        /** @noinspection PhpUnusedParameterInspection */
        DataModel_Definition_Property_Abstract $parent_property_definition,
        array $properties_list
    ) {
        /**
         * @var Form $related_form
         */
        $related_form = $this->getForm('', $properties_list);

        return $related_form->getFields();
    }

    /**
     * @param array $values
     *
     * @return bool
     */
    public function catchRelatedForm(
        /** @noinspection PhpUnusedParameterInspection */
        array $values
    ) {
        return false;
    }

}
