<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
abstract class Form_Field extends BaseObject implements \JsonSerializable
{

	use Form_Field_Trait_Render;


	const ERROR_CODE_EMPTY = 'empty';
	const ERROR_CODE_INVALID_FORMAT = 'invalid_format';

	/**
	 * @var string
	 */
	protected $_type = '';

	/**
	 *
	 * @var string
	 */
	protected $_name = '';

	/**
	 * @var Form
	 */
	protected $_form = null;

	/**
	 *
	 * @var mixed
	 */
	protected $_value_raw;

	/**
	 *
	 * @var mixed
	 */
	protected $_value;

	/**
	 *
	 * @var bool
	 */
	protected $_has_value = false;

	/**
	 *
	 * @var bool
	 */
	protected $is_valid = false;

	/**
	 *
	 * @var string
	 */
	protected $last_error_code = '';

	/**
	 * last validation error message
	 *
	 * @var string
	 */
	protected $last_error_message = '';


	/**
	 * form field default value
	 *
	 * @var mixed
	 */
	protected $default_value = '';

	/**
	 * @var string
	 */
	protected $label = '';

	/**
	 * @var string
	 */
	protected $placeholder = '';

	/**
	 * @var bool
	 */
	protected $is_required = false;

	/**
	 * @var bool
	 */
	protected $is_readonly = false;

	/**
	 * validation regexp
	 *
	 * @var string
	 */
	protected $validation_regexp;

	/**
	 * @var callable
	 */
	protected $validate_data_callback;

	/**
	 * @var callable
	 */
	protected $catch_data_callback;


	/**
	 * @return Form_Renderer_Pair
	 */
	protected $_tag_row;

	/**
	 * @return Form_Renderer_Single
	 */
	protected $_tag_label;

	/**
	 * @return Form_Renderer_Single
	 */
	protected $_tag_error;

	/**
	 * @return Form_Renderer_Pair
	 */
	protected $_tag_container;

	/**
	 * @return Form_Renderer_Single
	 */
	protected $_tag_input;


	/**
	 * @var array
	 */
	protected $error_messages = [
		self::ERROR_CODE_EMPTY => '', self::ERROR_CODE_INVALID_FORMAT => '',
	];


	/**
	 * Options for Select, MultiSelect, RadioButtons and so on ...
	 *
	 * @var array
	 */
	protected $select_options = [];


	/**
	 *
	 * @param string $name
	 * @param string $label
	 * @param string $default_value
	 * @param bool   $is_required
	 */
	public function __construct( $name, $label = '', $default_value = '', $is_required = false )
	{

		$this->_name = $name;
		$this->default_value = $default_value;
		$this->label = $label;
		$this->setIsRequired( $is_required );
		$this->setDefaultValue( $default_value );
	}

	/**
	 * Set field options
	 *
	 * @param array $options
	 *
	 * @throws Form_Exception
	 */
	public function setOptions( array $options )
	{
		foreach( $options as $o_k => $o_v ) {
			if( !$this->getObjectClassHasProperty( $o_k ) ) {
				throw new Form_Exception( 'Unknown form field option: '.$o_k );
			}

			$this->{$o_k} = $o_v;
		}
	}

	/**
	 * @return Form
	 */
	public function getForm()
	{
		return $this->_form;
	}

	/**
	 * set form instance
	 *
	 * @param Form $form
	 */
	public function setForm( Form $form )
	{
		$this->_form = $form;
	}

	/**
	 * @return array
	 */
	abstract public function getRequiredErrorCodes();

	/**
	 * Options for Select, MultiSelect and so on ...
	 *
	 * @return array
	 */
	public function getSelectOptions()
	{
		return $this->select_options;
	}

	/**
	 * Options for Select, MultiSelect and so on ...
	 *
	 * @param array|\Iterator $options
	 */
	public function setSelectOptions( $options )
	{
		if( is_object( $options ) ) {

			$_o = $options;
			$options = [];

			foreach( $_o as $k => $v ) {
				$options[$k] = $v;
			}
		}

		$this->select_options = $options;
	}

	/**
	 * Converts name to HTML ready name
	 *
	 * Example:
	 *
	 * name: /object/property/sub_property
	 *
	 * to: object[property][sub_property]
	 *
	 * @param string|null $name
	 *
	 * @return string
	 */
	public function getTagNameValue( $name = null )
	{
		if( !$name ) {
			$name = $this->getName();
		}

		if( $name[0]!='/' ) {
			return $name;
		}

		$name = explode( '/', $name );
		array_shift( $name );
		foreach( $name as $i => $np ) {
			if( $i>0 ) {
				if( substr( $np, -2 )=='[]' ) {
					$np = substr( $np, 0, -2 );
					$name[$i] = '['.$np.'][]';
				} else {
					$name[$i] = '['.$np.']';
				}
			}
		}

		return implode( '', $name );
	}

