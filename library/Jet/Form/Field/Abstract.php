<?php
/**
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
 * @package Form
 */
namespace Jet;

abstract class Form_Field_Abstract extends Object implements \JsonSerializable {

	/**
	 * @var string
	 */
	protected $_type = '';

	/**
	 * @var bool
	 */
	protected $_possible_to_decorate = true;

	/**
	 * filed name equals $_POST(or $_GET) key
	 * 
	 * @var string
	 */
	protected $_name = '';
	
	/**
	 * @var Form
	 */
	protected $__form = null;
	/**
	 * form name
	 * 
	 * @var string 
	 */
	protected $__form_name = '';

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
	protected $_is_valid = false;

	/**
	 * last validation error key (key = this->error_messages hash key)
	 *
	 * @var string
	 */
	protected $_last_error = '';

	/**
	 * last validation error message
	 *
	 * @var string
	 */
	protected $_last_error_message = '';

	
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
	 * @var bool
	 */
	protected $is_required = false;
	
	/**
	 * validation regexp
	 * 
	 * @var string
	 */
	protected $validation_regexp = null;

	/**
	 * @var callable
	 */
	protected $validate_data_callback = null;

	/**
	 * @var array
	 */
	protected $error_messages = [
			'empty' => 'empty',
			'invalid_format' => 'invalid_format'
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
	 * @param bool $is_required
	 * @param string $validation_regexp
	 * @param array $error_messages
	 */
	public function __construct(
				$name, 
				$label='', 
				$default_value='', 
				$is_required=false,
				$validation_regexp=null, 
				array $error_messages = []
			) {

		$this->_name = $name;
		$this->default_value = $default_value;
		$this->label = $label;
		$this->setIsRequired($is_required);
		if($validation_regexp) {
			$this->validation_regexp = $validation_regexp;
		}
		$this->setErrorMessages($error_messages);
		$this->setDefaultValue( $default_value );
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->_name = $name;
	}  

	/**
	 * Set field options
	 *
	 * @param array $options
	 */
	public function setOptions( array $options ) {
		foreach($options as $o_k=>$o_v) {
			if(!$this->getHasProperty($o_k)) {
				//TODO: zarvat
				continue;
			}

			$this->{$o_k} = $o_v;
		}
	}

	/**
	 * set form instance
	 * 
	 * @param Form $form
	 */
	public function setForm(Form $form) {
		$this->__form = $form;
		$this->__form_name = $form->getName();
	}
		
	/**
	 * returns field name
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * Returns field ID
	 *
	 * @return string
	 */
	public function getID() {
		return $this->__form->getID().'__'.str_replace('/', '___', $this->getName());
	}

	/**
	 * Options for Select, MultiSelect and so on ...
	 *
	 * @return array
	 */
	public function getSelectOptions() {
		return $this->select_options;
	}

	/**
	 * Options for Select, MultiSelect and so on ...
	 *
	 * @param array|\Iterator $options
	 */
	public function setSelectOptions( $options) {
		if(is_object($options)) {

			$_o = $options;
			$options = [];

			foreach($_o as $k=>$v) {
				$options[$k] = (string)$v;
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
	 * @param $name
	 *
	 * @return string
	 */
	public function getNameTagValue( $name ) {
		if($name[0]!='/') {
			return $name;
		}

		$name=explode('/', $name);
		array_shift($name);
		foreach($name as $i=>$np) {
			if($i>0) {
				if(substr($np, -2)=='[]') {
					$np = substr($np, 0, -2);
					$name[$i] = '['.$np.'][]';
				} else {
					$name[$i] = '['.$np.']';
				}
			}
		}
		return implode('', $name);

	}

	/**
	 * returns form field default value
	 * 
	 * @return string
	 */
	public function getDefaultValue() {
		return $this->default_value;	
	}
	
	/**
	 * set form field default value
	 * 
	 * @param string|array $default_value
	 */
	public function setDefaultValue( $default_value ) {

		$this->default_value = $default_value;

        if( $default_value instanceof DataModel_ID_Abstract ) {
            $default_value = $default_value->toString();
        }

		if(
			is_array($default_value) ||
			(
				is_object($default_value) &&
				$default_value instanceof \Iterator
			)
		) {
			$this->_value = [];
			foreach($default_value as $k=>$v) {
				if(
					is_object($v) &&
					$v instanceof DataModel
				) {
					/**
					 * @var DataModel $v
					 */
					$v = (string)$v->getID();
				}
				if(is_array($v)) {
					$v = $k;
				}

				$this->_value[] = trim(Data_Text::htmlSpecialChars(  (string)$v  ));
			}
		} else {
			$this->_value = trim(Data_Text::htmlSpecialChars($default_value));
		}

		$this->_value_raw = $default_value;
	}
	
	/**
	 * returns field label
	 * 
	 * @return string
	 */
	public function getLabel() {
		return $this->label;	
	}
	
	/**
	 * set field cation
	 * 
	 * @param string $label
	 */
	public function setLabel( $label ) {
		$this->label = $label;
	}
	
	/**
	 * returns field is_required value
	 * 
	 * @return bool
	 */
	public function getIsRequired() {
		return $this->is_required;
	}
	
	/**
	 * set field is_required value
	 * 
	 * @param string $required
	 */
	public function setIsRequired( $required ) {
		$this->is_required = (bool)$required;
	}

	/**
	 * returns validation format regexp
	 * 
	 * @return string
	 */
	public function getValidationRegexp() {
		return $this->validation_regexp;	
	}
	
	/**
	 * set validation format regexp
	 * 
	 * @param string $validation_regexp
	 */
	public function setValidationRegexp( $validation_regexp ) {
		$this->validation_regexp = $validation_regexp;
	}
	
	
	/**
	 * sets error messages
	 * @param array $error_messages
	 */
	public function setErrorMessages(array $error_messages) {
		foreach($error_messages as $key=>$message) {
			//TODO: overit platnost chyboveho kodu
			$this->error_messages[$key] = $message;
		}
	}

	/**
	 * returns error messages
	 * 
	 * @return array
	 */
	public function getErrorMessages() {
		return $this->error_messages;
	}
	
	/**
	 * returns error message text or false if does not exist
	 * 
	 * @param string $key
	 * 
	 * @return string|bool
	 */
	public function getErrorMessage($key) {
		$message = isset($this->error_messages[$key]) ?
					$this->error_messages[$key]
					:
					false;
		
		return $message;
	}

	/**
	 * catch value from input (input = most often $_POST)
	 * 
	 * @param Data_Array $data
	 */
	public function catchValue( Data_Array $data ) {
		$this->_value = null;
		$this->_has_value = $data->exists($this->_name);
		
		if($this->_has_value) {
			$this->_value_raw = $data->getRaw($this->_name);
			$this->_value = trim( $data->getString($this->_name) );
		} else {
			$this->_value_raw = null;
		}
	}
	
	/**
	 * returns false if value is is_required and is empty
	 * 
	 * @return bool
	 */
	public function checkValueIsNotEmpty() {
		if($this->_value==='' && $this->is_required) {
			$this->setValueError('empty');
			return false;	
		}
		
		return true;
	}
	
	/**
	 * is there value in input? (input = most often $_POST)
	 * 
	 * @return bool
	 */
	public function getHasValue() {
		return $this->_has_value;
	}
	
	/**
	 * validate value
	 *
	 * @return bool
	 */
	public function validateValue() {
		if(!$this->_validateFormat()) {
			$this->setValueError('invalid_format');
			return false;
		}
		
		$this->_setValueIsValid();
		
		return true;
	}
	
	/**
	 * returns field value
	 * 
	 * @return mixed
	 */
	public function getValue() {
		return $this->_value;
	}
	
	/**
	 * returns true if field is valid
	 * 
	 * @return bool
	 */
	public function isValid() {
		return $this->_is_valid;
	}
	
	/**
	 * return last error key (key = this->error_messages hash key)
	 * 
	 * @return string
	 */
	public function getLastError() {
		return $this->_last_error;
	}
	
	/**
	 * return last error message
	 * 
	 * @return string
	 */
	public function getLastErrorMessage() {
		return $this->_last_error_message;
	}

	/**
	 * set value is OK
	 */
	protected function _setValueIsValid() {
		$this->_is_valid = true;
		$this->_last_error = false;
		$this->_last_error_message = false;
	}
	
	/**
	 * set error status
	 * 
	 * @param string $key
	 */
	public function setValueError($key) {
		$this->_is_valid = false;
		$this->_last_error = $key;
		$this->_last_error_message = $this->getErrorMessage($key);
	}

	/**
	 * Set error directly
	 *
	 * @param
	 */
	public function setErrorMessage($error_message) {
		$this->_is_valid = false;
		$this->__form->setIsNotValid();
		$this->_last_error = $error_message;
		$this->_last_error_message = $error_message;
	}
		
	/**
	 * validate value by regex if validation_regexp is set
	 *
	 * @return bool|int
	 */
	protected function _validateFormat() {
		if(!$this->validation_regexp) {
			return true;
		}

		if(!$this->is_required && $this->_value==='') {
			return true;
		}

		if($this->validation_regexp[0]!='/') {
			return preg_match('/'.$this->validation_regexp.'/', $this->_value);
		} else {
			return preg_match($this->validation_regexp, $this->_value);
		}

	}
	


	/**
	 * replace magic mf_form* tags by real HTML for this form field
	 *
	 * @param Form_Parser_TagData $tag_data
	 *
	 * @return string
	 */
	public function getReplacement( Form_Parser_TagData $tag_data ) {

		$method_name = str_replace(':', '_', '_getReplacement_'.$tag_data->getTag() );
		return $this->{$method_name}($tag_data);
	}

	/**
	 * @param Form_Parser_TagData $tag_data
	 *
	 * @return string
	 */
	protected function _getReplacement_field_label( Form_Parser_TagData $tag_data ) {
		$label = $this->label;

		if(!$label) {
			$label = $this->_name.': ';
		}

		$label = $this->getTranslation( $label );

		if(
			$this->is_required &&
			$label
		) {
			$label = Data_Text::replaceData($this->__form->getTemplate_field_required(), ['LABEL'=>$label]);
		}

		$tag_data->setProperty('for', $this->getID());

		return '<label '.$this->_getTagPropertiesAsString( $tag_data ).'>'.$label.'</label>';
	}

	/**
	 * @param Form_Parser_TagData $tag_data
	 *
	 * @return string
	 */
	protected function _getReplacement_field_error_msg( /** @noinspection PhpUnusedParameterInspection */
		Form_Parser_TagData $tag_data ) {
		$msg = $this->getLastErrorMessage();
		if(!$msg) {
			return '';
		}

		$msg = $this->getTranslation($msg);

		$template = $this->__form->getTemplate_field_error_msg();

		return Data_Text::replaceData($template, ['ERROR_MSG'=>$msg]);
	}

	/**
	 * @param Form_Parser_TagData $tag_data
	 *
	 * @return string
	 */
	protected function _getReplacement_field( Form_Parser_TagData $tag_data ) {

		$tag_data->setProperty( 'name', $this->getName() );
		$tag_data->setProperty( 'id', $this->getID() );
		$tag_data->setProperty( 'type', 'text' );
		$tag_data->setProperty( 'value', $this->getValue() );


		return '<input '.$this->_getTagPropertiesAsString($tag_data).'/>';
	}

	/**
	 * @param Form_Parser_TagData $tag_data
	 *
	 * @return string
	 */
	protected function _getTagPropertiesAsString( Form_Parser_TagData $tag_data ) {
		if($this->_possible_to_decorate) {
			$decorator = $this->__form->getDecoratorInstance( $this );
			if($decorator) {
				/**
				 * @var Form_Decorator_Abstract $decorator
				 */
				$decorator->decorate( $tag_data );
			}
		}

		$result = '';

		foreach($tag_data->getProperties() as $property=>$val) {
			if($property=='name') {
				$val = $this->getNameTagValue( $val );
			}

			if($property=='value') {
				$result .= ' '.$property.'="'.$val.'"';
			} else {
				$result .= ' '.$property.'="'.Data_Text::htmlSpecialChars($val).'"';
			}

		}

		return $result;
	}


	/**
	 * @param null|string $template (optional)
	 *
	 * @return string
	 */
	public function helper_getBasicHTML($template=null) {

		if(!$template) {
			$template = $this->__form->getTemplate_field();
		}

		return Data_Text::replaceData($template, [
			'LABEL' => '<jet_form_field_label name="'.$this->_name.'"/>',
			'FIELD' => '<jet_form_field_error_msg name="'.$this->_name.'" class="form-error"/>'.JET_EOL
					.JET_TAB.JET_TAB.JET_TAB.'<jet_form_field name="'.$this->_name.'" class="form-control"/>'
		]);
	}

	/**
	 * @return array
	 */
	public function jsonSerialize() {

		$vars = [];

		$vars['ID'] = $this->getID();

		foreach(get_object_vars($this) as $k=>$v) {
			if($k=='_type') {
				$vars['type'] = $v;
				continue;
			}

			if($k[0]!='_') {
				$vars[$k] = $v;
			}
		}

		return $vars;
	}

	/**
	 * @return mixed
	 */
	public function getValueRaw() {
		return $this->_value_raw;
	}

	/**
	 * @param callable $validate_data_callback
	 */
	public function setValidateDataCallback($validate_data_callback) {
		$this->validate_data_callback = $validate_data_callback;
	}

	/**
	 * @return callable
	 */
	public function getValidateDataCallback() {
		return $this->validate_data_callback;
	}

	/**
	 * @see Translator
	 *
	 * @param string $phrase
	 * @param array $data
	 *
	 * @return string
	 */
	public function getTranslation( $phrase, $data= []) {
		return $this->__form->getTranslation($phrase, $data );
	}
}