<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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

	/**
	 * @var string
	 */
	protected static string $default_renderer_script = 'field';

	/**
	 * @var string
	 */
	protected static string $default_row_start_renderer_script = 'Field/row/start';

	/**
	 * @var string
	 */
	protected static string $default_row_end_renderer_script = 'Field/row/end';

	/**
	 * @var string
	 */
	protected static string $default_input_container_start_renderer_script = 'Field/input/container/start';

	/**
	 * @var string
	 */
	protected static string $default_input_container_end_renderer_script = 'Field/input/container/end';

	/**
	 * @var string
	 */
	protected static string $default_error_renderer = 'Field/error';

	/**
	 * @var string
	 */
	protected static string $default_label_renderer = 'Field/label';

	/**
	 * @var string string
	 */
	protected static string $default_input_renderer = 'Field/input/';


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
	protected $catcher;

	/**
	 * Options for Select, MultiSelect, RadioButtons and so on ...
	 *
	 * @var array
	 */
	protected array $select_options = [];


	/**
	 *
	 * @param string $name
	 * @param string $label
	 * @param mixed $default_value
	 * @param bool   $is_required
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
	public function getForm() : Form
	{
		return $this->_form;
	}

	/**
	 *
	 * @param Form $form
	 */
	public function setForm( Form $form ) : void
	{
		$this->_form = $form;
	}


	/**
	 *
	 * @return string
	 */
	public function getId() : string
	{
		return $this->_form->getId().'__'.str_replace( '/', '___', $this->getName() );
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
	public function getTagNameValue( ?string $name = null ) : string
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
	 *
	 * @return string
	 */
	public function getName() : string
	{
		return $this->_name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ) : void
	{
		$this->_name = $name;
	}

	/**
	 *
	 * @return mixed
	 */
	public function getDefaultValue() : mixed
	{
		return $this->default_value;
	}

	/**
	 *
	 * @param mixed $default_value
	 */
	public function setDefaultValue( mixed $default_value ) : void
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
					$v instanceof DataModel_Interface
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
			if(is_string($default_value)) {
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
	public function getLabel() : string
	{
		return $this->_( $this->label );
	}

	/**
	 *
	 * @param string $label
	 */
	public function setLabel( string $label ) : void
	{
		$this->label = $label;
	}


	/**
	 * @return string
	 */
	public function getPlaceholder() : string
	{
		return $this->_( $this->placeholder );
	}

	/**
	 * @param string $placeholder
	 */
	public function setPlaceholder( string $placeholder ) : void
	{
		$this->placeholder = $placeholder;
	}

	/**
	 *
	 * @return bool
	 */
	public function getIsRequired() : bool
	{
		return $this->is_required;
	}

	/**
	 *
	 * @param string $required
	 */
	public function setIsRequired( string $required ) : void
	{
		$this->is_required = (bool)$required;
	}




	/**
	 * Set field options (parameters)
	 *
	 * @param array $options
	 *
	 * @throws Form_Exception
	 */
	public function setOptions( array $options ) : void
	{
		foreach( $options as $o_k => $o_v ) {
			if( !$this->objectHasProperty( $o_k ) ) {
				throw new Form_Exception( 'Unknown form field option: '.$o_k );
			}

			$this->{$o_k} = $o_v;
		}
	}

	/**
	 * Options for Select, MultiSelect and so on ...
	 *
	 * @return array
	 */
	public function getSelectOptions() : array
	{
		return $this->select_options;
	}

	/**
	 * Options for Select, MultiSelect and so on ...
	 *
	 * @param array|Iterator $options
	 */
	public function setSelectOptions( array|Iterator $options ) : void
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
	 * @return bool
	 */
	public function getIsReadonly() : bool
	{
		return $this->is_readonly;
	}

	/**
	 * @param bool $is_readonly
	 */
	public function setIsReadonly( bool $is_readonly ) : void
	{
		$this->is_readonly = $is_readonly;
	}


	/**
	 * @return callable|null
	 */
	public function getCatcher() : callable|null
	{
		return $this->catcher;
	}

	/**
	 * @param callable $catcher
	 */
	public function setCatcher( callable $catcher ) : void
	{
		$this->catcher = $catcher;
	}

	/**
	 *
	 */
	public function catchData() : void
	{
		if(
			$this->getIsReadonly() ||
			!$this->getHasValue() ||
			!( $catcher = $this->getCatcher() )
		) {
			return;
		}

		$catcher( $this->getValue() );
	}

	/**
	 *
	 * @return bool
	 */
	public function getHasValue() : bool
	{
		return $this->_has_value;
	}

	/**
	 *
	 * @return mixed
	 */
	public function getValue() : mixed
	{
		return $this->_value;
	}

	/**
	 * @param mixed $value
	 */
	public function setValue( mixed $value ) : void
	{
		$this->_value = $value;
	}

	/**
	 * @return mixed
	 */
	public function getValueRaw() : mixed
	{
		return $this->_value_raw;
	}



	/**
	 * @return array
	 */
	public function jsonSerialize() : array
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
	 * @see Translator
	 *
	 * @param string $phrase
	 * @param array  $data
	 *
	 * @return string
	 */
	public function _( string $phrase, array $data = [] ) : string
	{
		return $this->_form->_( $phrase, $data );
	}

}
