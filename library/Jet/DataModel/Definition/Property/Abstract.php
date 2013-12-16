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

abstract class DataModel_Definition_Property_Abstract extends Object {
	/**
	 * @var null
	 */
	protected static $__factory_class_name = null;
	/**
	 * @var null
	 */
	protected static $__factory_class_method = null;
	/**
	 * @var string
	 */
	protected static $__factory_must_be_instance_of_class_name = "Jet\\DataModel_Definition_Property_Abstract";

	/**
	 * Default error messages
	 *
	 * @var array(code=>message)
	 */
	public static $default_error_messages = array(
		DataModel_Validation_Error::CODE_REQUIRED => "Item is required",
		DataModel_Validation_Error::CODE_INVALID_VALUE => "Invalid value",
		DataModel_Validation_Error::CODE_INVALID_FORMAT => "Invalid format",
		DataModel_Validation_Error::CODE_OUT_OF_RANGE => "Out of range",
	);

	/**
	 *
	 * @var DataModel_Definition_Model_Abstract
	 */
	protected $_data_model_definition = null;

	/**
	 *
	 * @var DataModel_Definition_Property_Abstract
	 */
	protected $_related_to_property = null;


	/**
	 * @var string
	 */
	protected $_type = null;
	/**
	 * @var bool
	 */
	protected $_is_array = false;
	/**
	 * @var bool
	 */
	protected $_is_data_model = false;


	/**
	 * @var string
	 */
	protected $_name = "";


	/**
	 * @var bool
	 */
	protected $is_ID = false;

	/**
	 * @var string
	 */
	protected $description = "";

	/**
	 * @var bool
	 */
	protected $do_not_serialize = false;

	/**
	 * @var string
	 */
	protected $default_value = "";

	/**
	 * @var string
	 */
	protected $backend_options = "";

	//Data check params
	/**
	 * @var bool
	 */
	protected $is_required = false;
	/**
	 * @var string
	 */
	protected $validation_method_name = "";
	/**
	 * @var array
	 */
	protected $list_of_valid_options = null;
	/**
	 * Format:
	 * <code>
	 * array(
	 *      "error_code" = "Error message"
	 * )
	 * </code>
	 *
	 * @var array
	 */
	protected $error_messages = array();

	/**
	 *
	 * @var string
	 */
	protected $form_field_type = "";

	/**
	 *
	 * @var string
	 */
	protected $form_field_label = "";

	/**
	 *
	 * @var string
	 */
	protected $form_field_error_messages = array();

	/**
	 *
	 * @var callable
	 */
	protected $form_field_get_default_value_callback;

	/**
	 *
	 * @var callable
	 */
	protected $form_field_get_select_options_callback;

	/**
	 *
	 * @var array
	 */
	protected $form_field_options = array();


