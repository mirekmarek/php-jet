<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @var ?string
	 */
	protected static ?string $default_views_dir = null;

	/**
	 * @var string
	 */
	protected static string $default_renderer_start_script = 'start';

	/**
	 * @var string
	 */
	protected static string $default_renderer_end_script = 'end';

	/**
	 * @var string
	 */
	protected string $renderer_start_script = 'start';

	/**
	 * @var string
	 */
	protected string $renderer_end_script = 'end';

	/**
	 *
	 * @var string $name
	 */
	protected string $name = '';

	/**
	 * @var string
	 */
	protected string $sent_key = '_jet_form_sent_';

	/**
	 * @var string $name
	 */
	protected string $id = '';
	/**
	 * POST (default) or GET
	 *
	 * @var string
	 */
	protected string $method = self::METHOD_POST;
	/**
	 * @var string
	 */
	protected string $enctype = '';
	/**
	 * @var string
	 */
	protected string $action = '';
	/**
	 * @var string
	 */
	protected string $target = '';
	/**
	 * @var string
	 */
	protected string $accept_charset = '';
	/**
	 * @var bool
	 */
	protected bool $novalidate = false;
	/**
	 * @var bool
	 */
	protected bool $autocomplete = true;

	/**
	 * Form fields
	 *
	 * @var Form_Field[]
	 */
	protected array $fields = [];

	/**
	 * @var bool
	 */
	protected bool $is_valid = false;

	/**
	 * @var bool
	 */
	protected bool $post_size_exceeded = false;

	/**
	 * @var ?Data_Array
	 */
	protected ?Data_Array $raw_data = null;

	/**
	 * Common error message (without field context)
	 *
	 * @var string
	 */
	protected string $common_message = '';

	/**
	 * @var bool
	 */
	protected bool $do_not_translate_texts = false;

	/**
	 * @var string|null
	 */
	protected string|null $custom_translator_namespace = null;

	/**
	 * @var Locale|null
	 */
	protected Locale|null $custom_translator_locale = null;

	/**
	 * @var bool
	 */
	protected bool $is_readonly = false;

	/**
	 * @var ?string
	 */
	protected string|null $views_dir = null;

	/**
	 * @var array
	 */
	protected array $default_label_width = [self::LJ_SIZE_MEDIUM => 4];

	/**
	 * @var array
	 */
	protected array $default_field_width = [self::LJ_SIZE_MEDIUM => 8];

	/**
	 * @var ?Form_Renderer_Pair
	 */
	protected ?Form_Renderer_Pair $_form_tag = null;

	/**
	 * @var ?Form_Renderer_Single
	 */
	protected ?Form_Renderer_Single $_message_tag = null;


	/**
	 * @return string
	 */
	public static function getDefaultViewsDir() : string
	{
		if(!static::$default_views_dir) {
			static::$default_views_dir = SysConf_PATH::APPLICATION().'views/Form/';
		}

		return static::$default_views_dir;
	}

	/**
	 * @param string $default_views_dir
	 */
	public static function setDefaultViewsDir( string $default_views_dir ) : void
	{
		static::$default_views_dir = $default_views_dir;
	}

	/**
	 * @return string
	 */
	public static function getDefaultRendererStartScript() : string
	{
		return static::$default_renderer_start_script;
	}

	/**
	 * @param string $default_renderer_start_script
	 */
	public static function setDefaultRendererStartScript( string $default_renderer_start_script ) : void
	{
		static::$default_renderer_start_script = $default_renderer_start_script;
	}

	/**
	 * @return string
	 */
	public static function getDefaultRendererEndScript() : string
	{
		return static::$default_renderer_end_script;
	}

	/**
	 * @param string $default_renderer_end_script
	 */
	public static function setDefaultRendererEndScript( string $default_renderer_end_script ) : void
	{
		static::$default_renderer_end_script = $default_renderer_end_script;
	}

	/**
	 * @return string
	 */
	public function getRendererStartScript() : string
	{
		if(!$this->renderer_start_script) {
			$this->renderer_start_script = static::getRendererStartScript();
		}
		return $this->renderer_start_script;
	}

	/**
	 * @param string $renderer_start_script
	 */
	public function setRendererStartScript( string $renderer_start_script ) : void
	{
		$this->renderer_start_script = $renderer_start_script;
	}

	/**
	 * @return string
	 */
	public function getRendererEndScript() : string
	{
		if(!$this->renderer_end_script) {
			$this->renderer_end_script = static::getRendererEndScript();
		}

		return $this->renderer_end_script;
	}

	/**
	 * @param string $renderer_end_script
	 */
	public function setRendererEndScript( string $renderer_end_script ) : void
	{
		$this->renderer_end_script = $renderer_end_script;
	}


	/**
	 * constructor
	 *
	 * @param string $name
	 * @param Form_Field[] $fields
	 * @param string $method - POST or GET (optional, default: POST)
	 */
	public function __construct( string $name, array $fields, string $method = self::METHOD_POST )
	{
		$this->name = $name;
		$this->method = $method;
		$this->setFields( $fields );
	}

	/**
	 * @return bool
	 */
	public function getIsReadonly() : bool
	{
		return $this->is_readonly;
	}

	/**
	 *
	 */
	public function setIsReadonly() : void
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
	public function getFields( bool $as_multidimensional_array = false ) : array
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
	 */
	public function setFields( array $fields ) : void
	{
		$this->fields = [];

		foreach( $fields as $field ) {
			$this->addField( $field );
		}
	}

	/**
	 * @return string
	 */
	public function getMethod() : string
	{
		return $this->method;
	}

	/**
	 * @param string $method
	 */
	public function setMethod( string $method ) : void
	{
		$this->method = $method;
	}

	/**
	 * @return string
	 */
	public function getEnctype() : string
	{
		return $this->enctype;
	}

	/**
	 * @param string $enctype
	 */
	public function setEnctype( string $enctype ) : void
	{
		$this->enctype = $enctype;
	}

	/**
	 * @return string
	 */
	public function getAction() : string
	{
		return $this->action;
	}

	/**
	 * @param string $action
	 */
	public function setAction( string $action ) : void
	{
		$this->action = $action;
	}

	/**
	 * @return string
	 */
	public function getTarget() : string
	{
		return $this->target;
	}

	/**
	 * @param string $target
	 */
	public function setTarget( string $target ) : void
	{
		$this->target = $target;
	}

	/**
	 * @return string
	 */
	public function getAcceptCharset() : string
	{
		return $this->accept_charset;
	}

	/**
	 * @param string $accept_charset
	 */
	public function setAcceptCharset( string $accept_charset ) : void
	{
		$this->accept_charset = $accept_charset;
	}

	/**
	 * @return bool|null
	 */
	public function getNovalidate() : bool|null
	{
		return $this->novalidate;
	}

	/**
	 * @param bool $novalidate
	 */
	public function setNovalidate( bool $novalidate ) : void
	{
		$this->novalidate = (bool)$novalidate;
	}

	/**
	 * @return bool
	 */
	public function getAutocomplete() : bool
	{
		return $this->autocomplete;
	}

	/**
	 * @param bool $autocomplete
	 */
	public function setAutocomplete( bool $autocomplete ) : void
	{
		$this->autocomplete = $autocomplete;
	}

	/**
	 * Get form name
	 * @return string
	 */
	public function getName() : string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ) : void
	{
		$this->name = $name;
	}


	/**
	 * @return string
	 */
	public function getSentKey() : string
	{
		return $this->sent_key;
	}

	/**
	 * @param string $sent_key
	 */
	public function setSentKey( string $sent_key ) : void
	{
		$this->sent_key = $sent_key;
	}


	/**
	 *
	 * @return string
	 */
	public function getId() : string
	{
		return $this->name;
	}

	/**
	 * @return array
	 */
	public function getDefaultLabelWidth() : array
	{
		return $this->default_label_width;
	}

	/**
	 * @param array $default_label_width
	 */
	public function setDefaultLabelWidth( array $default_label_width ) : void
	{
		$this->default_label_width = $default_label_width;
	}

	/**
	 * @return array
	 */
	public function getDefaultFieldWidth() : array
	{
		return $this->default_field_width;
	}

	/**
	 * @param array $default_field_width
	 */
	public function setDefaultFieldWidth( array $default_field_width ) : void
	{
		$this->default_field_width = $default_field_width;
	}


	/**
	 * @param Form_Field $field
	 */
	public function addField( Form_Field $field ) : void
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
	public function field( string $name ) : Form_Field
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
	public function getField( string $name ) : Form_Field
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
	public function removeField( string $field_name ) : void
	{
		if( isset( $this->fields[$field_name] ) ) {
			unset( $this->fields[$field_name] );
		}
	}

	/**
	 * @param string     $name
	 * @param Form_Field $field
	 */
	public function setField( string $name, Form_Field $field ) : void
	{
		$this->fields[$name] = $field;
		$field->setForm( $this );
	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function fieldExists( string $name ) : bool
	{
		return isset( $this->fields[$name] );
	}

	/**
	 * catch values from input ($_POST is default)
	 * and return true if form sent ...
	 *
	 * @param array|null $input_data
	 * @param bool  $force_catch
	 *
	 * @return bool
	 */
	public function catchInput( array|null $input_data = null, bool $force_catch = false ) : bool
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
	public function validate() : bool
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
	protected function checkFieldsHasErrorMessages() : void
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
	public function setIsNotValid() : void
	{
		$this->is_valid = false;
	}

	/**
	 *
	 * @return bool
	 */
	public function getIsValid() : bool
	{
		return $this->is_valid;
	}

	/**
	 *
	 * @return string
	 */
	public function getCommonMessage() : string
	{
		return $this->common_message;
	}

	/**
	 * @param string $message
	 */
	public function setCommonMessage( string $message ) : void
	{
		$this->common_message = $message;
	}

	/**
	 * get all errors in form
	 *
	 * @return array
	 */
	public function getAllErrors() : array
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
	public function getRawData() : Data_Array
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
	public function getValues( bool $escape_values = false,
	                           bool $force_skip_is_valid = false ) : array|bool
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
	public function getData( bool $force_skip_is_valid = false ) : Data_Array|null
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
	public function catchData() : bool
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
	public function _( string $phrase, array $data = [] ) : string
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
	public function getDoNotTranslateTexts() : bool
	{
		return $this->do_not_translate_texts;
	}

	/**
	 * @param bool $do_not_translate_texts
	 */
	public function setDoNotTranslateTexts( bool $do_not_translate_texts ) : void
	{
		$this->do_not_translate_texts = $do_not_translate_texts;
	}

	/**
	 * @return null|string
	 */
	public function getCustomTranslatorNamespace() : null|string
	{
		return $this->custom_translator_namespace;
	}

	/**
	 * @param null|string $custom_translator_namespace
	 */
	public function setCustomTranslatorNamespace( null|string $custom_translator_namespace ) : void
	{
		$this->custom_translator_namespace = $custom_translator_namespace;
	}

	/**
	 * @return null|Locale
	 */
	public function getCustomTranslatorLocale() : null|Locale
	{
		return $this->custom_translator_locale;
	}

	/**
	 * @param null|Locale $custom_translator_locale
	 */
	public function setCustomTranslatorLocale( Locale|null $custom_translator_locale ) : void
	{
		$this->custom_translator_locale = $custom_translator_locale;
	}

	/**
	 *
	 */
	public function __wakeup() : void
	{
		foreach( $this->fields as $field ) {
			$field->setForm( $this );
		}
	}

	/**
	 * @return string
	 */
	public function getViewsDir() : string
	{
		if(!$this->views_dir) {
			$this->views_dir = static::getDefaultViewsDir();
		}

		return $this->views_dir;
	}

	/**
	 * @param string $views_dir
	 */
	public function setViewsDir( string $views_dir ) : void
	{
		$this->views_dir = $views_dir;
	}

	/**
	 * @return Mvc_View
	 */
	public function getView() : Mvc_View
	{
		return Mvc_Factory::getViewInstance($this->getViewsDir());
	}


	/**
	 * @return Form_Renderer_Pair
	 */
	public function tag() : Form_Renderer_Pair
	{
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
	public function start() : string
	{
		return $this->tag()->start();
	}

	/**
	 * @return string
	 */
	public function end() : string
	{
		return $this->_form_tag->end();
	}

	/**
	 *
	 * @return Form_Renderer_Single
	 */
	public function message() : Form_Renderer_Single
	{
		if(!$this->_message_tag) {
			$this->_message_tag = Form_Factory::gerRendererSingleInstance( $this );
			$this->_message_tag->setViewScript('message');
		}

		return $this->_message_tag;
	}



}