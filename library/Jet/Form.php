<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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

	const TYPE_PASSWORD = 'Password';

	const TYPE_FILE = 'File';
	const TYPE_FILE_IMAGE = 'FileImage';


	/**
	 * @var string
	 */
	protected string $start_view_script = '';

	/**
	 * @var string
	 */
	protected string $end_view_script = '';
	
	/**
	 * @var string
	 */
	protected string $message_view_script = '';

	/**
	 *
	 * @var string $name
	 */
	protected string $name = '';

	/**
	 * @var string
	 */
	protected string $sent_key = '';


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
	 * @var array 
	 */
	protected array $validation_errors = [];

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
	protected string|null $custom_translator_dictionary = null;

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
	 * @var ?Form_Renderer_Form_Tag
	 */
	protected ?Form_Renderer_Form_Tag $_form_tag = null;

	/**
	 * @var ?Form_Renderer_Form_Message
	 */
	protected ?Form_Renderer_Form_Message $_message_tag = null;

	/**
	 * @return string
	 */
	public function getStartViewScript(): string
	{
		if( !$this->start_view_script ) {
			$this->start_view_script = SysConf_Jet_Form_DefaultViews::get('Form', 'start');
		}
		return $this->start_view_script;
	}

	/**
	 * @param string $start_view_script
	 */
	public function setStartViewScript( string $start_view_script ): void
	{
		$this->start_view_script = $start_view_script;
	}

	/**
	 * @return string
	 */
	public function getEndViewScript(): string
	{
		if( !$this->end_view_script ) {
			$this->end_view_script = SysConf_Jet_Form_DefaultViews::get('Form', 'end');
		}

		return $this->end_view_script;
	}

	/**
	 * @param string $end_view_script
	 */
	public function setEndViewScript( string $end_view_script ): void
	{
		$this->end_view_script = $end_view_script;
	}
	
	/**
	 * @return string
	 */
	public function getMessageViewScript(): string
	{
		if( !$this->message_view_script ) {
			$this->message_view_script = SysConf_Jet_Form_DefaultViews::get('Form', 'message');
		}
		
		return $this->message_view_script;
	}
	
	/**
	 * @param string $message_view_script
	 */
	public function setMessageViewScript( string $message_view_script ): void
	{
		$this->message_view_script = $message_view_script;
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
	public function getIsReadonly(): bool
	{
		return $this->is_readonly;
	}

	/**
	 *
	 */
	public function setIsReadonly(): void
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
	public function getFields( bool $as_multidimensional_array = false ): array
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
	public function setFields( array $fields ): void
	{
		$this->fields = [];

		foreach( $fields as $field ) {
			$this->addField( $field );
		}
	}

	/**
	 * @return string
	 */
	public function getMethod(): string
	{
		return $this->method;
	}

	/**
	 * @param string $method
	 */
	public function setMethod( string $method ): void
	{
		$this->method = $method;
	}

	/**
	 * @return string
	 */
	public function getEnctype(): string
	{
		return $this->enctype;
	}

	/**
	 * @param string $enctype
	 */
	public function setEnctype( string $enctype ): void
	{
		$this->enctype = $enctype;
	}

	/**
	 * @return string
	 */
	public function getAction(): string
	{
		return $this->action;
	}

	/**
	 * @param string $action
	 */
	public function setAction( string $action ): void
	{
		$this->action = $action;
	}

	/**
	 * @return string
	 */
	public function getTarget(): string
	{
		return $this->target;
	}

	/**
	 * @param string $target
	 */
	public function setTarget( string $target ): void
	{
		$this->target = $target;
	}

	/**
	 * @return string
	 */
	public function getAcceptCharset(): string
	{
		return $this->accept_charset;
	}

	/**
	 * @param string $accept_charset
	 */
	public function setAcceptCharset( string $accept_charset ): void
	{
		$this->accept_charset = $accept_charset;
	}

	/**
	 * @return bool|null
	 */
	public function getNovalidate(): bool|null
	{
		return $this->novalidate;
	}

	/**
	 * @param bool $novalidate
	 */
	public function setNovalidate( bool $novalidate ): void
	{
		$this->novalidate = $novalidate;
	}

	/**
	 * @return bool
	 */
	public function getAutocomplete(): bool
	{
		return $this->autocomplete;
	}

	/**
	 * @param bool $autocomplete
	 */
	public function setAutocomplete( bool $autocomplete ): void
	{
		$this->autocomplete = $autocomplete;
	}

	/**
	 * Get form name
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ): void
	{
		$this->name = $name;
	}


	/**
	 * @return string
	 */
	public function getSentKey(): string
	{
		if(!$this->sent_key) {
			$this->sent_key = SysConf_Jet_Form::getDefaultSentKey();
		}
		return $this->sent_key;
	}

	/**
	 * @param string $sent_key
	 */
	public function setSentKey( string $sent_key ): void
	{
		$this->sent_key = $sent_key;
	}




	/**
	 *
	 * @return string
	 */
	public function getId(): string
	{
		return $this->name;
	}

	/**
	 * @return array
	 */
	public function getDefaultLabelWidth(): array
	{
		return $this->default_label_width;
	}

	/**
	 * @param array $default_label_width
	 */
	public function setDefaultLabelWidth( array $default_label_width ): void
	{
		$this->default_label_width = $default_label_width;
	}

	/**
	 * @return array
	 */
	public function getDefaultFieldWidth(): array
	{
		return $this->default_field_width;
	}

	/**
	 * @param array $default_field_width
	 */
	public function setDefaultFieldWidth( array $default_field_width ): void
	{
		$this->default_field_width = $default_field_width;
	}


	/**
	 * @param Form_Field $field
	 */
	public function addField( Form_Field $field ): void
	{
		$field->setForm( $this );
		$key = $field->getName();
		$this->fields[$key] = $field;

	}

	/**
	 *
	 * @alias getField
	 *
	 * @param string $name
	 *
	 * @return Form_Field
	 * @throws Form_Exception
	 */
	public function field( string $name ): Form_Field
	{
		return $this->getField( $name );
	}

	/**
	 *
	 * @param string $name
	 *
	 * @return Form_Field
	 * @throws Form_Exception
	 */
	public function getField( string $name ): Form_Field
	{
		if( !isset( $this->fields[$name] ) ) {
			throw new Form_Exception(
				'Unknown field \'' . $name . '\'', Form_Exception::CODE_UNKNOWN_FIELD
			);
		}

		return $this->fields[$name];
	}

	/**
	 * @param string $field_name
	 */
	public function removeField( string $field_name ): void
	{
		if( isset( $this->fields[$field_name] ) ) {
			unset( $this->fields[$field_name] );
		}
	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function fieldExists( string $name ): bool
	{
		return isset( $this->fields[$name] );
	}

	/**
	 * @throws Form_Exception
	 */
	protected function checkFieldsHasErrorMessages(): void
	{
		foreach( $this->fields as $field ) {
			$required_error_codes = $field->getRequiredErrorCodes();

			foreach( $required_error_codes as $code ) {
				if( !$field->getErrorMessage( $code ) ) {
					throw new Form_Exception(
						'Form field error message is not set. Form:' . $this->name . ' Field:' . $field->getName() . ' Error code:' . $code
					);
				}
			}

			foreach($field->getErrorMessages() as $code=>$message) {
				$this->_($message);
			}
		}
	}

	/**
	 * catch values from input ($_POST is default)
	 * and return true if the form has been sent ...
	 *
	 * @param Data_Array|array|null $input_data
	 * @param bool $force_catch
	 *
	 * @return bool
	 */
	public function catchInput( Data_Array|array|null $input_data = null, bool $force_catch = false ): bool
	{

		$this->is_valid = false;

		if( $input_data === null ) {
			$input_data = $this->method == self::METHOD_GET
				? Http_Request::GET()->getRawData()
				:
				Http_Request::POST()->getRawData();
		}

		if( $input_data === false ) {
			$input_data = [];
		}

		if( !$input_data instanceof Data_Array ) {
			$input_data = new Data_Array( $input_data );
		}

		if(
			!$force_catch &&
			$input_data->getString( $this->getSentKey() ) != $this->name
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
	public function validate(): bool
	{
		$this->checkFieldsHasErrorMessages();

		$this->common_message = '';
		$this->is_valid = true;
		$this->validation_errors = [];
		foreach( $this->fields as $field ) {
			$field->validate();
		}

		foreach( $this->fields as $field ) {
			if(!$field->isValid()) {
				$this->is_valid = false;
				foreach($field->getAllErrors() as $error) {
					$this->validation_errors[] = $error;
				}
			}
		}


		return $this->is_valid;
	}

	/**
	 *
	 */
	public function setIsNotValid(): void
	{
		$this->is_valid = false;
	}

	/**
	 *
	 * @return bool
	 */
	public function getIsValid(): bool
	{
		return $this->is_valid;
	}

	/**
	 *
	 * @return string
	 */
	public function getCommonMessage(): string
	{
		return $this->common_message;
	}

	/**
	 * @param string $message
	 */
	public function setCommonMessage( string $message ): void
	{
		$this->common_message = $message;
	}

	/**
	 * @return Form_ValidationError[]
	 */
	public function getValidationErrors(): array
	{
		return $this->validation_errors;
	}

	/**
	 *
	 * @return array|bool
	 */
	public function getValues(): array|bool
	{
		if( !$this->is_valid ) {
			return false;
		}

		$values = new Data_Array();

		foreach( $this->fields as $key => $field ) {
			if(
				$field->getIsReadonly() ||
				!$field->hasValue()
			) {
				continue;
			}

			$value = $field->getValue();

			$values->set( $key, $value );
		}

		return $values->getRawData();
	}

	/**
	 *
	 * @return bool
	 */
	public function catchFieldValues(): bool
	{
		if( !$this->is_valid ) {
			return false;
		}

		foreach( $this->fields as $field ) {
			$field->catchFieldValue();
		}

		return true;
	}

	public function catch() : bool
	{
		if(
			$this->catchInput() &&
			$this->validate()
		) {
			$this->catchFieldValues();
			return true;
		}

		return false;
	}

	/**
	 *
	 * @param string $phrase
	 * @param array $data
	 *
	 * @return string
	 * @see Translator
	 *
	 */
	public function _( string $phrase, array $data = [] ): string
	{
		if( !$phrase ) {
			return $phrase;
		}
		if( $this->do_not_translate_texts ) {
			return Data_Text::replaceData($phrase, $data);
		}

		return Tr::_( $phrase, $data, $this->custom_translator_dictionary, $this->custom_translator_locale );
	}

	/**
	 * @return bool
	 */
	public function getDoNotTranslateTexts(): bool
	{
		return $this->do_not_translate_texts;
	}

	/**
	 * @param bool $do_not_translate_texts
	 */
	public function setDoNotTranslateTexts( bool $do_not_translate_texts ): void
	{
		$this->do_not_translate_texts = $do_not_translate_texts;
	}

	/**
	 * @return null|string
	 */
	public function getCustomTranslatorDictionary(): null|string
	{
		return $this->custom_translator_dictionary;
	}

	/**
	 * @param null|string $custom_translator_dictionary
	 */
	public function setCustomTranslatorDictionary( null|string $custom_translator_dictionary ): void
	{
		$this->custom_translator_dictionary = $custom_translator_dictionary;
	}

	/**
	 * @return null|Locale
	 */
	public function getCustomTranslatorLocale(): null|Locale
	{
		return $this->custom_translator_locale;
	}

	/**
	 * @param null|Locale $custom_translator_locale
	 */
	public function setCustomTranslatorLocale( Locale|null $custom_translator_locale ): void
	{
		$this->custom_translator_locale = $custom_translator_locale;
	}

	/**
	 *
	 */
	public function __wakeup(): void
	{
		foreach( $this->fields as $field ) {
			$field->setForm( $this );
		}
	}

	/**
	 * @return string
	 */
	public function getViewsDir(): string
	{
		if( !$this->views_dir ) {
			$this->views_dir = SysConf_Jet_Form::getDefaultViewsDir();
		}

		return $this->views_dir;
	}

	/**
	 * @param string $views_dir
	 */
	public function setViewsDir( string $views_dir ): void
	{
		$this->views_dir = $views_dir;
	}

	/**
	 * @return MVC_View
	 */
	public function getView(): MVC_View
	{
		return Factory_MVC::getViewInstance( $this->getViewsDir() );
	}


	/**
	 * @return Form_Renderer_Form_Tag
	 */
	public function tag(): Form_Renderer_Form_Tag
	{
		if( !$this->_form_tag ) {
			$this->checkFieldsHasErrorMessages();

			$this->_form_tag = Factory_Form::getRendererFormTagInstance(  $this );
		}

		return $this->_form_tag;
	}

	/**
	 * @return string
	 */
	public function start(): string
	{
		return $this->tag()->start();
	}

	/**
	 * @return string
	 */
	public function end(): string
	{
		return $this->_form_tag->end();
	}

	/**
	 *
	 * @return Form_Renderer_Form_Message
	 */
	public function message(): Form_Renderer_Form_Message
	{
		if( !$this->_message_tag ) {
			$this->_message_tag = Factory_Form::getRendererFormMessageInstance( $this );
		}

		return $this->_message_tag;
	}


}