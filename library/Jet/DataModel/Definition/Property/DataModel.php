<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Definition
 */
namespace Jet;

class DataModel_Definition_Property_DataModel extends DataModel_Definition_Property_Abstract {
	/**
	 * @var string
	 */
	protected $_type = DataModel::TYPE_DATA_MODEL;

	/**
	 * @var string
	 */
	protected $data_model_class = null;

	/**
	 * @var DataModel
	 */
	protected $default_value = null;

	/**
	 * @param array $definition_data
	 *
	 * @throws DataModel_Exception
	 */
	public function setUp( $definition_data ) {

		if($definition_data) {
			parent::setUp($definition_data);

            if( $this->is_ID ) {
                throw new DataModel_Exception(
                    $this->data_model_class_name.'::'.$this->_name.' property type is DataModel. Can\'t be ID! ',
                    DataModel_Exception::CODE_DEFINITION_NONSENSE
                );
            }


			if( !$this->data_model_class ) {
				throw new DataModel_Exception(
					'Property '.$this->data_model_class_name.'::'.$this->_name.' is DataModel, but data_model_class is missing in definition data.',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}
		}

	}


    /**
     * @param &$property
     */
    public function initPropertyDefaultValue( &$property ) {
        $property = $this->getDefaultValue();
    }

    /**
     * @param DataModel $data_model_instance
     * @param mixed &$property
     * @param DataModel_Validation_Error[] &$errors
     *
     *
     * @throws DataModel_Exception
     *
     * @return bool
     */
    public function validatePropertyValue( DataModel $data_model_instance, &$property, &$errors ) {

        $validation_method_name = $this->getValidationMethodName();


        if($validation_method_name) {
            return $data_model_instance->{$validation_method_name}($this, $property, $errors);
        }


        if( !$property ) {
            return true;
        }

        if(!is_object($property)) {

            throw new DataModel_Exception(
                get_class($data_model_instance).'::'.$this->getName().' should be an Object! ',
                DataModel_Exception::CODE_INVALID_PROPERTY_TYPE
            );
        }

        /**
         * @var DataModel $property
         */
        $property->validateProperties();

        $_errors = $property->getValidationErrors();
        if($_errors) {
            $errors = array_merge( $errors, $_errors );

            return false;
        }

        return true;

    }


    /**
     * Converts property form jsonSerialize
     *
     * Example: Locale to string
     *
     * @param DataModel $data_model_instance
     * @param mixed &$property
     *
     * @return mixed
     */
    public function getValueForJsonSerialize( DataModel $data_model_instance, &$property ) {
        if(!$property) {
            return null;
        }

        /**
         * @var DataModel $property
         */
        return $property->jsonSerialize();
    }


    /**
     * @return bool
     */
    public function getCanBeTableField() {
        return false;
    }

    /**
     * @return bool
     */
    public function getCanBeInSelectPartOfQuery() {
        return false;
    }

    /**
     * @return bool
     */
    public function getCanBeInInsertRecord() {
        return false;
    }

    /**
     * @return bool
     */
    public function getCanBeInUpdateRecord() {
        return false;
    }


	/**
	 *
	 * @return mixed
	 */
	public function getDefaultValue() {
		$class_name =  $this->getValueDataModelClass();

        /**
         * @var DataModel_Related_Interface $default_value
         */
        $default_value = new $class_name();

        return $default_value->createNewRelatedDataModelInstance();

	}


    /**
     * @param mixed &$property
     * @param mixed $data
     *
     */
    public function loadPropertyValue( &$property, array $data ) {
    }

	/**
	 * @param mixed $value
	 *
	 * @throws DataModel_Exception
	 */
	public function checkValueType( &$value ) {
		throw new DataModel_Exception('You can not use checkValueType for the property that is DataObject (property: '.$this->_name.')');
	}

	/**
	 *
	 * @return string
	 */
	public function getValueDataModelClass() {
		return Factory::getClassName($this->data_model_class);
	}

    /**
     * @return DataModel_Definition_Model_Related_Abstract
     */
    public function getValueDataModelDefinition() {

        return DataModel::getDataModelDefinition( $this->getValueDataModelClass() );
    }

    /**
     *
     * @param DataModel $data_model_instance
     * @param mixed $property_value
     *
     * @throws DataModel_Exception
     * @return Form_Field_Abstract|Form_Field_Abstract[]
     */
    public function createFormField( DataModel $data_model_instance, $property_value ) {

        $field_creator_method_name = $this->getFormFieldCreatorMethodName();

        if( $field_creator_method_name ) {
            return $data_model_instance->{$field_creator_method_name}( $this );
        }


        /**
         * @var DataModel_Related_Interface $property_value
         */
        if(!$property_value) {
            return false;
        }

        $fields = array();
        foreach( $property_value->getRelatedFormFields( $this  ) as $field ) {
            $fields[] = $field;
        }

        return $fields;

    }


    /**
     * @param DataModel $data_model_instance
     * @param mixed &$property
     * @param mixed $value
     */
    public function catchFormField( DataModel $data_model_instance, &$property, $value ) {

        if( ($method_name = $this->getFormCatchValueMethodName()) ) {
            $data_model_instance->{$method_name}($value);
            return;
        }

        if(!($property instanceof DataModel_Related_Interface)) {
            return;
        }

        $property->catchRelatedForm($value);

    }

    /**
     *
     * @param array|DataModel_Definition_Property_DataModel[] &$related_definitions
     *
     * @throws DataModel_Exception
     *
     */
    public function getAllRelatedPropertyDefinitions( array &$related_definitions ) {
        /**
         * @var DataModel_Definition_Property_DataModel[] $related_definitions
         */

        $related_model_definition = $this->getValueDataModelDefinition();

        $related_model_name = $related_model_definition->getModelName();


        if(isset($related_definitions[$related_model_name])) {
            $prev = $related_definitions[$related_model_name]->getValueDataModelClass();
            $current = $this->getValueDataModelClass();

            throw new DataModel_Exception('Data model name colision: '.$prev.' vs '.$current, DataModel_Exception::CODE_DEFINITION_NONSENSE);
        }


        $related_definitions[$related_model_name] = $this;

        foreach( $related_model_definition->getProperties() as $related_property_definition ) {
            $related_property_definition->getAllRelatedPropertyDefinitions( $related_definitions );
        }


    }


    /**
     *
     * @param array|DataModel_Definition_Property_DataModel[] &$internal_relations
     *
     * @throws DataModel_Exception
     */
    public function getInternalRelations( array &$internal_relations ) {

        /**
         * @var DataModel_Definition_Property_DataModel[] $internal_relations
         */

        $related_model_definition = $this->getValueDataModelDefinition();

        $related_model_name = $related_model_definition->getModelName();

        if(isset($internal_relations[$related_model_name])) {
            $prev = $internal_relations[$related_model_name]->getValueDataModelClass();
            $current = $this->getValueDataModelClass();

            throw new DataModel_Exception('Data model name colision: '.$prev.' vs '.$current, DataModel_Exception::CODE_DEFINITION_NONSENSE);
        }


        $internal_relations[$related_model_name] = new DataModel_Definition_Relation_Internal(
            $related_model_definition,
            $related_model_definition->getMainModelRelationJoinItems()
        );

        foreach( $related_model_definition->getProperties() as $related_property_definition ) {
            $related_property_definition->getInternalRelations( $internal_relations );
        }

    }

}