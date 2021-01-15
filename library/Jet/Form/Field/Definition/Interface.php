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
interface Form_Field_Definition_Interface
{

	/**
	 * @return string|bool
	 */
	public function getFormFieldType(): string|bool;

	/**
	 * @param string|bool $type
	 */
	public function setFormFieldType( string|bool $type ): void;

	/**
	 * @return string
	 */
	public function getFormFieldName(): string;

	/**
	 * @return string
	 */
	public function getFormFieldContextClassName(): string;

	/**
	 * @return string
	 */
	public function getFormFieldContextPropertyName(): string;

	/**
	 * @param string $form_field_creator_method_name
	 */
	public function setFormFieldCreatorMethodName( string $form_field_creator_method_name ): void;

	/**
	 * @return string
	 */
	public function getFormFieldCreatorMethodName(): string;

	/**
	 * @param callable|array|null $form_field_get_select_options_callback
	 */
	public function setFormFieldGetSelectOptionsCallback( callable|array|null $form_field_get_select_options_callback ): void;

	/**
	 * @return callable|array|null
	 */
	public function getFormFieldGetSelectOptionsCallback(): callable|array|null;

	/**
	 * @param string $setter_name
	 */
	public function setFormSetterName( string $setter_name ): void;

	/**
	 * @return string
	 */
	public function getFormSetterName(): string;


	/**
	 * @return bool
	 */
	public function getFormFieldIsRequired(): bool;


	/**
	 * @return string|null
	 */
	public function getFormFieldValidationRegexp(): string|null;


	/**
	 * @return int|float|null
	 */
	public function getFormFieldMinValue(): int|float|null;

	/**
	 * @return int|float|null
	 */
	public function getFormFieldMaxValue(): int|float|null;

	/**
	 * @param array $options
	 */
	public function setFormFieldOptions( array $options ): void;

	/**
	 * @return array
	 */
	public function getFormFieldOptions(): array;


	/**
	 * @param string $label
	 *
	 */
	public function setFormFieldLabel( string $label ): void;

	/**
	 * @return string
	 */
	public function getFormFieldLabel(): string;

	/**
	 * @param array $messages
	 *
	 */
	public function setFormFieldErrorMessages( array $messages ): void;

	/**
	 * @return array
	 */
	public function getFormFieldErrorMessages(): array;

	/**
	 * @return array|null
	 *
	 * @throws DataModel_Exception
	 */
	public function getFormFieldSelectOptions(): array|null;

	/**
	 *
	 * @param mixed $property_value
	 *
	 * @return Form_Field|null|Form_Field[]
	 * @throws DataModel_Exception
	 */
	public function createFormField( mixed $property_value ): Form_Field|null|array;

	/**
	 * @param BaseObject $object_instance
	 * @param mixed      &$property
	 * @param mixed $value
	 */
	public function catchFormField( BaseObject $object_instance, mixed &$property, mixed $value ): void;

}