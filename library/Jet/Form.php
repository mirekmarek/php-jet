<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

class Form extends BaseObject {

    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    const ENCTYPE_URL_ENCODED = 'application/x-www-form-urlencoded';
    const ENCTYPE_FORM_DATA = 'multipart/form-data';
    const ENCTYPE_TEXT_PLAIN = 'text/plain';

	const TYPE_HIDDEN = 'Hidden';

	const TYPE_INPUT = 'Input';

	const TYPE_INT = 'Int';
	const TYPE_FLOAT = 'Float';
	const TYPE_RANGE = 'Range';

	const TYPE_DATE = 'Date';
	const TYPE_DATE_TIME = 'DateTime';
	const TYPE_MONTH = 'Month';
	const TYPE_WEEK = 'Week';
	const TYPE_TIME = 'Time';

	const TYPE_EMAIL = 'Email';
	const TYPE_TEL = 'Tel';

	const TYPE_URL = 'Url';
	const TYPE_SEARCH = 'Search';

	const TYPE_COLOR = 'Color';

	const TYPE_SELECT = 'Select';
	const TYPE_MULTI_SELECT = 'MultiSelect';

	const TYPE_CHECKBOX = 'Checkbox';
	const TYPE_RADIO_BUTTON = 'RadioButton';

	const TYPE_TEXTAREA = 'Textarea';
	const TYPE_WYSIWYG = 'WYSIWYG';

	const TYPE_REGISTRATION_USER_NAME = 'RegistrationUsername';
	const TYPE_REGISTRATION_EMAIL = 'RegistrationEmail';
	const TYPE_REGISTRATION_PASSWORD = 'RegistrationPassword';
	const TYPE_PASSWORD = 'Password';

	const TYPE_FILE = 'File';
	const TYPE_FILE_IMAGE = 'FileImage';

	const FORM_SENT_KEY = '_jet_form_sent_';

	/**
	 * Form name
	 * @var string $name
	 */	
	protected $name = '';

	/**
	 * @var string $name
	 */
	protected $id = '';

	/**
	 * POST (default) or GET
	 *
	 * @var string
	 */
	protected $method = self::METHOD_POST;

    /**
     * @var string
     */
    protected $enctype = '';

    /**
     * @var string
     */
    protected $action = '';

    /**
     * @var string
     */
    protected $target = '';

    /**
     * @var string
     */
    protected $accept_charset = '';

    /**
     * @var bool
     */
    protected $novalidate = false;

    /**
     * @var bool
     */
    protected $autocomplete = true;

	/**
	 * Form fields
	 *
	 * @var Form_Field_Abstract[]
	 */
	protected $fields= [];
	
	/**
	 * @var bool
	 */
	protected $is_valid = false;

	/**
	 * @var Data_Array
	 */
	protected $raw_data;


	/**
	 * Common error message (without field context)
	 *
	 * @var string
	 */
	protected $common_message = '';

	/**
	 * @var bool
	 */
	protected $do_not_translate_texts = false;

	/**
	 * @var string|null
	 */
	protected $custom_translator_namespace = null;

	/**
	 * @var Locale|null
	 */
	protected $custom_translator_locale = null;

	/**
	 * @var bool
	 */
	protected $is_readonly = false;

	/**
	 * @var string
	 */
	protected $renderer_class_name;

	/**
	 * @var int
	 */
	protected $default_label_width = 4;

	/**
	 * @var int
	 */
	protected $default_field_width = 8;

	/**
	 * @var string
	 */
	protected $default_size = 'md';

	/**
	 * @var Form_Renderer_Abstract_Form|Form_Renderer_Bootstrap_Form
	 */
	protected $_tag;

    /**
     * @var string
     */
    protected static $default_renderer_class_name = __NAMESPACE__.'\Form_Renderer_Bootstrap';
	
	/**
	 * constructor
	 * 
	 * @param string $name
	 * @param Form_Field_Abstract[] $fields
	 * @param string $method - POST or GET (optional, default: POST)
	 */
	public function __construct( $name, array $fields, $method=self::METHOD_POST ) {
		$this->name = $name;			
		$this->method = $method;
		$this->setFields($fields);
	}

