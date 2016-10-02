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
	    /** @noinspection PhpIncompatibleReturnTypeInspection */
	    return new static();
    }


    /**
     * @param array &$loaded_related_data
     */
    protected function initRelatedProperties( array &$loaded_related_data ) {
        /**
         * @var DataModel_Interface|DataModel_Related_Interface $this
         * @var DataModel_Definition_Model_Abstract $definition
         */
        $definition = $this->getDataModelDefinition();


        foreach( $definition->getProperties() as $property_name=>$property_definition ) {

            /**
             * @var DataModel_Related_Interface $property
             */
            $property = $this->{$property_name};
            if(!($property instanceof DataModel_Related_Interface)) {
                continue;
            }

            $property->setupParentObjects( $this->_main_model_instance, $this );

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

        $this->_main_model_instance = $main_model_instance;
        $this->_parent_model_instance = $parent_model_instance;

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

            $property->setupParentObjects($this->_main_model_instance, $this);

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

        if(!$this->_main_model_instance ) {
            $this->_main_model_instance  = $this;
            $parent_model_instance = null;
        } else {
            $parent_model_instance = $this;

        }

        foreach( $definition->getProperties() as $property_name=>$property_definition ) {

            if(($this->{$property_name} instanceof DataModel_Related_Interface)) {

                $this->{$property_name}->setupParentObjects( $this->_main_model_instance, $parent_model_instance );
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