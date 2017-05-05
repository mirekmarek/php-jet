<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Form_Field_Abstract
 * @package Jet
 */
abstract class Form_Field_Abstract extends BaseObject implements \JsonSerializable
{
	const ERROR_CODE_EMPTY = 'empty';
	const ERROR_CODE_INVALID_FORMAT = 'invalid_format';

	/**
	 * @var string
	 */
	protected $_type = '';

	/**
	 * @var string
	 */
	protected $_input_type = 'text';

	/**
	 * filed name equals $_POST(or $_GET) key
	 *
	 * @var string
	 */
	protected $_name = '';

	/**
	 * @var Form
	 */
	protected $_form = null;
	/**
	 * form name
	 *
	 * @var string
	 */
	protected $_form_name = '';

	/**
	 * raw value from input (input = most often $_POST)
	 * @var mixed
	 */
	protected $_value_raw;

	/**
	 * processed value from input (input = most often $_POST)
	 *
	 * @var mixed
	 */
	protected $_value;

	/**
	 * is there value in input? (input = most often $_POST)
	 * @var bool
	 */
	protected $_has_value = false;

	/**
	 *
	 * @var bool
	 */
	protected $is_valid = false;

	/**
	 * last validation error key (key = this->error_messages hash key)
	 *
	 * @var string
	 */
	protected $last_error = '';

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
	 * @return Form_Renderer_Abstract_Container|Form_Renderer_Bootstrap_Container
	 */
	protected $_tag_container;

	/**
	 * @return Form_Renderer_Abstract_Label|Form_Renderer_Bootstrap_Label
	 */
	protected $_tag_label;

	/**
	 * @return Form_Renderer_Abstract_ErrorMessage|Form_Renderer_Bootstrap_ErrorMessage
	 */
	protected $_tag_error;

	/**
	 * @return Form_Renderer_Abstract_Field_Abstract|Form_Renderer_Bootstrap_Field_Abstract
	 */
	protected $_tag_field;


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
		$this->_form_name = $form->getName();
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

		if( $default_value instanceof DataModel_Id_Abstract ) {
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
	 * returns validation format regexp
	 *
	 * @return string
	 */
	public function getValidationRegexp()
	{
		return $this->validation_regexp;
	}

	/**
	 * set validation format regexp
	 *
	 * @param string $validation_regexp
	 */
	public function setValidationRegexp( $validation_regexp )
	{
		$this->validation_regexp = $validation_regexp;
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
	 * returns false if value is is_required and is empty
	 *
	 * @return bool
	 */
	public function checkValueIsNotEmpty()
	{
		if( $this->_value===''&&$this->is_required ) {
			$this->setValueError( self::ERROR_CODE_EMPTY );

			return false;
		}

		return true;
	}

	/**
	 * set error status
	 *
	 * @param string $key
	 */
	public function setValueError( $key )
	{
		$this->is_valid = false;
		$this->last_error = $key;
		$this->last_error_message = $this->getErrorMessage( $key );
	}

	/**
	 * returns error message text or false if does not exist
	 *
	 * @param string $key
	 *
	 * @return string|bool
	 */
	public function getErrorMessage( $key )
	{
		$message = isset( $this->error_messages[$key] ) ? $this->error_messages[$key] : false;

		return $this->getTranslation( $message );

	}

	/**
	 * validate value
	 *
	 * @return bool
	 */
	public function validateValue()
	{
		if( !$this->_validateFormat() ) {
			$this->setValueError( self::ERROR_CODE_INVALID_FORMAT );

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

		if( !$this->is_required&&$this->_value==='' ) {
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
		$this->last_error = false;
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
	 * return last error key (key = this->error_messages hash key)
	 *
	 * @return string
	 */
	public function getLastError()
	{
		return $this->last_error;
	}

	/**
	 * return last error message
	 *
	 * @return string
	 */
	public function getLastErrorMessage()
	{
		return $this->last_error_message;
	}

	/**
	 * Set error directly
	 *
	 * @param
	 */
	public function setErrorMessage( $error_message )
	{
		$this->is_valid = false;
		$this->_form->setIsNotValid();
		$this->last_error = $error_message;
		$this->last_error_message = $error_message;
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

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * @return string
	 */
	public function render()
	{

		try {

			return $this->container().$this->error().$this->label().$this->field().$this->container()->end();
		} catch( Exception $e ) {
			var_dump( $e );
			die( 'ERROR' );
		}
	}

	/**
	 * @return Form_Renderer_Abstract_Container|Form_Renderer_Bootstrap_Container
	 */
	public function container()
	{
		if( !$this->_tag_container ) {
			$this->_tag_container = $this->_getRenderer( 'Container' );
		}

		return $this->_tag_container;
	}

	/**
	 * @param string $element
	 *
	 * @return Form_Renderer_Abstract_Tag
	 */
	protected function _getRenderer( $element )
	{
		$class_name = $this->_form->getRendererClassName().'_'.$element;

		return new $class_name( $this );
	}

	/**
	 * @return Form_Renderer_Abstract_ErrorMessage|Form_Renderer_Bootstrap_ErrorMessage
	 */
	public function error()
	{
		if( !$this->_tag_error ) {
			$this->_tag_error = $this->_getRenderer( 'ErrorMessage' );
		}

		return $this->_tag_error;
	}

	/**
	 * @return Form_Renderer_Abstract_Label|Form_Renderer_Bootstrap_Label
	 */
	public function label()
	{
		if( !$this->_tag_label ) {
			$this->_tag_label = $this->_getRenderer( 'Label' );
		}

		return $this->_tag_label;
	}

	/**
	 * @return Form_Renderer_Abstract_Field_Abstract|Form_Renderer_Bootstrap_Field_Abstract
	 */
	public function field()
	{
		if( !$this->_tag_field ) {
			$this->_tag_field = $this->_getRenderer( 'Field_'.$this->_type );
		}

		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return $this->_tag_field;
	}
}