    /**
     * @return string
     */
    public static function getDefaultRendererClassName()
    {
        return self::$default_renderer_class_name;
    }

    /**
     * @param string $default_renderer_class_name
     */
    public static function setDefaultRendererClassName($default_renderer_class_name)
    {
        self::$default_renderer_class_name = $default_renderer_class_name;
    }

	/**
	 *
	 */
	public function setIsReadonly()
	{
		$this->is_readonly = true;

		foreach( $this->getFields() as $field ) {
			$field->setIsReadonly(true);
		}
	}

	/**
	 * @return bool
	 */
	public function getIsReadonly()
	{
		return $this->is_readonly;
	}

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $enctype
     */
    public function setEnctype($enctype)
    {
        $this->enctype = $enctype;
    }

    /**
     * @return string
     */
    public function getEnctype()
    {
        return $this->enctype;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param string $accept_charset
     */
    public function setAcceptCharset($accept_charset)
    {
        $this->accept_charset = $accept_charset;
    }

    /**
     * @return string
     */
    public function getAcceptCharset()
    {
        return $this->accept_charset;
    }

    /**
     * @param bool $novalidate
     */
    public function setNovalidate($novalidate)
    {
        $this->novalidate = (bool)$novalidate;
    }

    /**
     * @return bool|null
     */
    public function getNovalidate()
    {
        return $this->novalidate;
    }

    /**
     * @param bool $autocomplete
     */
    public function setAutocomplete($autocomplete)
    {
        $this->autocomplete = $autocomplete;
    }

    /**
     * @return bool
     */
    public function getAutocomplete()
    {
        return $this->autocomplete;
    }

	/**
	 * Get form name
	 * @return string
	 */
	public function getName(){
		return $this->name;
	}


	/**
	 *
	 * @return string
	 */
	public function getId() {
		return $this->name;
	}

	/**
	 * @return int
	 */
	public function getDefaultLabelWidth()
	{
		return $this->default_label_width;
	}

	/**
	 * @param int $default_label_width
	 */
	public function setDefaultLabelWidth($default_label_width)
	{
		$this->default_label_width = $default_label_width;
	}

	/**
	 * @return int
	 */
	public function getDefaultFieldWidth()
	{
		return $this->default_field_width;
	}

	/**
	 * @param int $default_field_width
	 */
	public function setDefaultFieldWidth($default_field_width)
	{
		$this->default_field_width = $default_field_width;
	}

	/**
	 * @return string
	 */
	public function getDefaultSize()
	{
		return $this->default_size;
	}

	/**
	 * @param string $default_size
	 */
	public function setDefaultSize($default_size)
	{
		$this->default_size = $default_size;
	}


	/**
	 * set form fields
	 *
	 * @param Form_Field_Abstract[] $fields
	 *
	 * @throws Form_Exception
	 */
	public function setFields(array $fields) {
		$this->fields = [];
		
		foreach($fields as $field) {
			$this->addField($field);
		}
	}

	/**
	 * @param Form_Field_Abstract $field
	 */
	public function addField( Form_Field_Abstract $field ) {
		$field->setForm($this);

		$key=$field->getName();
		$field->setForm($this);
		$this->fields[$key]=$field;

	}

	/**
	 * returns language independent fields
	 *
	 * @param bool $as_multidimensional_array (optional, default: false)
	 * @return Form_Field_Abstract[]
	 */
	public function getFields( $as_multidimensional_array=false ){
		if($as_multidimensional_array) {
			$fields = new Data_Array();

			foreach( $this->fields as $field ) {
				$fields->set( $field->getName(), $field );
			}

			return $fields->getRawData();

		}

		return $this->fields;
	}

	/**
	 *
	 * @param string $name
	 *
	 * @throws Form_Exception
	 * @return Form_Field_Abstract
	 */
	public function getField($name) {
		if(!isset($this->fields[$name])) {
			throw new Form_Exception(
				'Unknown field \''.$name.'\'',
				Form_Exception::CODE_UNKNOWN_FIELD
			);
		}

		return $this->fields[$name];
	}

	/**
	 *
	 * @alias getField
	 *
	 * @param string $name
	 *
	 * @throws Form_Exception
	 * @return Form_Field_Abstract
	 */
	public function field($name) {
		return $this->getField($name);
	}

    /**
     * @param string $field_name
     */
    public function removeField( $field_name ) {
        if(isset($this->fields[$field_name])) {
            unset($this->fields[$field_name]);
        }
    }

    /**
	 * @param $name
	 * @param Form_Field_Abstract $field
	 */
	public function setField( $name, Form_Field_Abstract $field ) {
		$this->fields[$name] = $field;
		$field->setForm($this);
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function fieldExists($name ) {
		return isset($this->fields[$name]);
	}

	/**
	 * @throws Form_Exception
	 */
	protected function checkFieldsHasErrorMessages() {
		foreach( $this->fields as $field ) {
			$required_error_codes = $field->getRequiredErrorCodes();

			foreach( $required_error_codes as $code ) {
				if(!$field->getErrorMessage($code)) {
					throw new Form_Exception('Form field error message is not set. Form:'.$this->name.' Field:'. $field->getName().' Error code:'.$code);
				}
			}
		}
	}


	/**
	 * catch values from input ($_POST is default)
	 * and return true if form sent ...
	 *
	 * @param array $data
	 * @param bool $force_catch
	 *
	 * @return bool
	 */
	public function catchValues( $data=null, $force_catch=false ) {
		$this->is_valid = false;
		
		if($data===null) {
			$data = $this->method==self::METHOD_GET ? Http_Request::GET()->getRawData() : Http_Request::POST()->getRawData();
		}

		if($data===false) {
			$data = [];
		}

		if(!$data instanceof Data_Array) {
			$data = new Data_Array($data);
		}
			
		if(
			!$force_catch &&
			$data->getString(self::FORM_SENT_KEY)!=$this->name
		) {
			return false;
		}

		foreach($this->fields as $field) {
			if($field->getIsReadonly()) {
				continue;
			}
			$field->catchValue($data);
		}

		$this->raw_data = $data;

		return true;
	}

	/**
	 * validate form values
	 *
	 * @return bool
	 */
	public function validateValues() {
		$this->checkFieldsHasErrorMessages();

		$this->common_message = '';
		$this->is_valid = true;
		foreach($this->fields as $field) {
			if($field->getIsReadonly()) {
				continue;
			}

			$callback = $field->getValidateDataCallback();
			if($callback) {
				if(!$callback( $field )) {
					$this->is_valid = false;
				}

				continue;
			}

			if(!$field->checkValueIsNotEmpty()) {
				$this->is_valid = false;
				continue;
			}
			
			if(!$field->validateValue()) {
				$this->is_valid = false;
			}

		}


		return $this->is_valid;
	}

	/**
	 * Force invalidate
	 */
	public function setIsNotValid() {
		$this->is_valid = false;
	}

	/**
	 *
	 * @return bool
	 */
	public function getIsValid() {
		return $this->is_valid;
	}

	/**
	 * @param $message
	 */
	public function setCommonMessage($message ) {
		$this->common_message = $message;
		$this->is_valid = false;
	}

	/**
	 *
	 * @return string
	 */
	public function getCommonMessage() {
		return $this->common_message;
	}


	
	/**
	 * get all errors in form
	 * 
	 * @return array
	 */
	public function getAllErrors() {
		$result = [];

		foreach($this->fields as $key=>$field) {
			$last_error = $field->getLastErrorMessage();
			
			if($last_error) {
				$result[$key] = $last_error;
			}
		}
		
		return $result;
	}


	/**
	 * @return Data_Array
	 */
	public function getRawData() {
		return $this->raw_data;
	}

	/**
	 * returns field values if form is valid otherwise false
	 *
	 * @param bool $escape_values - example: for database usage *
	 * @param bool $force_skip_is_valid
	 *
	 * @return array|bool
	 */
	public function getValues( $escape_values = false, $force_skip_is_valid = false ) {
		if(!$this->is_valid && !$force_skip_is_valid) {
			return false;
		}
			
		$result = [];
		foreach($this->fields as $key=>$field) {
			if(
				$field->getIsReadonly() ||
				!$field->getHasValue()
			) {
				continue;
			}

			$value = $field->getValue();
			
			if($escape_values) {
				if(is_string($value)) {
					$value = addslashes($value);
				} else {
					if(is_bool($value)) {
						$value = $value ? 1:0;
					}
				}
			}
			
			$result[$key] = $value;
		}
		
		return $result;
	}

	/**
	 * @param bool $force_skip_is_valid
	 *
	 * @return Data_Array|null
	 */
	public function getData( $force_skip_is_valid=false ) {
		if(!$this->is_valid && !$force_skip_is_valid) {
			return null;
		}

		$data = new Data_Array();

		foreach($this->fields as $key=>$field) {
			if(
				$field->getIsReadonly() ||
				!$field->getHasValue()
			) {
				continue;
			}

			$value = $field->getValue();

			$data->set($key, $value);
		}

		return $data;
	}

    /**
     *
     * @return bool
     */
    public function catchData() {
        if(!$this->is_valid) {
            return false;
        }

        foreach($this->fields as $field) {
	        $field->catchData();
        }

        return true;
    }


	/**
	 * Returns translation. Used by field, error messages and so on.
	 *
	 * @see Translator
	 *
	 * @param string $phrase
	 * @param array $data
	 *
	 * @return string
	 */
	public function getTranslation( $phrase, $data= []) {
		if(!$phrase) {
			return $phrase;
		}
		if($this->do_not_translate_texts) {
			return $phrase;
		}

		return Tr::_($phrase, $data, $this->custom_translator_namespace, $this->custom_translator_locale);
	}

	/**
	 * @param bool $do_not_translate_texts
	 */
	public function setDoNotTranslateTexts($do_not_translate_texts) {
		$this->do_not_translate_texts = $do_not_translate_texts;
	}

	/**
	 * @return bool
	 */
	public function getDoNotTranslateTexts() {
		return $this->do_not_translate_texts;
	}

	/**
	 * @param null|string $custom_translator_namespace
	 */
	public function setCustomTranslatorNamespace($custom_translator_namespace) {
		$this->custom_translator_namespace = $custom_translator_namespace;
	}

	/**
	 * @return null|string
	 */
	public function getCustomTranslatorNamespace() {
		return $this->custom_translator_namespace;
	}

	/**
	 * @param null|Locale $custom_translator_locale
	 */
	public function setCustomTranslatorLocale(Locale $custom_translator_locale) {
		$this->custom_translator_locale = $custom_translator_locale;
	}

	/**
	 * @return null|Locale
	 */
	public function getCustomTranslatorLocale() {
		return $this->custom_translator_locale;
	}

	/**
	 *
	 */
	public function __wakeup() {
		foreach($this->fields as $field) {
			$field->setForm($this);
		}
	}

	/**
	 * @return string
	 */
	public function getRendererClassName()
	{
	    if(!$this->renderer_class_name) {
	        $this->renderer_class_name = static::getDefaultRendererClassName();
        }

		return $this->renderer_class_name;
	}

	/**
	 * @param string $renderer_class_name
	 */
	public function setRendererClassName($renderer_class_name)
	{
		$this->renderer_class_name = $renderer_class_name;
	}

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	/**
	 * @return Form_Renderer_Abstract_Form|Form_Renderer_Bootstrap_Form
	 */
	public function start() {
		if(!$this->_tag) {
			$this->checkFieldsHasErrorMessages();

			$class_name = $this->getRendererClassName().'_Form';
			$this->_tag = new $class_name($this);
		}

		return $this->_tag;
	}

	/**
	 * @return string
	 */
	public function end() {
		return $this->_tag->end();
	}

	/**
	 *
	 * @return Form_Renderer_Abstract_Form_Message|Form_Renderer_Bootstrap_Form_Message
	 */
	public function message() {
		$class_name = $this->renderer_class_name.'_Form_Message';
		return new $class_name($this);
	}

}