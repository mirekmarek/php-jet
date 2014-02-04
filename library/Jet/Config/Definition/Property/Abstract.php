<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Config
 * @subpackage Config_Definition
 */
namespace Jet;

/**
 * Class Config_Definition_Property_Abstract
 *
 * @JetFactory:class = null
 * @JetFactory:method = null
 * @JetFactory:mandatory_parent_class = 'Jet\\Config_Definition_Property_Abstract'
 */
abstract class Config_Definition_Property_Abstract extends Object {

	/**
	 * @var string
	 */
	protected $_type;

	/**
	 * @var bool
	 */
	protected $_is_array = false;

	/**
	 * @var Config
	 */
	protected $_configuration;

	/**
	 * @var string
	 */
	protected $_configuration_class;

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var string
	 */
	protected $description = '';

	/**
	 * @var string
	 */
	protected $label = '';

	/**
	 * @var mixed
	 */
	protected $default_value = '';

	/**
	 * @var bool
	 */
	protected $is_required = false;

	/**
	 * @var string
	 */
	protected $error_message = '';

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
	 * @var array
	 */
	protected $form_field_options = array();

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
	 * @param string|Config $configuration_class_name
	 * @param string $name
	 * @param array $definition_data (optional)
	 *
	 * @return Config_Definition_Property_Abstract
	 */
	public function __construct( $configuration_class_name, $name, array $definition_data=null ) {
		if(is_object($configuration_class_name)) {
			/**
			 * @var Config $configuration_class_name
			 */
			$this->setConfiguration( $configuration_class_name );
		} else {
			$this->_configuration_class = $configuration_class_name;
		}


		$this->name = $name;

		$this->setUp($definition_data);
	}

	/**
	 * @param Config $configuration
	 */
	public function setConfiguration( Config $configuration ) {
		$this->_configuration = $configuration;
		$this->_configuration_class = get_class($configuration);
	}


	/**
	 * @return string
	 */
	public function __toString() {
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString() {
		return $this->_configuration_class.'::'.$this->getName();
	}

	/**
	 * @param array|null $definition_data
	 * @throws Config_Exception
	 */
	public function setUp(array $definition_data = null ) {
		if(!$definition_data) {
			return;
		}

		foreach($definition_data as $key=>$val) {
			if(!$this->getHasProperty($key)) {
				throw new Config_Exception(
						$this->_configuration_class.'::'.$this->name.': unknown definition option \''.$key.'\'  ',
						Config_Exception::CODE_DEFINITION_NONSENSE
					);
			}

			$this->{$key} = $val;
		}

		$this->is_required = (bool)$this->is_required;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->_type;
	}

	/**
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return bool
	 */
	public function getIsArray() {
		return $this->_is_array;
	}

	/**
	 * @param string $description
	 */
	public function setDescription( $description ) {
		$this->description = $description;
	}

	/**
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param mixed $default_value
	 */
	public function setDefaultValue($default_value) {
		$this->default_value = $default_value;
	}

	/**
	 * @return mixed
	 */
	public function getDefaultValue() {
		return $this->default_value;
	}

	/**
	 * @param bool $is_required
	 */
	public function setIsRequired($is_required) {
		$this->is_required = $is_required;
	}

	/**
	 * @return bool
	 */
	public function getIsRequired() {
		return $this->is_required;
	}


	/**
	 * @param string $error_message
	 */
	public function setErrorMessage($error_message) {
		$this->error_message = $error_message;
	}

	/**
	 * @return string
	 */
	public function getErrorMessage() {
		return $this->error_message;
	}

	/**
	 * @param string $label
	 */
	public function setLabel($label) {
		$this->label = $label;
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @param string $form_field_type
	 */
	public function setFormFieldType($form_field_type) {
		$this->form_field_type = $form_field_type;
	}

	/**
	 * @return string
	 */
	public function getFormFieldType() {
		return $this->form_field_type;
	}

	/**
	 * @param array $form_field_options
	 */
	public function setFormFieldOptions( array $form_field_options ) {
		$this->form_field_options = $form_field_options;
	}

	/**
	 * @return array
	 */
	public function getFormFieldOptions() {
		return $this->form_field_options;
	}

	/**
	 * @param string $form_field_label
	 */
	public function setFormFieldLabel($form_field_label) {
		$this->form_field_label = $form_field_label;
	}

	/**
	 * @return string
	 */
	public function getFormFieldLabel() {
		return $this->form_field_label;
	}


	/**
	 * @param callable $form_field_get_select_options_callback
	 */
	public function setFormFieldGetSelectOptionsCallback( callable $form_field_get_select_options_callback ) {
		$this->form_field_get_select_options_callback = $form_field_get_select_options_callback;
	}

	/**
	 *
	 * @return callable
	 */
	public function getFormFieldGetSelectOptionsCallback() {
		return $this->form_field_get_select_options_callback;
	}

	/**
	 * @param string $form_field_error_messages
	 */
	public function setFormFieldErrorMessages($form_field_error_messages) {
		$this->form_field_error_messages = $form_field_error_messages;
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
	 * @throws Config_Exception
	 *
	 * @return Form_Field_Abstract
	 */
	public function getFormField() {
		$type = $this->getFormFieldType();

		if(!$type) {
			return null;
		}

		if($this->form_field_get_default_value_callback) {
			$callback = $this->form_field_get_default_value_callback;
			$this->default_value = $callback();
		}

		$field = Form_Factory::getFieldInstance(
			$type,
			$this->name,
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
				$callback[0] = $this->_configuration_class;
			}

			if(!is_callable($callback)) {
				throw new Config_Exception($this->_configuration_class.'::'.$this->name.'::form_field_get_select_options_callback is not callable');
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

		if($this->default_value) {
			$res .= ', default value: '.$this->default_value;
		}

		if($this->description) {
			$res .= JET_EOL.JET_EOL.$this->description;
		}

		return $res;
	}

	/**
	 * Check data type by definition (retype)
	 *
	 * @param mixed &$value
	 */
	abstract function checkValueType( &$value );

	/**
	 * Check column (data) by definition (retype)
	 *  - type
	 *
	 * @param mixed &$value
	 *
	 * @return bool
	 * @throws Config_Exception
	 */
	public function checkValue( &$value ) {

		$this->checkValueType($value);

		if($this->_validateProperties_test_required( $value )) {
			/*if($this->list_of_valid_options) {
				$this->_validateProperties_test_validOptions( $value );
			} else */ {
				return $this->_validateProperties_test_value( $value );
			}
		}

		return true;
	}


	/**
	 * Property required test
	 *
	 * @param mixed &$value
	 *
	 * @throws Config_Exception
	 * @return bool
	 */
	protected function _validateProperties_test_required( &$value ) {
		if( !$this->is_required ) {
			return true;
		}

		if(!$value) {
			throw new Config_Exception(
				'Configuration property '.$this->_configuration_class.'::'.$this->name.' is required by definition, but value is missing!',
				Config_Exception::CODE_CONFIG_CHECK_ERROR
			);
		}

		return true;
	}

	/**
	 * Property value test - can be specific for each column type (eg: min and max value for number, string format ...)
	 *
	 * @param mixed &$value
	 *
	 * @return bool
	 */
	protected function _validateProperties_test_value(
		/** @noinspection PhpUnusedParameterInspection */
		&$value
	) {
		return true;
	}

	/**
	 * @param $data
	 *
	 * @return static
	 */
	public static function __set_state( $data ) {
		$i = new static( $data['_configuration_class'], $data['name'] );

		foreach( $data as $key=>$val ) {
			$i->{$key} = $val;
		}

		return $i;
	}


}