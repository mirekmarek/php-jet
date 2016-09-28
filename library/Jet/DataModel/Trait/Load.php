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
     * @param array $load_only_properties
     *
     * @return DataModel
     */
    public static function load( $ID, array $load_only_properties=[] ) {

	    /**
	     * @var DataModel|DataModel_Trait_Load $loaded_instance
	     */
        $loaded_instance = new static();
        foreach( $ID as $key=>$val ) {
            $loaded_instance->{$key} = $val;
        }
        $ID = $loaded_instance->getIdObject();


        $definition = $loaded_instance->getDataModelDefinition();

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


        $query = $ID->getQuery();
	    /** @noinspection PhpParamsInspection */
	    $query->setMainDataModel($loaded_instance);


        if(!$load_only_properties) {
            $load_only_related_properties = null;
            $select = $definition->getProperties();
        } else {
            $select = [];
            $load_only_related_properties = [];

            foreach( $load_only_properties as $lp ) {
                $property_name = null;

                if(strpos($lp, '.')===false) {
                    $property_name = $lp;
                } else {
                    list($model_name, $_property_name) = explode('.', $lp);

                    if($model_name=='this' || $model_name==$definition->getModelName()) {
                        $property_name = $_property_name;
                    } else {
                        if(!isset($load_only_related_properties[$model_name])) {
                            $load_only_related_properties[$model_name] = [];
                        }
                        $load_only_related_properties[$model_name][] = $_property_name;
                    }

                }

                if($property_name===null) {
                    continue;
                }

                $select[] = $definition->getProperty($property_name);
            }
        }
        $query->setSelect( $select );


        if(!isset($data['main_data'])) {
            $data['main_data'] = $loaded_instance->getBackendInstance()->fetchRow( $query );
        }

        if(!$data['main_data']) {
            return null;
        }


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

                if($load_only_related_properties!==null) {
                    if(!isset($load_only_related_properties[$related_model_name])) {
                        continue;
                    }
                }

                /**
                 * @var DataModel_Related_Interface $related_object
                 */
                $related_object = $related_property->getDefaultValue();
	            /** @noinspection PhpParamsInspection */
                $related_object->setupParentObjects( $loaded_instance );

                if($load_only_related_properties) {
                    $_related_data = $related_object->loadRelatedData( $load_only_related_properties );
                } else {
                    $_related_data = $related_object->loadRelatedData();
                }
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