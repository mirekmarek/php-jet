<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use Iterator;
use JsonSerializable;

/**
 *
 */
abstract class Form_Field extends BaseObject implements JsonSerializable
{

	use Form_Field_Trait_Validation;
	use Form_Field_Trait_Render;

	const ERROR_CODE_EMPTY = 'empty';
	const ERROR_CODE_INVALID_FORMAT = 'invalid_format';

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
	 *
	 * @param string $name
	 * @param string $label
	 * @param mixed $default_value
	 * @param bool $is_required
	 */
	public function __construct( string $name, string $label = '', mixed $default_value = '', bool $is_required = false )
	{

		$this->_name = $name;
		$this->default_value = $default_value;
		$this->label = $label;
		$this->setIsRequired( $is_required );
		$this->setDefaultValue( $default_value );
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

		if( $default_value instanceof DataModel_IDController ) {
			$default_value = $default_value->toString();
		}

		if(
			is_array( $default_value ) ||
			(
				is_object( $default_value ) &&
				$default_value instanceof Iterator
			)
		) {
			$this->_value = [];
			foreach( $default_value as $k => $v ) {
				if(
					is_object( $v ) &&
					$v instanceof DataModel
				) {
					/**
					 * @var DataModel $v
					 */
					$v = $v->getIDController()->toString();
				}
				if( is_array( $v ) ) {
					$v = $k;
				}

				$this->_value[] = trim( Data_Text::htmlSpecialChars( (string)$v ) );
			}
		} else {
			if( is_string( $default_value ) ) {
				$this->_value = trim( Data_Text::htmlSpecialChars( $default_value ) );
			} else {
				$this->_value = $default_value;
			}
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
	 * Set field options (parameters)
	 *
	 * @param array $properties
	 *
	 * @throws Form_Exception
	 */
	public function setup( array $properties ): void
	{
		foreach( $properties as $o_k => $o_v ) {
			if( !$this->objectHasProperty( $o_k ) ) {
				throw new Form_Exception( 'Unknown form field option: ' . $o_k );
			}
			
			if($o_k=='select_options') {
				/**
				 * @var Form_Field_Select $this
				 */
				$this->setSelectOptions( $o_v );
			} else {
				$this->{$o_k} = $o_v;
			}

		}
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

}
