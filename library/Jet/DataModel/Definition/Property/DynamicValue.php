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

class DataModel_Definition_Property_DynamicValue extends DataModel_Definition_Property_Abstract {
	/**
	 * @var string
	 */
	protected $_type = DataModel::TYPE_DYNAMIC_VALUE;

	/**
	 * @var string
	 */
	protected $getter_name = null;

	/**
	 * @var mixed
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

			if( !$this->getter_name ) {
				throw new DataModel_Exception(
					'Property '.$this->data_model_class_name.'::'.$this->_name.' is Dynamic Value, but getter_name is missing in definition data.',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}
		}

	}


    /**
     * @param &$property
     */
    public function initPropertyDefaultValue( &$property ) {
    }

    /**
     * @param DataModel $data_model_instance
     * @param mixed &$property
     * @param DataModel_Validation_Error[] &$errors
     *
     *
     * @return bool
     */
    public function validatePropertyValue( DataModel $data_model_instance, &$property, &$errors ) {
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
        return $data_model_instance->{$this->getGetterName()}();
    }

    /**
     *
     * @param DataModel $data_model_instance
     * @param mixed &$property
     *
     * @return mixed
     */
    public function getXMLexportValue( DataModel $data_model_instance, &$property ) {
        return $data_model_instance->{$this->getGetterName()}();
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
     * @return bool
     */
    public function getCanBeFormField() {
        return false;
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
    }

	/**
	 *
	 * @return void
	 *
	 * @throws DataModel_Exception
	 */
	public function getDefaultValue() {
		throw new DataModel_Exception('You can not use getDefaultValue for the property that is DynamicValue (property: '.$this->_name.')');
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
		throw new DataModel_Exception('You can not use checkValueType for the property that is DynamicValue (property: '.$this->_name.')');
	}

	/**
	 * @return mixed
	 */
	public function getGetterName() {
		return $this->getter_name;
	}
}