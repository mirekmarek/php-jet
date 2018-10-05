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
abstract class Form_Field extends BaseObject implements \JsonSerializable
{

	use Form_Field_Trait_Validation;
	use Form_Field_Trait_Render;


	const ERROR_CODE_EMPTY = 'empty';
	const ERROR_CODE_INVALID_FORMAT = 'invalid_format';

	/**
	 * @var string
	 */
	protected $_type = '';

	/**
	 *
	 * @var string
	 */
	protected $_name = '';

	/**
	 * @var Form
	 */
	protected $_form = null;

	/**
	 *
	 * @var mixed
	 */
	protected $_value_raw;

	/**
	 *
	 * @var mixed
	 */
	protected $_value;

	/**
	 *
	 * @var bool
	 */
	protected $_has_value = false;


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
	 * @var callable
	 */
	protected $catcher;

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

		if( $default_value instanceof DataModel_IDController ) {
			$default_value = $default_value->toString();
		}

		if(
			is_array( $default_value ) ||
			(
				is_object( $default_value ) &&
				$default_value instanceof \Iterator
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
		return $this->_( $this->label );
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
	 * @return string
	 */
	public function getPlaceholder()
	{
		return $this->_( $this->placeholder );
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
	 * Set field options
	 *
	 * @param array $options
	 *
	 * @throws Form_Exception
	 */
	public function setOptions( array $options )
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
	 * @return callable
	 */
	public function getCatcher()
	{
		return $this->catcher;
	}

	/**
	 * @param callable $catcher
	 */
	public function setCatcher( callable $catcher )
	{
		$this->catcher = $catcher;
	}

	/**
	 *
	 */
	public function catchData()
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
	public function getHasValue()
	{
		return $this->_has_value;
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
	 * @param mixed $value
	 */
	public function setValue( $value )
	{
		$this->_value = $value;
	}

	/**
	 * @return mixed
	 */
	public function getValueRaw()
	{
		return $this->_value_raw;
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
	 * @see Translator
	 *
	 * @param string $phrase
	 * @param array  $data
	 *
	 * @return string
	 */
	public function _( $phrase, $data = [] )
	{
		return $this->_form->_( $phrase, $data );
	}

}