	/**
	 * returns field name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * @param string $name
	 */
	public function setName( $name )
	{
		$this->_name = $name;
	}

	/**
	 * returns form field default value
	 *
	 * @return string
	 */
	public function getDefaultValue()
	{
		return $this->default_value;
	}

	/**
	 * set form field default value
	 *
	 * @param string|array $default_value
	 */
	public function setDefaultValue( $default_value )
	{

		$this->default_value = $default_value;

		if( $default_value instanceof DataModel_Id ) {
			$default_value = $default_value->toString();
		}

		if( is_array( $default_value )||( is_object( $default_value )&&$default_value instanceof \Iterator ) ) {
			$this->_value = [];
			foreach( $default_value as $k => $v ) {
				if( is_object( $v )&&$v instanceof DataModel_Interface ) {
					/**
					 * @var DataModel $v
					 */
					$v = (string)$v->getIdObject();
				}
				if( is_array( $v ) ) {
					$v = $k;
				}

				$this->_value[] = trim( Data_Text::htmlSpecialChars( (string)$v ) );
			}
		} else {
			$this->_value = trim( Data_Text::htmlSpecialChars( $default_value ) );
		}

		$this->_value_raw = $default_value;
	}

	/**
	 * returns field label
	 *
	 * @return string
	 */
	public function getLabel()
	{
		return $this->getTranslation( $this->label );
	}

	/**
	 * set field cation
	 *
	 * @param string $label
	 */
	public function setLabel( $label )
	{
		$this->label = $label;
	}

	/**
	 * @see Translator
	 *
	 * @param string $phrase
	 * @param array  $data
	 *
	 * @return string
	 */
	public function getTranslation( $phrase, $data = [] )
	{
		return $this->_form->getTranslation( $phrase, $data );
	}

	/**
	 * @return string
	 */
	public function getPlaceholder()
	{
		return $this->getTranslation( $this->placeholder );
	}

	/**
	 * @param string $placeholder
	 */
	public function setPlaceholder( $placeholder )
	{
		$this->placeholder = $placeholder;
	}

	/**
	 * returns field is_required value
	 *
	 * @return bool
	 */
	public function getIsRequired()
	{
		return $this->is_required;
	}

	/**
	 * set field is_required value
	 *
	 * @param string $required
	 */
	public function setIsRequired( $required )
	{
		$this->is_required = (bool)$required;
	}


	/**
	 * returns error messages
	 *
	 * @return array
	 */
	public function getErrorMessages()
	{
		return $this->error_messages;
	}

	/**
	 * sets error messages
	 *
	 * @param array $error_messages
	 *
	 * @throws Form_Exception
	 */
	public function setErrorMessages( array $error_messages )
	{

		foreach( $error_messages as $key => $message ) {
			if( !array_key_exists( $key, $this->error_messages ) ) {
				throw new Form_Exception( 'Unknown form field error code: '.$key.'! Field: '.$this->_name );
			}

			$this->error_messages[$key] = $message;
		}
	}

	/**
	 *
	 * @param string $code
	 *
	 * @return string|bool
	 */
	public function getErrorMessage( $code )
	{
		$message = isset( $this->error_messages[$code] ) ? $this->error_messages[$code] : false;

		return $this->getTranslation( $message );

	}

	/**
	 *
	 * @param string $code
	 */
	public function setError( $code )
	{
		$this->is_valid = false;
		$this->last_error_code = $code;
		$this->last_error_message = $this->getErrorMessage( $code );
	}

	/**
	 *
	 * @param string $error_message
	 * @param string $error_code
	 */
	public function setCustomError( $error_message, $error_code='custom' )
	{
		$this->is_valid = false;
		$this->_form->setIsNotValid();
		$this->last_error_code = $error_code;
		$this->last_error_message = $error_message;
	}

	/**
	 *
	 * @return string
	 */
	public function getLastErrorCode()
	{
		return $this->last_error_code;
	}

	/**
	 *
	 * @return string
	 */
	public function getLastErrorMessage()
	{
		return $this->last_error_message;
	}

