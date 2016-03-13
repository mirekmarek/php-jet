<?php
/**
 *
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
 */
namespace Jet;

trait DataModel_Trait_Load {

    /**
     * Loads DataModel.
     *
     * @param DataModel_ID_Abstract|array $ID
     *
     * @throws DataModel_Exception
     *
     * @return DataModel
     */
    public static function load( $ID ) {

        /**
         * @var DataModel $loaded_instance
         */
        $loaded_instance = new static();
        foreach( $ID as $key=>$val ) {
            $loaded_instance->{$key} = $val;
        }
        $ID = $loaded_instance->getIdObject();


        $definition = $loaded_instance->getDataModelDefinition();

        $cache = $loaded_instance->getCacheBackendInstance();


        if($cache) {
            $cached_instance = $cache->get( $definition, $ID);

            if($cached_instance) {
                /**
                 * @var DataModel $loaded_instance
                 */

                foreach( $definition->getProperties() as $property_name=>$property_definition ) {

                    /**
                     * @var DataModel_Related_Interface $related_object
                     */
                    $related_object = $cached_instance->{$property_name};

                    if($related_object instanceof DataModel_Related_Interface) {
                        $related_object->setupParentObjects( $cached_instance );

                    }
                }

                $cached_instance->setIsSaved();
                $cached_instance->afterLoad();

                return $cached_instance;
            }
        }




        $query = $ID->getQuery();
        $query->setMainDataModel($loaded_instance);

        $query->setSelect( $definition->getProperties() );


        $data = $loaded_instance->getBackendInstance()->fetchRow( $query );

        if(!$data) {
            return null;
        }


        foreach( $definition->getProperties() as $property_name=>$property_definition ) {

            if(!($loaded_instance->{$property_name} instanceof DataModel_Related_Interface)) {
                $property_definition->loadPropertyValue( $loaded_instance->{$property_name}, $data );
            }
        }
        $loaded_instance->setIsSaved();


        $related_properties = $definition->getAllRelatedPropertyDefinitions();

        $loaded_related_data = [];

        foreach( $related_properties as $related_model_name=>$related_property ) {

            /**
             * @var DataModel_Related_Interface $related_object
             */
            $related_object = $related_property->getDefaultValue();
            $related_object->setupParentObjects( $loaded_instance );

            $related_data = $related_object->loadRelatedData();
            unset($related_object);

            if(!isset($loaded_related_data[$related_model_name])) {
                $loaded_related_data[$related_model_name] = [];

                foreach($related_data as $rd) {
                    $loaded_related_data[$related_model_name][] = $rd;
                }
            }
        }



        foreach( $definition->getProperties() as $property_name=>$property_definition ) {
            $property = $loaded_instance->{$property_name};

            if(($property instanceof DataModel_Related_Interface)) {
                $property->setupParentObjects( $loaded_instance );
                $property_definition->loadPropertyValue( $property, $loaded_related_data );
            }

        }

        //$loaded_instance->initRelatedProperties( $loaded_related_data );


        if($cache) {
            $cache->save($definition, $ID, $loaded_instance);
        }

        $loaded_instance->afterLoad();

        return $loaded_instance;
    }


    /**
     * @param array &$loaded_related_data
     * @return mixed
     */
    protected function initRelatedProperties( array &$loaded_related_data ) {
        /**
         * @var DataModel $this
         */
        $definition = $this->getDataModelDefinition();

        foreach( $definition->getProperties() as $property_name=>$property_definition ) {

            /**
             * @var DataModel_Definition_Property_DataModel $property_definition
             * @var DataModel_Related_Interface $property
             */
            $property = $this->{$property_name};
            if(!($property instanceof DataModel_Related_Interface)) {
                continue;
            }

            $property->setupParentObjects( $this );

            $this->{$property_name} = $property->loadRelatedInstances( $loaded_related_data );
        }

    }


}