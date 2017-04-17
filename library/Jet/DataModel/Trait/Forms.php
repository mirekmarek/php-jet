<?php
/**
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
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
     * @param array|DataModel_PropertyFilter|null $property_filter
     * @throws DataModel_Exception
     *
     * @return Form
     */
    public function getForm( $form_name, $property_filter=null ) {
        /**
         * @var DataModel $this
         * @var DataModel_Definition_Model_Abstract $definition
         */


        $definition = static::getDataModelDefinition();

	    if(
	        $property_filter &&
	        !($property_filter instanceof DataModel_PropertyFilter)
	    ) {
		    $property_filter = new DataModel_PropertyFilter($definition, $property_filter);
	    }


        $form_fields = [];

        foreach($definition->getProperties() as $property_name=>$property_definition ) {
	        if(
	        	$property_filter &&
		        !$property_filter->getPropertyAllowed( $definition->getModelName(), $property_name )
	        ) {
	        	continue;
	        }
            $property = &$this->{$property_name};

            if( ($field_creator_method_name = $property_definition->getFormFieldCreatorMethodName()) ) {
                $created_field = $this->{$field_creator_method_name}( $property_definition, $property_filter );
            } else {
                if(
                	is_object($property) &&
	                method_exists( $property, 'getRelatedFormFields' ) &&
	                $property_definition->getFormFieldType()!==false
                ) {
                    foreach( $property->getRelatedFormFields( $property_definition, $property_filter  ) as $field ) {
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
         * @var DataModel_Definition_Model_Abstract $definition
         */

        if(!$form_name) {
            $definition = static::getDataModelDefinition();
            $form_name = $definition->getModelName();
        }

        return $this->getForm( $form_name );
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