	/**
	 * @param DataModel_Definition_Model_Abstract $model_definition
	 * @param string $name
	 * @param array $definition_data (optional)
	 */
	public function  __construct(DataModel_Definition_Model_Abstract $model_definition, $name, $definition_data=null ) {
		$this->_data_model_definition = $model_definition;
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
			unset($definition_data["type"]);

			foreach($definition_data as $key=>$val) {
				if( !$this->getHasProperty($key) ) {
					throw new DataModel_Exception(
						"{$this->_data_model_definition->getClassName()}::{$this->_name}: unknown definition option '{$key}'  ",
						DataModel_Exception::CODE_DEFINITION_NONSENSE
					);
				}

				$this->{$key} = $val;
			}

			$this->is_ID = (bool)$this->is_ID;
			$this->is_required = (bool)$this->is_required;

			if( $this->is_ID ) {
				if( $this->_is_data_model ) {
					throw new DataModel_Exception(
						"{$this->_data_model_definition->getClassName()}::{$this->_name} property type is DataModel. Can't be ID! ",
						DataModel_Exception::CODE_DEFINITION_NONSENSE
					);

				}
				if( $this->_is_array ) {
					throw new DataModel_Exception(
						"{$this->_data_model_definition->getClassName()}::{$this->_name} property type is Array. Can't be ID! ",
						DataModel_Exception::CODE_DEFINITION_NONSENSE
					);
				}
			}

		}

	}

	/**
	 * @param DataModel_Definition_Property_Abstract $related_to_property
	 * @throws DataModel_Exception
	 */
	public function setUpRelation( DataModel_Definition_Property_Abstract $related_to_property ) {
		if(!$this->is_ID) {
			throw new DataModel_Exception(
				"{$this->_data_model_definition->getClassName()}::{$this->_name} property is not ID. Can't setup relations! ",
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$this->_related_to_property = $related_to_property;
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
		return $this->_data_model_definition->getClassName()."::".$this->getName();
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
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return boolean
	 */
	public function getDoNotSerialize() {
		return $this->do_not_serialize;
	}

	/**
	 *
	 * @return DataModel_Definition_Model_Abstract
	 */
	public function getDataModelDefinition() {
		return $this->_data_model_definition;
	}

	/**
	 * @return DataModel_Definition_Property_Abstract
	 */
	public function getRelatedToProperty() {
		return $this->_related_to_property;
	}

	/**
	 * @return bool
	 */
	public function getIsArray() {
		return $this->_is_array;
	}


	/**
	 * @return bool
	 */
	public function getIsDataModel() {
		return $this->_is_data_model;
	}

	/**
	 * @return bool
	 */
	public function getIsID() {
		return $this->is_ID;
	}


	/**
	 * @return int|null
	 */
	public function getMaxLen() {
		return null;
	}

	/**
	 * @return bool
	 */
	public function getIsRequired() {
		return $this->is_required;
	}

	/**
	 * @return mixed
	 */
	public function getDefaultValue() {
		return $this->default_value;
	}

	/**
	 * @return array
	 */
	public function getBackendOptions() {
		return $this->backend_options;
	}

	/**
	 * @return string
	 */
	public function getValidationMethodName() {
		return $this->validation_method_name;
	}

	/**
	 * @return array
	 */
	public function getListOfValidOptions() {
		return $this->list_of_valid_options;
	}


	/**
	 * @param string $error_code
	 * @return string
	 */
	public function getErrorMessage( $error_code ) {
		if(isset($this->error_messages[$error_code])) {
			return $this->error_messages[$error_code];
		}

		if(isset(self::$default_error_messages[$error_code])) {
			return self::$default_error_messages[$error_code];
		}

		return "Unknown ERROR (code: {$error_code})";
	}

	/**
	 * Check data type by definition (retype)
	 *
	 * @param mixed &$value
	 */
	abstract public function checkValueType( &$value );


	/**
	 * @param mixed &$value
	 * @param DataModel_Validation_Error[] &$errors
	 *
	 *
	 * @return bool
	 */
	public function validateProperties( &$value, &$errors ) {
		$this->checkValueType($value);

		if(!$this->_validateProperties_test_required($value, $errors )) {
			return false;
		}
		if($this->list_of_valid_options) {
			if(!$this->_validateProperties_test_validOptions($value, $errors )) {
				return false;
			}
		}
		return $this->_validateProperties_test_value($value, $errors );

	}


	/**
	 * Property required test
	 *
	 * @param mixed &$value
	 * @param DataModel_Validation_Error[] &$errors[]
	 *
	 * @return bool
	 */
	public function _validateProperties_test_required( &$value, &$errors ) {
		if( !$this->is_required ) {
			return true;
		}

		if(!$value) {
			$errors[] = new DataModel_Validation_Error(
					DataModel_Validation_Error::CODE_REQUIRED,
					$this, 
					$value
				);

			return false;
		}

		return true;
	}

	/**
	 * Property value test - value must be in list of valid options
	 *
	 * @param mixed &$value
	 * @param DataModel_Validation_Error[] &$errors

	 * @return bool
	 */
	public function _validateProperties_test_validOptions( &$value, &$errors ) {
		if(!in_array($value, $this->list_of_valid_options)) {

			$errors[] = new DataModel_Validation_Error(
						DataModel_Validation_Error::CODE_INVALID_VALUE,
						$this, $value
					);

			return false;
		}

		return true;
	}

	/**
	 * Property value test - can be specific for each column type (eg: min and max value for number, string format ...)
	 *
	 * @param mixed &$value
	 * @param DataModel_Validation_Error[] &$errors
	 *
	 * @return bool
	 */
	/** @noinspection PhpUnusedParameterInspection */
	public function _validateProperties_test_value(
					/** @noinspection PhpUnusedParameterInspection */
					&$value,
					/** @noinspection PhpUnusedParameterInspection */
	                                &$errors) {
		return true;
	}

	/**
	 * @return string
	 */
	public function getFormFieldType() {
		return $this->form_field_type;
	}

	/**
	 * @return array
	 */
	public function getFormFieldOptions() {
		return $this->form_field_options;
	}

	/**
	 * @return string
	 */
	public function getFormFieldLabel() {
		return $this->form_field_label;
	}

	/**
	 * @return string
	 */
	public function getFormFieldErrorMessages() {
		$error_messages = $this->form_field_error_messages;

		foreach($error_messages as $k=>$m) {
			$error_messages[$k] = $m;
		}
		return $error_messages;
	}


	/**
	 *
	 * @throws DataModel_Exception
	 *
	 * @return Form_Field_Abstract
	 */
	public function getFormField() {
		$type = $this->is_ID ? "Hidden" : $this->getFormFieldType();

		if(!$type) {
			return null;
		}

		if($this->form_field_get_default_value_callback) {
			$callback = $this->form_field_get_default_value_callback;
			$this->default_value = $callback();
		}

		$field = Form_Factory::getFieldInstance(
				$type,
				$this->_name,
				$this->getFormFieldLabel(),
				$this->default_value,
				$this->is_required
			);

		$field->setOptions( $this->getFormFieldOptions() );

		if($this->form_field_get_select_options_callback) {
			$callback = $this->form_field_get_select_options_callback;

			if(
				is_array($callback) &&
				$callback[0]=="this"
			) {
				$callback[0] = $this->_data_model_definition->getClassName();
			}

			if(!is_callable($callback)) {
				throw new DataModel_Exception($this->_data_model_definition->getClassName()."::".$this->_name."::form_field_get_select_options_callback is not callable");
			}

			$field->setSelectOptions( $callback() );
		}

		return $field;
	}

	/**
	 * @return string
	 */
	public function getTechnicalDescription() {
		$res = "Type: ".$this->getType();

		$res .= ", required: ".($this->is_required ? "yes":"no");


		if($this->is_ID) {
			$res .= ", is ID";
		}

		if($this->default_value) {
			$res .= ", default value: {$this->default_value}";
		}

		if($this->description) {
			$res .= "\n\n{$this->description}";
		}

		return $res;
	}

	/**
	 * Converts property form jsonSerialize
	 *
	 * Example: Locale to string
	 *
	 * @param mixed $property_value
	 * @return mixed
	 */
	public function getValueForJsonSerialize( $property_value ) {
		return $property_value;
	}


	/**
	 * @static
	 * @param DataModel_Definition_Property_Abstract $source_property
	 * @param DataModel_Definition_Property_Abstract $target_property
	 */
	public static function cloneProperty(
		DataModel_Definition_Property_Abstract $source_property,
		DataModel_Definition_Property_Abstract$target_property
	) {
		$props = get_object_vars($source_property);

		foreach( $props as $p=>$v ) {
			if($p[0]=="_") {
				continue;
			}

			$target_property->{$p} = $v;
		}
	}

}