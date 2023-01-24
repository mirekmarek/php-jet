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
	
	const TYPE_CSRF_PROTECTION = 'CSRFProtection';
	
	
	use Form_Field_Trait_Validation;
	use Form_Field_Trait_Render;

	const ERROR_CODE_EMPTY = 'empty';
	const ERROR_CODE_INVALID_FORMAT = 'invalid_format';
	const ERROR_CODE_INVALID_VALUE = 'invalid_value';
	const ERROR_CODE_OUT_OF_RANGE = 'out_of_range';
	const ERROR_CODE_FILE_IS_TOO_LARGE = 'file_is_too_large';
	const ERROR_CODE_DISALLOWED_FILE_TYPE = 'disallowed_file_type';
	const ERROR_CODE_CHECK_NOT_MATCH = 'check_not_match';
	const ERROR_CODE_WEAK_PASSWORD = 'weak_password';

	
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

	/**
	 *
	 * @var mixed
	 */
	protected mixed $_value_raw = null;

	/**
	 *
	 * @var mixed
	 */
	protected mixed $_value = null;

	/**
	 *
	 * @var bool
	 */
	protected bool $_has_value = false;


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
	 * @var array
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
			$this->_value = [];
			foreach( $default_value as $k => $v ) {
				$this->_value[$k] = $v;
			}
		} else {
			$this->_value = $default_value;
		}

		$this->_value_raw = $default_value;
	}

	/**
	 *
	 * @return string
	 */
	public function getLabel(): string
	{
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
	 * @return mixed
	 */
	public function getHelpData(): array
	{
		return $this->help_data;
	}
	
	/**
	 * @param mixed $help_data
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
	 * @param string $required
	 */
	public function setIsRequired( string $required ): void
	{
		$this->is_required = (bool)$required;
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
		return $this->_has_value;
	}

	/**
	 *
	 * @return mixed
	 */
	public function getValue(): mixed
	{
		return $this->_value;
	}

	/**
	 * @param mixed $value
	 */
	public function setValue( mixed $value ): void
	{
		$this->_value = $value;
	}

	/**
	 * @return mixed
	 */
	public function getValueRaw(): mixed
	{
		return $this->_value_raw;
	}


	/**
	 * @return array
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
	 * @param array $data
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
	 *
	 * @param Data_Array $data
	 */
	public function catchInput( Data_Array $data ): void
	{
		$this->_value = null;
		$this->_has_value = $data->exists( $this->_name );

		if( $this->_has_value ) {
			$this->_value_raw = $data->getRaw( $this->_name );
			$this->_value = trim( $data->getString( $this->_name ) );
		} else {
			$this->_value_raw = null;
			$this->_value = $this->default_value;
		}
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
