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
     * @param array|DataModel_ID_Abstract $ID
     * @param array|DataModel_Load_OnlyProperties $load_only_properties
     *
     * @return DataModel
     */
    public static function load( $ID, $load_only_properties=null ) {

	    /**
	     * @var DataModel|DataModel_Trait_Load $loaded_instance
	     */
        $loaded_instance = new static();
        foreach( $ID as $key=>$val ) {
            $loaded_instance->{$key} = $val;
        }
        $ID = $loaded_instance->getIdObject();


	    $definition = $loaded_instance->getDataModelDefinition();


	    if($load_only_properties) {
		    if(!($load_only_properties instanceof DataModel_Load_OnlyProperties)) {
			    $load_only_properties = new DataModel_Load_OnlyProperties( $definition, $load_only_properties );
		    }
	    } else {
		    $load_only_properties = null;
	    }


        $cache = $loaded_instance->getCacheBackendInstance();

        $data = [];
        $cache_loaded = false;

        if(
            !$load_only_properties &&
            $cache &&
            ($data = $cache->get( $definition, $ID))
        ) {
            if(
                !is_array($data) ||
                !array_key_exists('main_data', $data) ||
                !array_key_exists('related_data', $data) ||
                !is_array($data['main_data']) ||
                !is_array($data['related_data'])
            ) {
                $data = [];
            } else {
                $cache_loaded = true;
            }

        }





        if(!isset($data['main_data'])) {
	        $query = $ID->getQuery();
	        /** @noinspection PhpParamsInspection */
	        $query->setMainDataModel($loaded_instance);

	        $select = DataModel_Load_OnlyProperties::getSelectProperties( $definition, $load_only_properties );

	        $query->setSelect( $select );

            $data['main_data'] = $loaded_instance->getBackendInstance()->fetchRow( $query );
        }

        if(!$data['main_data']) {
            return null;
        }

        //TODO: $load_only_properties dame objektu jako vlastnost a zamezieme jeho ukladani
        foreach( $definition->getProperties() as $property_name=>$property_definition ) {

            if(!($loaded_instance->{$property_name} instanceof DataModel_Related_Interface)) {
                $property_definition->loadPropertyValue( $loaded_instance->{$property_name}, $data['main_data'] );
            }
        }

        $loaded_instance->setIsSaved();

        $related_properties = $definition->getAllRelatedPropertyDefinitions();


        if(!isset($data['related_data'])) {
            $data['related_data'] = [];
            foreach( $related_properties as $related_model_name=>$related_property ) {


                /**
                 * @var DataModel_Related_Interface $related_object
                 */
                $related_object = $related_property->getDefaultValue();
	            /** @noinspection PhpParamsInspection */
                $related_object->setupParentObjects( $loaded_instance );
	            $_related_data = $related_object->loadRelatedData( $load_only_properties );
                unset($related_object);

                if(!isset($related_data[$related_model_name])) {
                    $data['related_data'][$related_model_name] = [];

                    foreach($_related_data as $rd) {
                        $data['related_data'][$related_model_name][] = $rd;
                    }
                }
            }
        }


        foreach( $definition->getProperties() as $property_name=>$property_definition ) {
            $property = $loaded_instance->{$property_name};

            if(($property instanceof DataModel_Related_Interface)) {

	            /** @noinspection PhpParamsInspection */
                $property->setupParentObjects( $loaded_instance );
                $property_definition->loadPropertyValue( $property, $data['related_data'] );
            }
        }


        if(
            !$load_only_properties &&
            $cache &&
            !$cache_loaded
        ) {
            $cache->save($definition, $ID, $data);
        }

	    /**
	     * @var DataModel $loaded_instance
	     */

	    /** @noinspection PhpUndefinedMethodInspection */
	    $loaded_instance->afterLoad();

	    /** @noinspection PhpIncompatibleReturnTypeInspection */
	    return $loaded_instance;
    }


    /**
     * @param array &$loaded_related_data
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