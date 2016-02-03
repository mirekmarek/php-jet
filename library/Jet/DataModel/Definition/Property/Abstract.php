<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Definition
 */
namespace Jet;

abstract class DataModel_Definition_Property_Abstract extends Object implements Form_Field_Definition_Interface{
	use Form_Field_Definition_Trait;

	/**
	 * @var string
	 */
	protected $data_model_class_name = '';

	/**
	 *
	 * @var string|null
	 */
	protected $related_to_class_name = null;

	/**
	 *
	 * @var string|null
	 */
	protected $related_to_property_name = null;


	/**
	 * @var string
	 */
	protected $_type = null;


	/**
	 * @var string
	 */
	protected $_name = '';


	/**
	 * @var bool
	 */
	protected $is_ID = false;

	/**
	 * @var bool
	 */
	protected $is_key = false;

	/**
	 * @var bool
	 */
	protected $is_unique = false;


	/**
	 * @var string
	 */
	protected $description = '';

	/**
	 * @var bool
	 */
	protected $do_not_export = false;

	/**
	 *
	 * @var string
	 */
	protected $default_value = '';

	/**
	 * @var string
	 */
	protected $backend_options = '';




	/**
	 * @param string $data_model_class_name
	 * @param string $name
	 * @param array $definition_data (optional)
	 */
	public function  __construct( $data_model_class_name, $name, $definition_data=null ) {
		$this->data_model_class_name = (string)$data_model_class_name;
		$this->_name = $name;

		$this->setUp($definition_data);
		
	}

	/**
	 * @param $definition_data
	 *
	 * @throws DataModel_Exception
	 */
	public function setUp( $definition_data ) {
		if($definition_data) {
			unset($definition_data['type']);

			foreach($definition_data as $key=>$val) {
				if( !$this->getHasProperty($key) ) {
					throw new DataModel_Exception(
						$this->data_model_class_name.'::'.$this->_name.': unknown definition option \''.$key.'\'  ',
						DataModel_Exception::CODE_DEFINITION_NONSENSE
					);
				}

				$this->{$key} = $val;
			}

			$this->is_ID = (bool)$this->is_ID;
			$this->is_key = (bool)$this->is_key;
			$this->is_unique = (bool)$this->is_unique;
			$this->form_field_is_required = (bool)$this->form_field_is_required;

			if( $this->is_ID ) {
				if(!isset($definition_data['form_field_type'])) {
					$this->form_field_type = Form::TYPE_HIDDEN;
				}
			}

		}

	}

	/**
	 *
	 * @param $related_to_class_name
	 * @param $related_to_property_name
	 *
	 * @throws DataModel_Exception
	 */
	public function setUpRelation( $related_to_class_name, $related_to_property_name ) {
		$this->related_to_class_name = $related_to_class_name;
		$this->related_to_property_name = $related_to_property_name;
	}

	/**
	 * @return string
	 */
	public function getDataModelClassName() {
		return $this->data_model_class_name;
	}

	/**
	 *
	 * @return DataModel_Definition_Model_Abstract|DataModel_Definition_Model_Related_Abstract
	 */
	public function getDataModelDefinition() {
		return DataModel_Definition_Model_Abstract::getDataModelDefinition($this->data_model_class_name);
	}

	/**
	 * @return null|string
	 */
	public function getRelatedToClassName() {
		return $this->related_to_class_name;
	}

	/**
	 * @return string
	 */
	public function getRelatedToPropertyName() {
		return $this->related_to_property_name;
	}


