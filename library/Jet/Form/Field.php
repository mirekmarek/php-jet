<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use Iterator;
use JsonSerializable;
use ReflectionClass;

/**
 *
 */
abstract class Form_Field extends BaseObject implements JsonSerializable
{
	
	public const TYPE_HIDDEN = 'Hidden';
	
	public const TYPE_INPUT = 'Input';
	
	public const TYPE_INT = 'Int';
	public const TYPE_FLOAT = 'Float';
	public const TYPE_RANGE = 'Range';
	
	public const TYPE_DATE = 'Date';
	public const TYPE_DATE_TIME = 'DateTime';
	public const TYPE_MONTH = 'Month';
	public const TYPE_WEEK = 'Week';
	public const TYPE_TIME = 'Time';
	
	public const TYPE_EMAIL = 'Email';
	public const TYPE_TEL = 'Tel';
	
	public const TYPE_URL = 'Url';
	public const TYPE_SEARCH = 'Search';
	
	public const TYPE_COLOR = 'Color';
	
	public const TYPE_SELECT = 'Select';
	public const TYPE_MULTI_SELECT = 'MultiSelect';
	
	public const TYPE_CHECKBOX = 'Checkbox';
	public const TYPE_RADIO_BUTTON = 'RadioButton';
	
	public const TYPE_TEXTAREA = 'Textarea';
	public const TYPE_WYSIWYG = 'WYSIWYG';
	
	public const TYPE_PASSWORD = 'Password';
	
	public const TYPE_FILE = 'File';
	public const TYPE_FILE_IMAGE = 'FileImage';
	
	public const TYPE_CSRF_PROTECTION = 'CSRFProtection';
	
	
	use Form_Field_Trait_Validation;
	use Form_Field_Trait_Render;

	public const ERROR_CODE_EMPTY = Validator::ERROR_CODE_EMPTY;
	public const ERROR_CODE_INVALID_FORMAT = Validator_RegExp::ERROR_CODE_INVALID_FORMAT;
	public const ERROR_CODE_INVALID_VALUE = Validator_Option::ERROR_CODE_INVALID_VALUE;
	public const ERROR_CODE_OUT_OF_RANGE = Validator_Int::ERROR_CODE_OUT_OF_RANGE;
	public const ERROR_CODE_FILE_IS_TOO_LARGE = Validator_File::ERROR_CODE_FILE_IS_TOO_LARGE;
	public const ERROR_CODE_DISALLOWED_FILE_TYPE = Validator_File::ERROR_CODE_DISALLOWED_FILE_TYPE;
	public const ERROR_CODE_CHECK_NOT_MATCH = Validator_Password::ERROR_CODE_CHECK_NOT_MATCH;
	public const ERROR_CODE_WEAK_PASSWORD = Validator_Password::ERROR_CODE_WEAK_PASSWORD;
	
	
	
	/**
	 * @var string
	 */
	protected string $_type = '';

	/**
	 *
	 * @var string
	 */
	protected string $_name = '';

	/**
	 * @var ?Form
	 */
	protected ?Form $_form = null;
	
	protected string $_input_catcher_type;
	
	protected ?InputCatcher $_input_catcher = null;

	/**
	 *
	 * @var mixed
	 */
	protected mixed $default_value = '';

	/**
	 * @var string
	 */
	protected string $label = '';
	
	/**
	 * @var string
	 */
	protected string $help_text = '';
	
	/**
	 * @var array<string,mixed>
	 */
	protected array $help_data = [];

	/**
	 * @var string
	 */
	protected string $placeholder = '';

	/**
	 * @var bool
	 */
	protected bool $is_required = false;

	/**
	 * @var bool
	 */
	protected bool $is_readonly = false;
	
	/**
	 * @var bool
	 */
	protected bool $do_not_translate_label = false;


	/**
	 * @var callable
	 */
	protected $field_value_catcher;
	
	
	/**
	 * @var Form_Definition_FieldOption[][]
	 */
	protected static array $field_options_definition = [];

	
	/**
	 *
	 * @param string $name
	 * @param string $label
	 */
	public function __construct( string $name, string $label = '' )
	{
		$this->_name = $name;
		$this->label = $label;
	}
	
	public function getInputCatcher() : InputCatcher
	{
		if( !$this->_input_catcher ) {
			$this->_input_catcher = Factory_InputCatcher::getInputCatcherInstance(
				$this->_input_catcher_type,
				$this->getName(),
				$this->getDefaultValue()
			);
		}
		
		return $this->_input_catcher;
	}
	
	/**
	 * @return string
	 */
	public function getType() : string
	{
		return $this->_type;
	}
	
	/**
	 * @return Form
	 */
	public function getForm(): Form
	{
		return $this->_form;
	}

	/**
	 *
	 * @param Form $form
	 */
	public function setForm( Form $form ): void
	{
		$this->_form = $form;
	}


