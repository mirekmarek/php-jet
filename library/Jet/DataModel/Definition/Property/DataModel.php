<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Definition_Property_DataModel
 * @package Jet
 */
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

            if( $this->is_id ) {
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
	 * @param DataModel_Interface $data_model_instance
	 */
	public function initPropertyDefaultValue(
		&$property,
        DataModel_Interface $data_model_instance
	) {
        $property = $this->getDefaultValue();
    }


    /**
     * Converts property form jsonSerialize
     *
     * Example: Locale to string
     *
     * @param DataModel_Interface $data_model_instance
     * @param mixed &$property
     *
     * @return mixed
     */
    public function getValueForJsonSerialize( DataModel_Interface $data_model_instance, &$property ) {
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
         * @var DataModel_Related_Item_Interface $default_value
         */
        $default_value = new $class_name();

        return $default_value->createNewRelatedDataModelInstance();

	}


    /**
     * @param DataModel_Related_Interface &$property
     * @param array $data
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
		return $this->data_model_class;
	}

    /**
     * @return DataModel_Definition_Model_Related_Abstract
     */
    public function getValueDataModelDefinition() {
	    /**
	     * @var DataModel_Definition_Model_Related_Abstract $definition
	     */
	    $definition = DataModel::getDataModelDefinition( $this->getValueDataModelClass() );

        return $definition;
    }



    /**
     * @param $object_instance
     * @param mixed &$property
     * @param mixed $value
     */
    public function catchFormField( $object_instance, &$property, $value ) {

        if( ($method_name = $this->getFormCatchValueMethodName()) ) {
            $object_instance->{$method_name}($value);
            return;
        }

        if(!($property instanceof DataModel_Related_Interface)) {
            return;
        }

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

            throw new DataModel_Exception('Data model name collision: '.$prev.' vs '.$current, DataModel_Exception::CODE_DEFINITION_NONSENSE);
        }


        $related_definitions[$related_model_name] = $this;

        foreach( $related_model_definition->getProperties() as $related_property_definition ) {
            $related_property_definition->getAllRelatedPropertyDefinitions( $related_definitions );
        }


    }


    /**
     *
     * @param DataModel_Definition_Relations $internal_relations
     *
     * @throws DataModel_Exception
     */
    public function getInternalRelations( DataModel_Definition_Relations $internal_relations ) {

        $related_model_definition = $this->getValueDataModelDefinition();

	    $related_model_definition->getInternalRelations( $internal_relations );


    }

}