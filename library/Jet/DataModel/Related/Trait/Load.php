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

trait DataModel_Related_Trait_Load {

    /**
     * @return DataModel_Related_Interface
     */
    public function createNewRelatedDataModelInstance() {
        return new static();
    }

    /**
     *
     * @param array $load_only_related_properties
     * @throws DataModel_Exception
     * @return DataModel_Query
     */
    protected function getLoadRelatedDataQuery(array $load_only_related_properties=[]) {

        /**
         * @var DataModel_Interface|DataModel_Related_Interface $this
         * @var DataModel_Definition_Model_Related_Abstract $data_model_definition
         */
        $data_model_definition = $this->getDataModelDefinition();

        $query = new DataModel_Query( $data_model_definition );

        if(!$load_only_related_properties) {
            $select = $data_model_definition->getProperties();
        } else {
            $select = [];

            foreach( $load_only_related_properties as $lp ) {

                list($model_name, $property_name) = explode('.', $lp);

                if($model_name!=$data_model_definition->getModelName()) {
                    continue;
                }

                $select[] = $data_model_definition->getProperty($property_name);
            }
            $select = [];
        }

        $query->setSelect( $select );
        $query->setWhere([]);

        $where = $query->getWhere();

        /**
         * @var DataModel $this_main_model_instance
         */
        $this_main_model_instance = &DataModel_ObjectState::getVar($this, 'main_model_instance');
        /**
         * @var DataModel_Interface $this_parent_model_instance
         */
        $this_parent_model_instance = &DataModel_ObjectState::getVar($this, 'parent_model_instance');

        if( $this_main_model_instance ) {
            $main_model_ID = $this_main_model_instance->getIdObject();

            foreach( $data_model_definition->getMainModelRelationIDProperties() as $property ) {
                /**
                 * @var DataModel_Definition_Property_Abstract $property
                 */
                $property_name = $property->getRelatedToPropertyName();
                $value = $main_model_ID[ $property_name ];

                $where->addAND();
                $where->addExpression(
                    $property,
                    DataModel_Query::O_EQUAL,
                    $value

                );

            }
        } else {
            if( $this_parent_model_instance ) {
                $parent_model_ID = $this_parent_model_instance->getIdObject();

                foreach( $data_model_definition->getParentModelRelationIDProperties() as $property ) {
                    /**
                     * @var DataModel_Definition_Property_Abstract $property
                     */
                    $property_name = $property->getRelatedToPropertyName();
                    $value = $parent_model_ID[ $property_name ];

                    $where->addAND();
                    $where->addExpression(
                        $property,
                        DataModel_Query::O_EQUAL,
                        $value

                    );

                }

            } else {
                throw new DataModel_Exception('Parents are not set!');
            }
        }

        return $query;
    }

    /**
     * @param array &$loaded_related_data
     * @return mixed
     */
    protected function initRelatedProperties( array &$loaded_related_data ) {
        /**
         * @var DataModel_Interface|DataModel_Related_Interface $this
         * @var DataModel_Definition_Model_Abstract $definition
         */
        $definition = $this->getDataModelDefinition();

        /**
         * var DataModel $this_main_model_instance
         */
        $this_main_model_instance = &DataModel_ObjectState::getVar($this, 'main_model_instance');

        foreach( $definition->getProperties() as $property_name=>$property_definition ) {

            /**
             * @var DataModel_Related_Interface $property
             */
            $property = $this->{$property_name};
            if(!($property instanceof DataModel_Related_Interface)) {
                continue;
            }

            $property->setupParentObjects( $this_main_model_instance, $this );

            $this->{$property_name} = $property->loadRelatedInstances( $loaded_related_data );
        }

    }

    /**
     * @param DataModel_Interface $main_model_instance
     * @param DataModel_Related_Interface|DataModel_Interface $parent_model_instance (optional)
     *
     */
    public function setupParentObjects( DataModel_Interface $main_model_instance, DataModel_Related_Interface $parent_model_instance=null ) {
        /**
         * @var DataModel_Interface|DataModel_Related_Interface $this
         */

        $this_main_model_instance = &DataModel_ObjectState::getVar($this, 'main_model_instance');
        $this_main_model_instance = $main_model_instance;

        $this_parent_model_instance = &DataModel_ObjectState::getVar($this, 'parent_model_instance');
        $this_parent_model_instance = $parent_model_instance;

        $main_ID = $main_model_instance->getIdObject();
        /**
         * @var DataModel_Definition_Model_Related_Abstract $definition
         */
        $definition = $this->getDataModelDefinition();

        foreach( $definition->getMainModelRelationIDProperties() as $property_definition ) {

            if(isset($main_ID[$property_definition->getRelatedToPropertyName()])) {
                if(
                    $this->getIsSaved() &&
                    $this->{$property_definition->getName()} != $main_ID[$property_definition->getRelatedToPropertyName()]
                ) {
                    $this->setIsNew();
                }

                $this->{$property_definition->getName()} = $main_ID[$property_definition->getRelatedToPropertyName()];
            }
        }

        if($parent_model_instance) {
            $parent_ID = $parent_model_instance->getIdObject();

            foreach( $definition->getParentModelRelationIDProperties() as $property_definition ) {

                if(
                    $this->getIsSaved() &&
                    $this->{$property_definition->getName()} != $parent_ID[$property_definition->getRelatedToPropertyName()]
                ) {
                    $this->setIsNew();
                }

                if(isset($parent_ID[$property_definition->getRelatedToPropertyName()])) {
                    $this->{$property_definition->getName()} = $parent_ID[$property_definition->getRelatedToPropertyName()];
                }
            }

        }


        foreach( $definition->getProperties() as $property_name=>$property_definition ) {

            /**
             * @var DataModel_Related_Interface $property
             */
            $property = $this->{$property_name};
            if(!($property instanceof DataModel_Related_Interface)) {
                continue;
            }

            $property->setupParentObjects($this_main_model_instance, $this);

        }


    }

    /**
     * @param array $data
     * @param array &$loaded_related_data
     */
    public function _setRelatedData($data, array &$loaded_related_data=[] ) {
        /**
         * @var DataModel_Interface $this
         */

        $definition = $this->getDataModelDefinition();

        /**
         * var DataModel $this_main_model_instance
         */
        $this_main_model_instance = DataModel_ObjectState::getVar($this, 'main_model_instance');
        if(!$this_main_model_instance ) {
            $this_main_model_instance  = $this;
            $parent_model_instance = null;
        } else {
            $parent_model_instance = $this;

        }

        foreach( $definition->getProperties() as $property_name=>$property_definition ) {

            if(($this->{$property_name} instanceof DataModel_Related_Interface)) {

                $this->{$property_name}->setupParentObjects( $this_main_model_instance, $parent_model_instance );
                if($loaded_related_data) {
                    $property_definition->loadPropertyValue( $this->{$property_name}, $loaded_related_data );
                }
            } else {
                $property_definition->loadPropertyValue( $this->{$property_name}, $data );
            }

        }

        $this->setIsSaved();

    }


    /**
     *
     */
    public function __wakeup_relatedItems() {
        /**
         * @var DataModel_Interface $this
         */

        foreach( $this->getDataModelDefinition()->getProperties() as $property_name=>$property_definition ) {

            /**
             * @var DataModel_Related_Interface $property
             */
            $property = $this->{$property_name};
            if(!($property instanceof DataModel_Related_Interface)) {
                continue;
            }

            $property->__wakeup_relatedItems();
        }
    }

}