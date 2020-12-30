<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
trait Form_Field_Definition_Trait
{

	/**
	 * @var string
	 */
	protected string $form_field_creator_method_name = '';

	/**
	 *
	 * @var string|bool
	 */
	protected string|bool $form_field_type = '';

	/**
	 * @var bool
	 */
	protected bool $form_field_is_required = false;

	/**
	 *
	 * @var string
	 */
	protected string $form_field_label = '';

	/**
	 * @var ?string
	 */
	protected ?string $form_field_validation_regexp = null;

	/**
	 * @var null|int|float
	 */
	protected null|int|float $form_field_min_value = null;

	/**
	 * @var null|int|float
	 */
	protected null|int|float $form_field_max_value = null;

	/**
	 *
	 * @var array
	 */
	protected array $form_field_error_messages = [];

	/**
	 *
	 * @var ?callable
	 */
	protected $form_field_get_select_options_callback;

	/**
	 * @var string
	 */
	protected string $form_setter_name = '';

	/**
	 *
	 * @var array
	 */
	protected array $form_field_options = [];


	/**
	 * @return string|bool
	 */
	public function getFormFieldType() : string|bool
	{
		return $this->form_field_type;
	}


	/**
	 * @param string|bool $type
	 */
	public function setFormFieldType( string|bool $type ) : void
	{
		$this->form_field_type = $type;
	}

	/**
	 * @return string
	 */
	public function getFormFieldCreatorMethodName() : string
	{
		return $this->form_field_creator_method_name;
	}

	/**
	 * @param string $form_field_creator_method_name
	 */
	public function setFormFieldCreatorMethodName( string $form_field_creator_method_name ) : void
	{
		$this->form_field_creator_method_name = $form_field_creator_method_name;
	}

	/**
	 * @return callable|null
	 */
	public function getFormFieldGetSelectOptionsCallback() : callable|null
	{
		return $this->form_field_get_select_options_callback;
	}

	/**
	 * @param ?callable $form_field_get_select_options_callback
	 */
	public function setFormFieldGetSelectOptionsCallback( ?callable $form_field_get_select_options_callback ) : void
	{
		$this->form_field_get_select_options_callback = $form_field_get_select_options_callback;
	}

	/**
	 * @return string
	 */
	public function getFormSetterName() : string
	{
		return $this->form_setter_name;
	}

	/**
	 * @param string $form_setter_name
	 */
	public function setFormSetterName( string $form_setter_name ) : void
	{
		$this->form_setter_name = $form_setter_name;
	}

	/**
	 * @return bool
	 */
	public function getFormFieldIsRequired() : bool
	{
		return $this->form_field_is_required;
	}


	/**
	 * @return string|null
	 */
	public function getFormFieldValidationRegexp() : string|null
	{
		return $this->form_field_validation_regexp;
	}


	/**
	 * @return int|float|null
	 */
	public function getFormFieldMinValue() : int|float|null
	{
		return $this->form_field_min_value;
	}

	/**
	 * @return int|float|null
	 */
	public function getFormFieldMaxValue() : int|float|null
	{
		return $this->form_field_max_value;
	}

	/**
	 * @return array
	 */
	public function getFormFieldOptions() : array
	{

		if(
			$this->form_field_validation_regexp &&
			!isset( $this->form_field_options['validation_regexp'] )
		) {
			$this->form_field_options['validation_regexp'] = $this->form_field_validation_regexp;
		}

		if(
			$this->form_field_min_value!==null &&
			!array_key_exists( 'min_value', $this->form_field_options )
		) {
			$this->form_field_options['min_value'] = $this->form_field_min_value;
		}

		if(
			$this->form_field_max_value!==null &&
			!array_key_exists( 'max_value', $this->form_field_options )
		) {
			$this->form_field_options['max_value'] = $this->form_field_max_value;
		}


		return $this->form_field_options;
	}

	/**
	 * @param array $options
	 */
	public function setFormFieldOptions( array $options ) : void
	{
		$this->form_field_options = $options;
	}

	/**
	 * @return string
	 */
	public function getFormFieldLabel() : string
	{
		return $this->form_field_label;
	}

	/**
	 * @param string $label
	 */
	public function setFormFieldLabel( string $label ) : void
	{
		$this->form_field_label = $label;
	}

	/**
	 * @return array
	 */
	public function getFormFieldErrorMessages() : array
	{
		return $this->form_field_error_messages;
	}

	/**
	 * @param array $messages
	 *
	 */
	public function setFormFieldErrorMessages( array $messages ) : void
	{
		$this->form_field_error_messages = $messages;
	}

	/**
	 * @return array|null
	 *
	 * @throws DataModel_Exception
	 */
	public function getFormFieldSelectOptions() : array|null
	{
		/**
		 * @var Form_Field_Definition_Interface|Form_Field_Definition_Trait $this
		 */

		$select_options = null;

		if( $this->form_field_get_select_options_callback ) {
			$callback = $this->form_field_get_select_options_callback;

			if( !is_callable( $callback ) ) {
				throw new DataModel_Exception(
					$this->getFormFieldContextClassName().'::'.$this->getFormFieldName(
					).'::form_field_get_select_options_callback is not callable'
				);
			}

			$select_options = $callback();
		}

		return $select_options;
	}

	/**
	 *
	 * @param mixed $property_value
	 *
	 * @throws DataModel_Exception
	 * @return Form_Field|null|Form_Field[]
	 */
	public function createFormField( mixed $property_value ) : Form_Field|null|array
	{
		/**
		 * @var Form_Field_Definition_Interface|Form_Field_Definition_Trait $this
		 */

		if( !$this->getFormFieldType() ) {
			return null;
		}

		$field = Form_Factory::getFieldInstance(
			$this->getFormFieldType(), $this->getFormFieldName(), $this->getFormFieldLabel(), $property_value,
			$this->getFormFieldIsRequired()
		);

		$field->setErrorMessages( $this->getFormFieldErrorMessages() );
		$field->setOptions( $this->getFormFieldOptions() );

		$select_options = $this->getFormFieldSelectOptions();
		if( $select_options ) {
			$field->setSelectOptions( $select_options );
		}

		return $field;

	}


	/**
	 * @param BaseObject $object_instance
	 * @param mixed      &$property
	 * @param mixed      $value
	 */
	public function catchFormField( BaseObject $object_instance, mixed &$property, mixed $value ) : void
	{
		/**
		 * @var BaseObject                                                  $object_instance
		 * @var Form_Field_Definition_Interface|Form_Field_Definition_Trait $this
		 */

		if( ( $setter_method_name = $this->getFormSetterName() ) ) {
			$object_instance->{$setter_method_name}( $value );

			return;
		}

		$setter_method_name = $object_instance->objectSetterMethodName( $this->getFormFieldContextPropertyName() );

		if( method_exists( $object_instance, $setter_method_name ) ) {
			$object_instance->{$setter_method_name}( $value );

			return;
		}

		$property = $value;

	}

}
