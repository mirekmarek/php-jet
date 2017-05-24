<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Form extends BaseObject
{
	const LJ_SIZE_EXTRA_SMALL = 'xs';
	const LJ_SIZE_SMALL = 'sm';
	const LJ_SIZE_MEDIUM = 'md';
	const LJ_SIZE_LARGE = 'lg';


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



	/**
	 * @var string
	 */
	protected static $default_views_dir = JET_PATH_APPLICATION.'views/Form/';

	/**
	 * @var string
	 */
	protected static $default_renderer_start_script = 'start';

	/**
	 * @var string
	 */
	protected static $default_renderer_end_script = 'end';

	/**
	 * @var string
	 */
	protected $renderer_start_script = 'start';

	/**
	 * @var string
	 */
	protected $renderer_end_script = 'end';

	/**
	 *
	 * @var string $name
	 */
	protected $name = '';

	/**
	 * @var string
	 */
	protected $sent_key = '_jet_form_sent_';

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
	 * @var Form_Field[]
	 */
	protected $fields = [];
	/**
	 * @var bool
	 */
	protected $is_valid = false;

	/**
	 * @var bool
	 */
	protected $post_size_exceeded = false;

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
	protected $views_dir;

	/**
	 * @var array
	 */
	protected $default_label_width = [self::LJ_SIZE_MEDIUM => 4];

	/**
	 * @var array
	 */
	protected $default_field_width = [self::LJ_SIZE_MEDIUM => 8];

	/**
	 * @var Form_Renderer_Pair
	 */
	protected $_form_tag;

	/**
	 * @var Form_Renderer_Single
	 */
	protected $_message_tag;


	/**
	 * @return string
	 */
	public static function getDefaultViewsDir()
	{
		return static::$default_views_dir;
	}

	/**
	 * @param string $default_views_dir
	 */
	public static function setDefaultViewsDir( $default_views_dir )
	{
		static::$default_views_dir = $default_views_dir;
	}

	/**
	 * @return string
	 */
	public static function getDefaultRendererStartScript()
	{
		return static::$default_renderer_start_script;
	}

	/**
	 * @param string $default_renderer_start_script
	 */
	public static function setDefaultRendererStartScript( $default_renderer_start_script )
	{
		static::$default_renderer_start_script = $default_renderer_start_script;
	}

	/**
	 * @return string
	 */
	public static function getDefaultRendererEndScript()
	{
		return static::$default_renderer_end_script;
	}

	/**
	 * @param string $default_renderer_end_script
	 */
	public static function setDefaultRendererEndScript( $default_renderer_end_script )
	{
		static::$default_renderer_end_script = $default_renderer_end_script;
	}

	/**
	 * @return string
	 */
	public function getRendererStartScript()
	{
		if(!$this->renderer_start_script) {
			$this->renderer_start_script = static::getRendererStartScript();
		}
		return $this->renderer_start_script;
	}

	/**
	 * @param string $renderer_start_script
	 */
	public function setRendererStartScript( $renderer_start_script )
	{
		$this->renderer_start_script = $renderer_start_script;
	}

	/**
	 * @return string
	 */
	public function getRendererEndScript()
	{
		if(!$this->renderer_end_script) {
			$this->renderer_end_script = static::getRendererEndScript();
		}

		return $this->renderer_end_script;
	}

	/**
	 * @param string $renderer_end_script
	 */
	public function setRendererEndScript( $renderer_end_script )
	{
		$this->renderer_end_script = $renderer_end_script;
	}



	/**
	 * constructor
	 *
	 * @param string       $name
	 * @param Form_Field[] $fields
	 * @param string       $method - POST or GET (optional, default: POST)
	 */
	public function __construct( $name, array $fields, $method = self::METHOD_POST )
	{
		$this->name = $name;
		$this->method = $method;
		$this->setFields( $fields );
	}

	/**
	 * @return bool
	 */
	public function getIsReadonly()
	{
		return $this->is_readonly;
	}

	/**
	 *
	 */
	public function setIsReadonly()
	{
		$this->is_readonly = true;

		foreach( $this->getFields() as $field ) {
			$field->setIsReadonly( true );
		}
	}

	/**
	 * returns language independent fields
	 *
	 * @param bool $as_multidimensional_array (optional, default: false)
	 *
	 * @return Form_Field[]
	 */
	public function getFields( $as_multidimensional_array = false )
	{
		if( $as_multidimensional_array ) {
			$fields = new Data_Array();

			foreach( $this->fields as $field ) {
				$fields->set( $field->getName(), $field );
			}

			return $fields->getRawData();

		}

		return $this->fields;
	}

	/**
	 * set form fields
	 *
	 * @param Form_Field[] $fields
	 *
	 * @throws Form_Exception
	 */
	public function setFields( array $fields )
	{
		$this->fields = [];

		foreach( $fields as $field ) {
			$this->addField( $field );
		}
	}

	/**
	 * @return string
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * @param string $method
	 */
	public function setMethod( $method )
	{
		$this->method = $method;
	}

	/**
	 * @return string
	 */
	public function getEnctype()
	{
		return $this->enctype;
	}

	/**
	 * @param string $enctype
	 */
	public function setEnctype( $enctype )
	{
		$this->enctype = $enctype;
	}

	/**
	 * @return string
	 */
	public function getAction()
	{
		return $this->action;
	}

	/**
	 * @param string $action
	 */
	public function setAction( $action )
	{
		$this->action = $action;
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		return $this->target;
	}

	/**
	 * @param string $target
	 */
	public function setTarget( $target )
	{
		$this->target = $target;
	}

	/**
	 * @return string
	 */
	public function getAcceptCharset()
	{
		return $this->accept_charset;
	}

	/**
	 * @param string $accept_charset
	 */
	public function setAcceptCharset( $accept_charset )
	{
		$this->accept_charset = $accept_charset;
	}

	/**
	 * @return bool|null
	 */
	public function getNovalidate()
	{
		return $this->novalidate;
	}

	/**
	 * @param bool $novalidate
	 */
	public function setNovalidate( $novalidate )
	{
		$this->novalidate = (bool)$novalidate;
	}

	/**
	 * @return bool
	 */
	public function getAutocomplete()
	{
		return $this->autocomplete;
	}

	/**
	 * @param bool $autocomplete
	 */
	public function setAutocomplete( $autocomplete )
	{
		$this->autocomplete = $autocomplete;
	}

	/**
	 * Get form name
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getSentKey()
	{
		return $this->sent_key;
	}

	/**
	 * @param string $sent_key
	 */
	public function setSentKey( $sent_key )
	{
		$this->sent_key = $sent_key;
	}


	/**
	 *
	 * @return string
	 */
	public function getId()
	{
		return $this->name;
	}

	/**
	 * @return array
	 */
	public function getDefaultLabelWidth()
	{
		return $this->default_label_width;
	}

	/**
	 * @param array $default_label_width
	 */
	public function setDefaultLabelWidth( array $default_label_width )
	{
		$this->default_label_width = $default_label_width;
	}

	/**
	 * @return array
	 */
	public function getDefaultFieldWidth()
	{
		return $this->default_field_width;
	}

	/**
	 * @param array $default_field_width
	 */
	public function setDefaultFieldWidth( array $default_field_width )
	{
		$this->default_field_width = $default_field_width;
	}


	/**
	 * @param Form_Field $field
	 */
	public function addField( Form_Field $field )
	{
		$field->setForm( $this );

		$key = $field->getName();
		$field->setForm( $this );
		$this->fields[$key] = $field;

	}

	/**
	 *
	 * @alias getField
	 *
	 * @param string $name
	 *
	 * @throws Form_Exception
	 * @return Form_Field
	 */
	public function field( $name )
	{
		return $this->getField( $name );
	}

	/**
	 *
	 * @param string $name
	 *
	 * @throws Form_Exception
	 * @return Form_Field
	 */
	public function getField( $name )
	{
		if( !isset( $this->fields[$name] ) ) {
			throw new Form_Exception(
				'Unknown field \''.$name.'\'', Form_Exception::CODE_UNKNOWN_FIELD
			);
		}

		return $this->fields[$name];
	}

	/**
	 * @param string $field_name
	 */
	public function removeField( $field_name )
	{
		if( isset( $this->fields[$field_name] ) ) {
			unset( $this->fields[$field_name] );
		}
	}

	/**
	 * @param string     $name
	 * @param Form_Field $field
	 */
	public function setField( $name, Form_Field $field )
	{
		$this->fields[$name] = $field;
		$field->setForm( $this );
	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function fieldExists( $name )
	{
		return isset( $this->fields[$name] );
	}

	/**
	 * catch values from input ($_POST is default)
	 * and return true if form sent ...
	 *
	 * @param array $input_data
	 * @param bool  $force_catch
	 *
	 * @return bool
	 */
	public function catchInput( $input_data = null, $force_catch = false )
	{

		$this->is_valid = false;

		if( $input_data===null ) {
			$input_data = $this->method==self::METHOD_GET ? Http_Request::GET()->getRawData() :
				Http_Request::POST()->getRawData();
		}

		if( $input_data===false ) {
			$input_data = [];
		}

		if( !$input_data instanceof Data_Array ) {
			$input_data = new Data_Array( $input_data );
		}

		if(
			!$force_catch  &&
			$input_data->getString( $this->getSentKey() )!=$this->name
		) {
			return false;
		}

		foreach( $this->fields as $field ) {
			if( $field->getIsReadonly() ) {
				continue;
			}
			$field->catchInput( $input_data );
		}

		$this->raw_data = $input_data;

		return true;
	}

	/**
	 *
	 * @return bool
	 */
	public function validate()
	{
		$this->checkFieldsHasErrorMessages();

		$this->common_message = '';
		$this->is_valid = true;
		foreach( $this->fields as $field ) {
			if( $field->getIsReadonly() ) {
				continue;
			}

			$validator = $field->getValidator();
			if( $validator ) {
				if( !$validator( $field ) ) {
					$this->is_valid = false;
				}

				continue;
			}

			if( !$field->checkValueIsNotEmpty() ) {
				$this->is_valid = false;
				continue;
			}

			if( !$field->validate() ) {
				$this->is_valid = false;
			}

		}


		return $this->is_valid;
	}

	/**
	 * @throws Form_Exception
	 */
	protected function checkFieldsHasErrorMessages()
	{
		foreach( $this->fields as $field ) {
			$required_error_codes = $field->getRequiredErrorCodes();

			foreach( $required_error_codes as $code ) {
				if( !$field->getErrorMessage( $code ) ) {
					throw new Form_Exception(
						'Form field error message is not set. Form:'.$this->name.' Field:'.$field->getName(
						).' Error code:'.$code
					);
				}
			}
		}
	}

	/**
	 *
	 */
	public function setIsNotValid()
	{
		$this->is_valid = false;
	}

	/**
	 *
	 * @return bool
	 */
	public function getIsValid()
	{
		return $this->is_valid;
	}

	/**
	 *
	 * @return string
	 */
	public function getCommonMessage()
	{
		return $this->common_message;
	}

	/**
	 * @param string $message
	 */
	public function setCommonMessage( $message )
	{
		$this->common_message = $message;
	}

	/**
	 * get all errors in form
	 *
	 * @return array
	 */
	public function getAllErrors()
	{
		$result = [];

		foreach( $this->fields as $key => $field ) {
			$last_error = $field->getLastErrorMessage();

			if( $last_error ) {
				$result[$key] = $last_error;
			}
		}

		return $result;
	}

	/**
	 * @return Data_Array
	 */
	public function getRawData()
	{
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
	public function getValues( $escape_values = false, $force_skip_is_valid = false )
	{
		if(
			!$this->is_valid &&
			!$force_skip_is_valid
		) {
			return false;
		}

		$result = [];
		foreach( $this->fields as $key => $field ) {
			if(
				$field->getIsReadonly() ||
				!$field->getHasValue()
			) {
				continue;
			}

			$value = $field->getValue();

			if( $escape_values ) {
				if( is_string( $value ) ) {
					$value = addslashes( $value );
				} else {
					if( is_bool( $value ) ) {
						$value = $value ? 1 : 0;
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
	public function getData( $force_skip_is_valid = false )
	{
		if(
			!$this->is_valid &&
			!$force_skip_is_valid
		) {
			return null;
		}

		$data = new Data_Array();

		foreach( $this->fields as $key => $field ) {
			if(
				$field->getIsReadonly() ||
				!$field->getHasValue()
			) {
				continue;
			}

			$value = $field->getValue();

			$data->set( $key, $value );
		}

		return $data;
	}

	/**
	 *
	 * @return bool
	 */
	public function catchData()
	{
		if( !$this->is_valid ) {
			return false;
		}

		foreach( $this->fields as $field ) {
			$field->catchData();
		}

		return true;
	}

	/**
	 *
	 * @see Translator
	 *
	 * @param string $phrase
	 * @param array  $data
	 *
	 * @return string
	 */
	public function _( $phrase, $data = [] )
	{
		if( !$phrase ) {
			return $phrase;
		}
		if( $this->do_not_translate_texts ) {
			return $phrase;
		}

		return Tr::_( $phrase, $data, $this->custom_translator_namespace, $this->custom_translator_locale );
	}

	/**
	 * @return bool
	 */
	public function getDoNotTranslateTexts()
	{
		return $this->do_not_translate_texts;
	}

	/**
	 * @param bool $do_not_translate_texts
	 */
	public function setDoNotTranslateTexts( $do_not_translate_texts )
	{
		$this->do_not_translate_texts = $do_not_translate_texts;
	}

	/**
	 * @return null|string
	 */
	public function getCustomTranslatorNamespace()
	{
		return $this->custom_translator_namespace;
	}

	/**
	 * @param null|string $custom_translator_namespace
	 */
	public function setCustomTranslatorNamespace( $custom_translator_namespace )
	{
		$this->custom_translator_namespace = $custom_translator_namespace;
	}

	/**
	 * @return null|Locale
	 */
	public function getCustomTranslatorLocale()
	{
		return $this->custom_translator_locale;
	}

	/**
	 * @param null|Locale $custom_translator_locale
	 */
	public function setCustomTranslatorLocale( Locale $custom_translator_locale )
	{
		$this->custom_translator_locale = $custom_translator_locale;
	}

	/**
	 *
	 */
	public function __wakeup()
	{
		foreach( $this->fields as $field ) {
			$field->setForm( $this );
		}
	}

	/**
	 * @return string
	 */
	public function getViewsDir()
	{
		if(!$this->views_dir) {
			$this->views_dir = static::getDefaultViewsDir();
		}

		return $this->views_dir;
	}

	/**
	 * @param string $views_dir
	 */
	public function setViewsDir( $views_dir )
	{
		$this->views_dir = $views_dir;
	}

	/**
	 * @return Mvc_View
	 */
	public function getView() {

		return Mvc_Factory::getViewInstance($this->getViewsDir());
	}


	/**
	 * @return Form_Renderer_Pair
	 */
	public function tag() {
		if( !$this->_form_tag ) {
			$this->checkFieldsHasErrorMessages();

			$this->_form_tag = Form_Factory::gerRendererPairInstance( $this );

			$this->_form_tag->setViewScriptStart( $this->getRendererStartScript() );
			$this->_form_tag->setViewScriptEnd( $this->getRendererEndScript() );
		}

		return $this->_form_tag;
	}

	/**
	 * @return string
	 */
	public function start()
	{
		return $this->tag()->start();
	}

	/**
	 * @return string
	 */
	public function end()
	{
		return $this->_form_tag->end();
	}

	/**
	 *
	 * @return Form_Renderer_Single
	 */
	public function message()
	{
		if(!$this->_message_tag) {
			$this->_message_tag = Form_Factory::gerRendererSingleInstance( $this );
			$this->_message_tag->setViewScript('message');
		}

		return $this->_message_tag;
	}



}