	/**
	 * @return string
	 */
	public function  __toString() {
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString() {
		return $this->data_model_class_name.'::'.$this->getName();
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->_type;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * @return bool
	 */
	public function getIsKey() {
		return $this->is_key;
	}

	/**
	 * @return bool
	 */
	public function getIsUnique() {
		return $this->is_unique;
	}



	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return boolean
	 */
	public function doNotExport() {
		return $this->do_not_export;
	}

	/**
	 * @return bool
	 */
	public function getIsID() {
		return $this->is_ID;
	}

    /**
     * @return bool
     */
    public function getMustBeSerializedBeforeStore() {
        return false;
    }

    /**
     * @return bool
     */
    public function getCanBeTableField() {
        return true;
    }

    /**
     * @return bool
     */
    public function getCanBeInSelectPartOfQuery() {
        return true;
    }

    /**
     * @return bool
     */
    public function getCanBeInInsertRecord() {
        return true;
    }

    /**
     * @return bool
     */
    public function getCanBeInUpdateRecord() {
        if($this->getIsID()) {
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function getCanBeFormField() {
        return true;
    }


	/**
	 * @return int|null
	 */
	public function getMaxLen() {
		return null;
	}

    /**
     * @param &$property
     * @param DataModel_Interface $data_model_instance
     */
	public function initPropertyDefaultValue(
		&$property,
		/** @noinspection PhpUnusedParameterInspection */
        DataModel_Interface $data_model_instance
	) {

        if($property===null) {
            $property = $this->getDefaultValue();

            $this->checkValueType( $property );
        }
    }

	/**
	 *
	 * @return mixed
	 */
	public function getDefaultValue() {
		return $this->default_value;
	}


	/**
	 * @param string $backend_type
	 *
	 * @return array
	 */
	public function getBackendOptions( $backend_type ) {
		if(!isset($this->backend_options[$backend_type])) {
			return [];
		}
		return $this->backend_options[$backend_type];
	}


	/**
	 * Check data type by definition (retype)
	 *
	 * @param mixed &$value
	 */
	abstract public function checkValueType( &$value );

    /**
     * @param mixed &$property
     * @param mixed $data
     *
     */
     public function loadPropertyValue( &$property, array $data ) {
        $property = $data[$this->getName()];

        $this->checkValueType( $property );
    }

	/**
	 * @return string
	 */
	public function getFormFieldName() {
		return $this->_name;
	}

	/**
	 * @return string
	 */
	public function getFormFieldContextClassName() {
		return $this->data_model_class_name;
	}

	/**
	 * @return string
	 */
	public function getFormFieldContextPropertyName() {
		return $this->_name;
	}


	/**
	 * @return string
	 */
	public function getTechnicalDescription() {
		$res = 'Type: '.$this->getType();

		$res .= ', required: '.($this->form_field_is_required ? 'yes':'no');


		if($this->is_ID) {
			$res .= ', is ID';
		}

		if($this->default_value) {
			$res .= ', default value: '.$this->default_value;
		}

		if($this->description) {
			$res .= JET_EOL.JET_EOL.$this->description;
		}

		return $res;
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
	public function getValueForJsonSerialize( /** @noinspection PhpUnusedParameterInspection */ DataModel_Interface $data_model_instance, &$property ) {
		return $property;
	}


    /**
     *
     * @param DataModel_Interface $data_model_instance
     * @param mixed &$property
     *
     * @return mixed
     */
    public function getXmlExportValue( /** @noinspection PhpUnusedParameterInspection */DataModel_Interface $data_model_instance, &$property ) {
        return $property;
    }

    /**
     *
     * @param array|DataModel_Definition_Property_DataModel[] &$related_definitions
     * @throws DataModel_Exception
     */
    public function getAllRelatedPropertyDefinitions( array &$related_definitions ) {
    }

    /**
     *
     * @param DataModel_Definition_Relations $internal_relations
     * @throws DataModel_Exception
     */
    public function getInternalRelations( DataModel_Definition_Relations $internal_relations ) {

    }


	/**
	 * @param $data
	 *
	 * @return static
	 */
	public static function __set_state( $data ) {

		$i = new static( $data['data_model_class_name'], $data['_name'] );

		foreach( $data as $key=>$val ) {
			$i->{$key} = $val;
		}

		return $i;
	}

}