	/**
	 *
	 * @param bool $raw
	 *
	 * @return string
	 */
	public function getValidationRegexp( $raw=false )
	{
		if($raw) {
			return $this->validation_regexp;
		}

		$regexp = $this->validation_regexp;

		if( $regexp[0]=='/' ) {
			$regexp = substr( $regexp, 1 );
			$regexp = substr( $regexp, 0, strrpos( $regexp, '/' ) );
		}

		return $regexp;
	}

	/**
	 *
	 * @param string $validation_regexp
	 */
	public function setValidationRegexp( $validation_regexp )
	{
		$this->validation_regexp = $validation_regexp;
	}

	/**
	 * catch value from input (input = most often $_POST)
	 *
	 * @param Data_Array $data
	 */
	public function catchValue( Data_Array $data )
	{
		$this->_value = null;
		$this->_has_value = $data->exists( $this->_name );

		if( $this->_has_value ) {
			$this->_value_raw = $data->getRaw( $this->_name );
			$this->_value = trim( $data->getString( $this->_name ) );
		} else {
			$this->_value_raw = null;
		}
	}

	/**
	 *
	 * @return bool
	 */
	public function checkValueIsNotEmpty()
	{
		if( $this->_value===''&&$this->is_required ) {
			$this->setError( self::ERROR_CODE_EMPTY );

			return false;
		}

		return true;
	}

	/**
	 * validate value
	 *
	 * @return bool
	 */
	public function validateValue()
	{
		if( !$this->_validateFormat() ) {
			$this->setError( self::ERROR_CODE_INVALID_FORMAT );

			return false;
		}

		$this->_setValueIsValid();

		return true;
	}

	/**
	 * validate value by regex if validation_regexp is set
	 *
	 * @return bool|int
	 */
	protected function _validateFormat()
	{

		if( !$this->validation_regexp ) {
			return true;
		}

		if( !$this->is_required && $this->_value==='' ) {
			return true;
		}

		if( $this->validation_regexp[0]!='/' ) {
			return preg_match( '/'.$this->validation_regexp.'/', $this->_value );
		} else {
			return preg_match( $this->validation_regexp, $this->_value );
		}

	}

	/**
	 * set value is OK
	 */
	protected function _setValueIsValid()
	{
		$this->is_valid = true;
		$this->last_error_code = false;
		$this->last_error_message = false;
	}

	/**
	 * returns true if field is valid
	 *
	 * @return bool
	 */
	public function isValid()
	{
		return $this->is_valid;
	}

	/**
	 *
	 */
	public function catchData()
	{
		if( $this->getIsReadonly()||!$this->getHasValue() ) {
			return;
		}

		if( !( $callback = $this->getCatchDataCallback() ) ) {
			return;
		}

		$callback( $this->getValue() );
	}

	/**
	 * @return bool
	 */
	public function getIsReadonly()
	{
		return $this->is_readonly;
	}

	/**
	 * @param bool $is_readonly
	 */
	public function setIsReadonly( $is_readonly )
	{
		$this->is_readonly = $is_readonly;
	}

	/**
	 * is there value in input? (input = most often $_POST)
	 *
	 * @return bool
	 */
	public function getHasValue()
	{
		return $this->_has_value;
	}

	/**
	 * @return callable
	 */
	public function getCatchDataCallback()
	{
		return $this->catch_data_callback;
	}

	/**
	 * @param callable $catch_data_callback
	 */
	public function setCatchDataCallback( callable $catch_data_callback )
	{
		$this->catch_data_callback = $catch_data_callback;
	}

	/**
	 * returns field value
	 *
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->_value;
	}

	/**
	 * @return array
	 */
	public function jsonSerialize()
	{

		$vars = [];

		$vars['id'] = $this->getId();

		foreach( get_object_vars( $this ) as $k => $v ) {
			if( $k=='_type' ) {
				$vars['type'] = $v;
				continue;
			}

			if( $k[0]!='_' ) {
				$vars[$k] = $v;
			}
		}

		return $vars;
	}

	/**
	 * Returns field id
	 *
	 * @return string
	 */
	public function getId()
	{
		return $this->_form->getId().'__'.str_replace( '/', '___', $this->getName() );
	}

	/**
	 * @return mixed
	 */
	public function getValueRaw()
	{
		return $this->_value_raw;
	}

	/**
	 * @return callable
	 */
	public function getValidateDataCallback()
	{
		return $this->validate_data_callback;
	}

	/**
	 * @param callable $validate_data_callback
	 */
	public function setValidateDataCallback( $validate_data_callback )
	{
		$this->validate_data_callback = $validate_data_callback;
	}

}
