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

trait DataModel_Trait_Forms {
    /**
     *
     * @param string $form_name
     * @param array $properties_list
     * @throws DataModel_Exception
     *
     * @return Form
     */
    public function getForm( $form_name, array $properties_list ) {
        /**
         * @var DataModel $this
         */

        $definition = $this->getDataModelDefinition();
        $properties_definition = $definition->getProperties();

        $form_fields = [];

        foreach( $properties_list as $key=>$val ) {
            if(is_array($val)) {
                $property_name = $key;
                $related_data = $val;
            } else {
                $property_name = $val;
                $related_data = [];
            }

            $property_definition = $properties_definition[$property_name];
            $property = &$this->{$property_name};

            if( ($field_creator_method_name = $property_definition->getFormFieldCreatorMethodName()) ) {
                $created_field = $this->{$field_creator_method_name}( $property_definition, $related_data );
            } else {
                if($property instanceof DataModel_Related_Interface) {
                    foreach( $property->getRelatedFormFields( $property_definition, $related_data  ) as $field ) {
                        $form_fields[] = $field;
                    }

                    continue;
                }

                $created_field = $property_definition->createFormField( $property );

            }

            if(!$created_field) {
                continue;
            }

            $created_field->setCatchDataCallback( function( $value ) use ($property_definition, &$property) {
                $property_definition->catchFormField( $this, $property, $value );
            } );

            $form_fields[] = $created_field;


        }


        return new Form( $form_name, $form_fields );

    }

    /**
     * @param string $form_name
     *
     * @return Form
     */
    public function getCommonForm( $form_name='' ) {
        /**
         * @var DataModel $this
         */

        $properties_list = $this->getCommonFormPropertiesList();

        if(!$form_name) {
            $definition = $this->getDataModelDefinition();
            $form_name = $definition->getModelName();
        }

        return $this->getForm($form_name, $properties_list );
    }


    /**
     * @return array
     */
    public function getCommonFormPropertiesList() {
        /**
         * @var DataModel $this
         */

        $definition = $this->getDataModelDefinition();
        $properties_list = [];

        foreach($definition->getProperties() as $property_name => $property_definition) {
            if(
                !$property_definition->getCanBeFormField() ||
                $property_definition->getFormFieldType()===false
            ) {
                continue;
            }

            $property = $this->{$property_name};

            if($property instanceof DataModel_Related_Interface) {
                $properties_list[$property_name] = $property->getCommonFormPropertiesList();

            } else {
                $properties_list[] = $property_name;
            }

        }

        return $properties_list;

    }

    /**
     * @param Form $form
     *
     * @param array $data
     * @param bool $force_catch
     *
     * @return bool;
     */
    public function catchForm( Form $form, $data=null, $force_catch=false   ) {

        if(
            !$form->catchValues($data, $force_catch) ||
            !$form->validateValues()
        ) {
            return false;
        }

        return $form->catchData();
    }

}