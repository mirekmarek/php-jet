<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Interface Form_Field_Definition_Interface
 * @package Jet
 */
interface Form_Field_Definition_Interface
{

	/**
	 * @return string
	 */
	public function getFormFieldType();

	/**
	 * @param string $type
	 */
	public function setFormFieldType( $type );

	/**
	 * @return string
	 */
	public function getFormFieldName();

	/**
	 * @return string
	 */
	public function getFormFieldContextClassName();

	/**
	 * @return string
	 */
	public function getFormFieldContextPropertyName();

	/**
	 * @param string $form_field_creator_method_name
	 */
	public function setFormFieldCreatorMethodName( $form_field_creator_method_name );

	/**
	 * @return string
	 */
	public function getFormFieldCreatorMethodName();

	/**
	 * @param callable $form_field_get_select_options_callback
	 */
	public function setFormFieldGetSelectOptionsCallback( $form_field_get_select_options_callback );

	/**
	 * @return callable
	 */
	public function getFormFieldGetSelectOptionsCallback();

	/**
	 * @param string $form_catch_value_method_name
	 */
	public function setFormCatchValueMethodName( $form_catch_value_method_name );

	/**
	 * @return string
	 */
	public function getFormCatchValueMethodName();


	/**
	 * @return bool
	 */
	public function getFormFieldIsRequired();


	/**
	 * @return string|null
	 */
	public function getFormFieldValidationRegexp();


	/**
	 * @return int|float|null
	 */
	public function getFormFieldMinValue();

	/**
	 * @return int|float|null
	 */
	public function getFormFieldMaxValue();

	/**
	 * @param array $options
	 *
	 * @return mixed
	 */
	public function setFormFieldOptions( array $options );

	/**
	 * @return array
	 */
	public function getFormFieldOptions();


	/**
	 * @param string $label
	 *
	 */
	public function setFormFieldLabel( $label );

	/**
	 * @return string
	 */
	public function getFormFieldLabel();

	/**
	 * @param array $messages
	 *
	 */
	public function setFormFieldErrorMessages( array $messages );

	/**
	 * @return array
	 */
	public function getFormFieldErrorMessages();

	/**
	 * @return array|null
	 *
	 * @throws DataModel_Exception
	 */
	public function getFormFieldSelectOptions();

	/**
	 *
	 * @param mixed $property_value
	 *
	 * @throws DataModel_Exception
	 * @return Form_Field|Form_Field[]
	 */
	public function createFormField( $property_value );

	/**
	 * @param object $object_instance
	 * @param mixed &$property
	 * @param mixed $value
	 */
	public function catchFormField( $object_instance, &$property, $value );

}