	/**
	 *
	 * @return string
	 */
	public function getId(): string
	{
		return $this->_form->getId() . '__' . str_replace( '/', '___', $this->getName() );
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
	 * @return string
	 */
	public function getTagNameValue(): string
	{
		$name = $this->getName();

		if( $name[0] != '/' ) {
			return $name;
		}

		$name = explode( '/', $name );
		array_shift( $name );
		foreach( $name as $i => $np ) {
			if( $i > 0 ) {
				if( str_ends_with( $np, '[]' ) ) {
					$np = substr( $np, 0, -2 );
					$name[$i] = '[' . $np . '][]';
				} else {
					$name[$i] = '[' . $np . ']';
				}
			}
		}

		return implode( '', $name );
	}

	/**
	 *
	 * @return string
	 */
	public function getName(): string
	{
		return $this->_name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ): void
	{
		$this->_name = $name;
		$this->getInputCatcher()->setName( $name );
	}

	/**
	 *
	 * @return mixed
	 */
	public function getDefaultValue(): mixed
	{
		return $this->default_value;
	}

	/**
	 *
	 * @param mixed $default_value
	 */
	public function setDefaultValue( mixed $default_value ): void
	{
		$this->default_value = $default_value;
		
		if(
			is_object( $default_value ) &&
			$default_value instanceof Iterator
		) {
			$value = [];
			foreach( $default_value as $k => $v ) {
				$value[$k] = $v;
			}
		} else {
			$value = $default_value;
		}

		$this->getInputCatcher()->setValue( $value );
		$this->getInputCatcher()->setDefaultValue( $value );
	}

	/**
	 *
	 * @return string
	 */
	public function getLabel(): string
	{
		if($this->getDoNotTranslateLabel()) {
			return $this->label;
		}
		
		return $this->_( $this->label );
	}

	/**
	 *
	 * @param string $label
	 */
	public function setLabel( string $label ): void
	{
		$this->label = $label;
	}
	
	/**
	 * @return string
	 */
	public function getHelpText(): string
	{
		return $this->_( $this->help_text, $this->help_data );
	}
	
	/**
	 * @param string $help_text
	 */
	public function setHelpText( string $help_text ): void
	{
		$this->help_text = $help_text;
	}
	
	/**
	 * @return array<string,mixed>
	 */
	public function getHelpData(): array
	{
		return $this->help_data;
	}
	
	/**
	 * @param array<string,mixed> $help_data
	 */
	public function setHelpData( array $help_data ): void
	{
		$this->help_data = $help_data;
	}
	
	


	/**
	 * @return string
	 */
	public function getPlaceholder(): string
	{
		return $this->_( $this->placeholder );
	}

	/**
	 * @param string $placeholder
	 */
	public function setPlaceholder( string $placeholder ): void
	{
		$this->placeholder = $placeholder;
	}

	/**
	 *
	 * @return bool
	 */
	public function getIsRequired(): bool
	{
		return $this->is_required;
	}

	/**
	 *
	 * @param bool $required
	 */
	public function setIsRequired( bool $required ): void
	{
		$this->is_required = $required;
	}
	
	/**
	 * @return bool
	 */
	public function getIsReadonly(): bool
	{
		return $this->is_readonly;
	}

	/**
	 * @param bool $is_readonly
	 */
	public function setIsReadonly( bool $is_readonly ): void
	{
		$this->is_readonly = $is_readonly;
	}


	/**
	 * @return callable|null
	 */
	public function getFieldValueCatcher(): callable|null
	{
		return $this->field_value_catcher;
	}

	/**
	 * @param callable $field_value_catcher
	 */
	public function setFieldValueCatcher( callable $field_value_catcher ): void
	{
		$this->field_value_catcher = $field_value_catcher;
	}

	/**
	 *
	 */
	public function catchFieldValue(): void
	{
		if(
			$this->getIsReadonly() ||
			!$this->hasValue() ||
			!($catcher = $this->getFieldValueCatcher())
		) {
			return;
		}

		$catcher( $this->getValue() );
	}

	/**
	 *
	 * @return bool
	 */
	public function hasValue(): bool
	{
		return $this->getInputCatcher()->valueExistsInTheInput();
	}

	/**
	 *
	 * @return mixed
	 */
	public function getValue(): mixed
	{
		return $this->getInputCatcher()->getValue();
	}

	/**
	 * @param mixed $value
	 */
	public function setValue( mixed $value ): void
	{
		$this->getInputCatcher()->setValue( $value );

	}

	/**
	 * @return mixed
	 */
	public function getValueRaw(): mixed
	{
		return $this->getInputCatcher()->getValueRaw();
	}


	/**
	 * @return array<string,mixed>
	 */
	public function jsonSerialize(): array
	{

		$vars = [];

		$vars['id'] = $this->getId();

		foreach( get_object_vars( $this ) as $k => $v ) {
			if( $k == '_type' ) {
				$vars['type'] = $v;
				continue;
			}

			if( $k[0] != '_' ) {
				$vars[$k] = $v;
			}
		}

		return $vars;
	}

	/**
	 * @param string $phrase
	 * @param array<string,mixed> $data
	 *
	 * @return string
	 * @see Translator
	 *
	 */
	public function _( string $phrase, array $data = [] ): string
	{
		return $this->_form->_( $phrase, $data );
	}
	
	/**
	 * @return bool
	 */
	public function getDoNotTranslateLabel(): bool
	{
		return $this->do_not_translate_label;
	}
	
	/**
	 * @param bool $do_not_translate_label
	 */
	public function setDoNotTranslateLabel( bool $do_not_translate_label ): void
	{
		$this->do_not_translate_label = $do_not_translate_label;
	}
	
	

	/**
	 *
	 * @param Data_Array $data
	 */
	public function catchInput( Data_Array $data ): void
	{
		$this->getInputCatcher()->catchInput( $data );
	}
	
	/**
	 * @return Form_Definition_FieldOption[]
	 */
	public static function getFieldOptionsDefinition() : array
	{
		$class = static::class;
		
		if(!array_key_exists($class, static::$field_options_definition)) {
			$properties = Attributes::getClassPropertyDefinition( new ReflectionClass($class), Form_Definition_FieldOption::class );
			static::$field_options_definition[$class] = [];
			
			foreach($properties as $option_name=>$def_data) {
				static::$field_options_definition[$class][$option_name] = new Form_Definition_FieldOption();
				static::$field_options_definition[$class][$option_name]->setup($class, $option_name, $def_data);
				
			}

			
		}
		return static::$field_options_definition[$class];
	}
	
}
