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
     * @var array
     */
    protected $load_realted_data_order_by = array();

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
    public function setLoadRealtedDataOrderBy( array $order_by)
    {
        $this->load_realted_data_order_by = $order_by;
    }

    /**
     * @return array
     */
    public function getLoadRealtedDataOrderBy()
    {
        /**
         * @var DataModel_Definition_Model_Related_1toN $data_model_definition
         */
        $data_model_definition = $this->getDataModelDefinition();

        return $this->load_realted_data_order_by ? $this->load_realted_data_order_by : $data_model_definition->getDefaultOrderBy();
    }


    /**
     * @return array
     */
    public function loadRelatedData() {

        $query = $this->getLoadRelatedDataQuery();

        $order_by = $this->getLoadRealtedDataOrderBy();
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

        /**
         * @var DataModel_Definition_Model_Related_1toN $data_model_definition
         */
        $data_model_definition = $this->getDataModelDefinition();

        $parent_ID_values = array();
        if($this->__parent_model_instance) {
            $parent_ID = $this->__parent_model_instance->getID();

            foreach( $data_model_definition->getParentModelRelationIDProperties() as $property ) {

                /**
                 * @var DataModel_Definition_Property_Abstract $property
                 */
                $parent_ID_values[$property->getName()] = $parent_ID[$property->getRelatedToPropertyName()];

            }
        }


        $model_name = $data_model_definition->getModelName();
        $items = array();

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
            $loaded_instance = static::createInstanceFromData( $dat );
            $loaded_instance->setupParentObjects($this->__main_model_instance, $this->__parent_model_instance);
            $loaded_instance->initRelatedProperties( $loaded_related_data );


            unset($loaded_related_data[$model_name][$i]);

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
    public function getRelatedFormFields( DataModel_Definition_Property_Abstract $parent_property_definition, array $properties_list ) {
        /**
         * @var Form $related_form
         */
        $related_form = $this->getForm('', $properties_list);

        return $related_form;
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