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

/**
 * Class DataModel_Definition_Property_Abstract
 *
 * @JetFactory:class = null
 * @JetFactory:method = null
 * @JetFactory:mandatory_parent_class = 'Jet\DataModel_Definition_Property_Abstract'
 */
abstract class DataModel_Definition_Property_Abstract extends Object {

	/**
	 * Default error messages
	 *
	 * @var array(code=>message)
	 */
	public static $default_error_messages = array(
		DataModel_Validation_Error::CODE_REQUIRED => 'Item is required',
		DataModel_Validation_Error::CODE_INVALID_VALUE => 'Invalid value',
		DataModel_Validation_Error::CODE_INVALID_FORMAT => 'Invalid format',
		DataModel_Validation_Error::CODE_OUT_OF_RANGE => 'Out of range',
	);


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
	protected $do_not_serialize = false;

	/**
	 * @var string
	 */
	protected $default_value = '';

	/**
	 * @var string
	 */
	protected $backend_options = '';

	//Data check params
	/**
	 * @var bool
	 */
	protected $is_required = false;
	/**
	 * @var string
	 */
	protected $validation_method_name = '';
	/**
	 * @var array
	 */
	protected $list_of_valid_options = null;
	/**
	 * Format:
	 * <code>
	 * [
	 *      'error_code' = 'Error message'
	 * ]
	 * </code>
	 *
	 * @var array
	 */
	protected $error_messages = array();

	/**
	 * @var string
	 */
	protected $form_field_creator_method_name = '';

	/**
	 *
	 * @var string
	 */
	protected $form_field_type = '';

	/**
	 *
	 * @var string
	 */
	protected $form_field_label = '';

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
			$this->is_required = (bool)$this->is_required;

			if( $this->is_ID ) {
				if( $this->_is_data_model ) {
					throw new DataModel_Exception(
						$this->data_model_class_name.'::'.$this->_name.' property type is DataModel. Can\'t be ID! ',
						DataModel_Exception::CODE_DEFINITION_NONSENSE
					);

				}
				if( $this->_is_array ) {
					throw new DataModel_Exception(
						$this->data_model_class_name.'::'.$this->_name.' property type is Array. Can\'t be ID! ',
						DataModel_Exception::CODE_DEFINITION_NONSENSE
					);
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
	public function getDoNotSerialize() {
		return $this->do_not_serialize;
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
	 * @param DataModel $data_model
	 *
	 * @return mixed
	 */
	public function getDefaultValue(
		/** @noinspection PhpUnusedParameterInspection */
		DataModel $data_model
	) {
		return $this->default_value;
	}

	/**
	 *
	 * @param mixed $default_value
	 */
	public function setDefaultValue( $default_value ) {
		$this->default_value = $default_value;
	}

	/**
	 * @param string $backend_type
	 *
	 * @return array
	 */
	public function getBackendOptions( $backend_type ) {
		if(!isset($this->backend_options[$backend_type])) {
			return array();
		}
		return $this->backend_options[$backend_type];
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

		return 'Unknown ERROR (code: '.$error_code.')';
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
	 * @param string $form_field_creator_method_name
	 */
	public function setFormFieldCreatorMethodName($form_field_creator_method_name) {
		$this->form_field_creator_method_name = $form_field_creator_method_name;
	}

	/**
	 * @return string
	 */
	public function getFormFieldCreatorMethodName() {
		return $this->form_field_creator_method_name;
	}

	/**
	 * @param callable $form_field_get_default_value_callback
	 */
	public function setFormFieldGetDefaultValueCallback($form_field_get_default_value_callback) {
		$this->form_field_get_default_value_callback = $form_field_get_default_value_callback;
	}

	/**
	 * @return callable
	 */
	public function getFormFieldGetDefaultValueCallback() {
		return $this->form_field_get_default_value_callback;
	}

	/**
	 * @param callable $form_field_get_select_options_callback
	 */
	public function setFormFieldGetSelectOptionsCallback($form_field_get_select_options_callback) {
		$this->form_field_get_select_options_callback = $form_field_get_select_options_callback;
	}

	/**
	 * @return callable
	 */
	public function getFormFieldGetSelectOptionsCallback() {
		return $this->form_field_get_select_options_callback;
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
		$type = $this->is_ID ? 'Hidden' : $this->getFormFieldType();

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
				$callback[0]=='this'
			) {
				$callback[0] = $this->data_model_class_name;
			}

			if(!is_callable($callback)) {
				throw new DataModel_Exception($this->data_model_class_name.'::'.$this->_name.'::form_field_get_select_options_callback is not callable');
			}

			$field->setSelectOptions( $callback() );
		}

		return $field;
	}

	/**
	 * @return string
	 */
	public function getTechnicalDescription() {
		$res = 'Type: '.$this->getType();

		$res .= ', required: '.($this->is_required ? 'yes':'no');


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
			if($p[0]=='_') {
				continue;
			}

			$target_property->{$p} = $v;
		}
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