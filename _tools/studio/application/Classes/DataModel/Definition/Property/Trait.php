<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Cache;
use Jet\DataModel;
use Jet\Exception;
use Jet\Form;
use Jet\Form_Field_Checkbox;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\IO_File;
use Jet\Tr;
use Jet\UI;
use ReflectionClass;


/**
 *
 */
trait DataModel_Definition_Property_Trait
{

	/**
	 * @var bool
	 */
	protected bool $_is_inherited = false;

	/**
	 * @var string
	 */
	protected string $_declaring_class_name = '';

	/**
	 * @var bool
	 */
	protected bool $_is_overload = false;

	/**
	 * @var ?Form
	 */
	protected ?Form $__edit_form = null;

	/**
	 * @var DataModel_Class $_class
	 */
	public function setClass( DataModel_Class $_class ): void
	{
		$reflection = $_class->getReflection();

		$property_reflection = $reflection->getProperty( $this->getName() );

		$declaring_class_name = $property_reflection->getDeclaringClass()->getName();
		if( $_class->getFullClassName() != $declaring_class_name ) {
			$this->_is_inherited = true;
			$this->_declaring_class_name = $declaring_class_name;
			$this->_is_overload = false;
		} else {

			$declaring_class_name = $_class->getPropertyDeclaringClass( $this->getName() );
			if( $declaring_class_name ) {
				$this->_is_inherited = true;
				$this->_declaring_class_name = $declaring_class_name;
				$this->_is_overload = true;
			}
		}
	}

	/**
	 * @return bool
	 */
	public function isInherited(): bool
	{
		return $this->_is_inherited;
	}


	/**
	 * @return bool
	 */
	public function isOverload(): bool
	{
		return $this->_is_overload;
	}

	/**
	 * @return string
	 */
	public function getDeclaringClassName(): string
	{
		return $this->_declaring_class_name;
	}





	/**
	 * @return Form
	 */
	public function getEditForm(): Form
	{

		if( !$this->__edit_form ) {

			$name_field = new Form_Field_Input( 'name', 'Property name:', $this->name );
			$name_field->setIsRequired( true );
			$name_field->setErrorMessages( [
				Form_Field_Input::ERROR_CODE_EMPTY          => 'Please enter property name',
				Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid property name format',
				'property_is_not_unique' => 'Property with the same name already exists',
			] );
			$name_field->setFieldValueCatcher( function( $value ) {
				//$this->name = $value;
			} );
			$old_name = $this->getName();
			$name_field->setValidator( function( Form_Field_Input $field ) use ( $old_name ) {
				return DataModel_Definition_Property::checkPropertyName( $field, $old_name );
			} );
			$name_field->setIsReadonly( true );


			$type_field = new Form_Field_Select( 'type', 'Type:', $this->getType() );
			$type_field->setSelectOptions( DataModel_Definition_Property::getPropertyTypes() );
			$type_field->setIsRequired( true );
			$type_field->setErrorMessages( [
				Form_Field_Input::ERROR_CODE_EMPTY          => 'Please select property type',
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select property type'
			] );

			$database_column_name_field = new Form_Field_Input( 'database_column_name', 'Custom column name:', $this->database_column_name );
			$database_column_name_field->setFieldValueCatcher( function( $value ) {
				$this->database_column_name = $value;
			} );


			$is_id_filed = new Form_Field_Checkbox( 'is_id', 'Is ID', $this->getIsId() );
			$is_id_filed->setFieldValueCatcher( function( $value ) {
				$this->is_id = $value;
			} );

			$is_key_filed = new Form_Field_Checkbox( 'is_key', 'Is key (index)', $this->getIsKey() );
			$is_key_filed->setFieldValueCatcher( function( $value ) {
				$this->is_key = $value;
			} );

			$is_unique_filed = new Form_Field_Checkbox( 'is_unique', 'Is unique (index)', $this->getIsUnique() );
			$is_unique_filed->setFieldValueCatcher( function( $value ) {
				$this->is_unique = $value;
			} );


			$is_do_not_export_filed = new Form_Field_Checkbox( 'is_do_not_export', 'Do not export to XML or JSON', $this->doNotExport() );
			$is_do_not_export_filed->setFieldValueCatcher( function( $value ) {
				$this->do_not_export = $value;
			} );

			if( $this->getIsId() && $this->getRelatedToPropertyName() ) {
				$is_do_not_export_filed->setDefaultValue( true );
				$is_do_not_export_filed->setIsReadonly( true );
			}

			$fields = [
				$type_field->getName()                 => $type_field,
				$name_field->getName()                 => $name_field,
				$database_column_name_field->getName() => $database_column_name_field,
				$is_id_filed->getName()                => $is_id_filed,
				$is_key_filed->getName()               => $is_key_filed,
				$is_unique_filed->getName()            => $is_unique_filed,
				$is_do_not_export_filed->getName()     => $is_do_not_export_filed,

			];


			$this->getEditForm_getFormDefinitionFields( $fields );
			$this->getEditFormCustomFields( $fields );

			$form = new Form( 'property_edit_form_' . $this->getName(), $fields );

			$form->setAction( DataModels::getActionUrl( 'property/edit' ) );

			if(
				$this->getRelatedToClassName() &&
				$form->fieldExists( 'type' )
			) {
				$form->field( 'type' )->setIsReadonly( true );
			}

			if( $this->isInherited() ) {
				if( !$this->isOverload() ) {
					$form->setIsReadonly();
				}
			}

			$form->setAction( DataModels::getActionUrl( 'property/edit' ) );

			$this->__edit_form = $form;
		}


		return $this->__edit_form;
	}

	/**
	 * @param array $fields
	 */
	public function getEditForm_getFormDefinitionFields( array &$fields ): void
	{
		$form_field_creator_method_name_filed = new Form_Field_Input( 'form_field_creator_method_name', 'Field creator method:', $this->getFormFieldCreatorMethodName() );
		$form_field_creator_method_name_filed->setFieldValueCatcher( function( $value ) {
			$this->setFormFieldCreatorMethodName( $value );
		} );

		$form_field_type = $this->form_field_type;

		if( $form_field_type === false ) {
			$form_field_type = 'false';
		}
		$form_field_type_field = new Form_Field_Select( 'form_field_type', 'Form field type:', $form_field_type );
		$form_field_type_field->setFieldValueCatcher( function( $value ) {
			if( $value === 'false' ) {
				$value = false;
			}

			$this->setFormFieldType( $value );
		} );
		$so = [];
		foreach( DataModel_Definition_Property::getFormFieldTypes() as $type => $td ) {
			$so[$type] = $td['label'];
		}
		$form_field_type_field->setSelectOptions( $so );
		$form_field_type_field->setErrorMessages( [
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select type'
		] );


		if( $this->getType() == DataModel::TYPE_DATA_MODEL ) {

			$related_model = DataModels::getClass( $this->getDataModelClass() );

			if( $related_model ) {
				$form_field_type_field->setSelectOptions( [
					''      => Tr::_( 'Include to the common form' ),
					'false' => Tr::_( 'DO NOT include to the common form' )
				] );
			}


			$fields[$form_field_type_field->getName()] = $form_field_type_field;
			return;
		}


		$form_field_is_required_filed = new Form_Field_Checkbox( 'form_field_is_required', 'Is required', $this->getFormFieldIsRequired() );
		$form_field_is_required_filed->setFieldValueCatcher( function( $value ) {
			$this->setFormFieldIsRequired( $value );
		} );

		$form_field_label_filed = new Form_Field_Input( 'form_field_label', 'Label:', $this->getFormFieldLabel() );
		$form_field_label_filed->setFieldValueCatcher( function( $value ) {
			$this->setFormFieldLabel( $value );
		} );


		$form_field_validation_regexp_filed = new Form_Field_Input( 'form_field_validation_regexp', 'Validation reg. exp:', $this->getFormFieldValidationRegexp() );
		$form_field_validation_regexp_filed->setFieldValueCatcher( function( $value ) {
			$this->setFormFieldValidationRegexp( $value );
		} );

		$form_field_min_value_filed = new Form_Field_Input( 'form_field_min_value', 'Minimal value:', $this->getFormFieldMinValue() );
		$form_field_min_value_filed->setFieldValueCatcher( function( $value ) {
			if( $value !== '' ) {
				$this->setFormFieldMinValue( $value );
			}
		} );

		$form_field_max_value_filed = new Form_Field_Input( 'form_field_max_value', 'Maximal value:', $this->getFormFieldMaxValue() );
		$form_field_max_value_filed->setFieldValueCatcher( function( $value ) {
			if( $value !== '' ) {
				$this->setFormFieldMaxValue( $value );
			}
		} );


		$_callback = $this->getFormFieldGetSelectOptionsCallback();
		if(
			!is_array( $_callback ) ||
			!isset( $_callback[1] )
		) {
			$_callback = [
				'self::class',
				''
			];
		}

		if( $_callback[0] == DataModels::getCurrentClassName() ) {
			$_callback[0] = 'self::class';
		}

		if(str_contains($_callback[0], '\\')) {
			$_callback[0] = substr($_callback[0], strrpos($_callback[0], '\\')+1).'::class';
		}

		$form_field_get_select_options_callback_filed_class_name = new Form_Field_Input( 'form_field_get_select_options_callback_class_name', 'Select options callback:', $_callback[0] );
		$form_field_get_select_options_callback_filed_method = new Form_Field_Input( 'form_field_get_select_options_callback_method', '', $_callback[1] );
		$form_field_get_select_options_callback_filed_method->setFieldValueCatcher( function( $value ) use ( $form_field_get_select_options_callback_filed_class_name ) {
			if( $form_field_get_select_options_callback_filed_class_name->getValue() && $value ) {
				$this->setFormFieldGetSelectOptionsCallback( [
					$form_field_get_select_options_callback_filed_class_name->getValue(),
					$value
				] );
			} else {
				$this->setFormFieldGetSelectOptionsCallback( null );
			}

		} );


		$form_setter_name_filed = new Form_Field_Input( 'form_setter_name', 'Custom value setter method:', $this->getFormSetterName() );
		$form_setter_name_filed->setFieldValueCatcher( function( $value ) {
			$this->setFormSetterName( $value );
		} );

		$fields[$form_field_creator_method_name_filed->getName()] = $form_field_creator_method_name_filed;
		$fields[$form_field_type_field->getName()] = $form_field_type_field;
		$fields[$form_field_is_required_filed->getName()] = $form_field_is_required_filed;
		$fields[$form_field_label_filed->getName()] = $form_field_label_filed;
		$fields[$form_field_validation_regexp_filed->getName()] = $form_field_validation_regexp_filed;
		$fields[$form_field_min_value_filed->getName()] = $form_field_min_value_filed;
		$fields[$form_field_max_value_filed->getName()] = $form_field_max_value_filed;
		$fields[$form_setter_name_filed->getName()] = $form_setter_name_filed;
		$fields[$form_field_get_select_options_callback_filed_class_name->getName()] = $form_field_get_select_options_callback_filed_class_name;
		$fields[$form_field_get_select_options_callback_filed_method->getName()] = $form_field_get_select_options_callback_filed_method;


		/**
		 * @var Form_Field_Input[] $form_error_message_fields
		 */
		$form_error_message_fields = [];

		$current_messages = $this->getFormFieldErrorMessages();

		foreach( DataModel_Definition_Property::getFormErrorCodes() as $code => $ec_data ) {
			$default_value = '';
			if( $current_messages && isset( $current_messages[$code] ) ) {
				$default_value = $current_messages[$code];

				unset($current_messages[$code]);
			}

			$form_error_message_fields[$code] = new Form_Field_Input( 'form_field_error_messages/' . $code, $ec_data['label'], $default_value );
			$form_error_message_fields[$code]->setFieldValueCatcher( function( $value ) use ( $code ) {
				$messages = $this->getFormFieldErrorMessages();
				if( !$messages || !is_array( $messages ) ) {
					$messages = [];
				}
				$messages[$code] = $value;

				$this->setFormFieldErrorMessages( $messages );
			} );

		}


		foreach( DataModel_Definition_Property::getFormErrorCodes() as $code => $ec_data ) {
			$field = $form_error_message_fields[$code];
			$fields[$field->getName()] = $field;
		}

		$custom_count = count($current_messages)+3;

		$c_codes = array_keys($current_messages);
		$c_messages = array_values($current_messages);

		for($c=1,$i=0;$c<=$custom_count;$c++,$i++) {

			$custom_code_field = new Form_Field_Input( 'form_field_error_messages/custom_code_'.$c , '', $c_codes[$i]??'' );
			$custom_message_field = new Form_Field_Input( 'form_field_error_messages/custom_message_'.$c , '', $c_messages[$i]??'' );

			$custom_message_field->setFieldValueCatcher(function( $value ) use ($custom_code_field, $custom_message_field) {
				$code = $custom_code_field->getValue();
				$message = $custom_message_field->getValue();

				if($code && $message) {
					$messages = $this->getFormFieldErrorMessages();
					if( !$messages || !is_array( $messages ) ) {
						$messages = [];
					}
					$messages[$code] = $message;

					$this->setFormFieldErrorMessages( $messages );

				}
			});

			$fields[$custom_code_field->getName()] = $custom_code_field;
			$fields[$custom_message_field->getName()] = $custom_message_field;
		}

	}

	/**
	 * @return bool|DataModel_Definition_Property_Interface
	 */
	public function catchEditForm(): bool|DataModel_Definition_Property_Interface
	{
		$form = $this->getEditForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$result = $this;

		if(
			$form->fieldExists( 'type' ) &&
			$form->field( 'type' )->getValue() != $this->getType()
		) {
			$type = $form->field( 'type' )->getValue();

			$class_name = __NAMESPACE__ . '\\DataModels_Property_' . $type;

			/**
			 * @var DataModel_Definition_Property_Interface $new_property ;
			 */
			$new_property = new $class_name();
			$new_property->setName( $this->getName() );
			$new_property->setIsId( $this->getIsId() );
			$new_property->setIsKey( $this->getIsKey() );

			DataModels::getCurrentModel()->addProperty( $new_property );

			$result = $new_property;
		}

		$form->catchFieldValues();


		$this->__edit_form = null;

		return $result;
	}


	/**
	 * @return string
	 */
	public function getHeadCssClass(): string
	{
		/*
		$class = 'bg-default';

		if($this->getIsId()) {
			$class='bg-warning';
		}

		if($this->getRelatedToClassName()) {
			$class = 'bg-info';
		}

		return $class;
		*/
		return '';
	}

	/**
	 * @return string
	 */
	public function getTypeDescription(): string
	{
		return DataModel_Definition_Property::getPropertyTypes()[$this->getType()];
	}

	/**
	 * @return string
	 */
	public function getIcons(): string
	{
		$icon = '';

		if( $this->getRelatedToPropertyName() ) {
			$icon .= UI::icon( 'arrows-alt-h' )
				->setSize( 12 )
				->setWidth( 24 )
				->setTitle( Tr::_( 'Related to parent models' ) );
		}

		if( $this->getIsId() ) {
			$icon .= UI::icon( 'magic' )
				->setSize( 12 )
				->setWidth( 24 )
				->setTitle( Tr::_( 'Is ID' ) );
		}

		if( $this->getIsKey() ) {
			$icon .= UI::icon( 'key' )
				->setSize( 12 )
				->setWidth( 24 )
				->setTitle( Tr::_( 'Is key' ) );
		}

		if( $this->isInherited() ) {

			$icon .= UI::icon( 'angle-double-up' )
				->setSize( 12 )
				->setWidth( 24 )
				->setTitle( Tr::_( 'Is inherited' ) );


			if( $this->isOverload() ) {
				$icon .= UI::icon( 'check' )
					->setSize( 12 )
					->setWidth( 24 )
					->setTitle( Tr::_( 'Overloaded' ) );
			} else {
				$icon .= UI::icon( 'times' )
					->setSize( 12 )
					->setWidth( 24 )
					->setTitle( Tr::_( 'Not overloaded' ) );
			}
		}

		return $icon;
	}


	/**
	 *
	 * @param mixed &$value
	 */
	public function checkValueType( mixed &$value ): void
	{
	}


	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
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
	 * @return null|string
	 */
	public function getRelatedToClassName(): string|null
	{
		return $this->related_to_class_name;
	}

	/**
	 * @param null|string $related_to_class_name
	 */
	public function setRelatedToClassName( ?string $related_to_class_name ): void
	{
		$this->related_to_class_name = $related_to_class_name;
	}

	/**
	 * @return null|string
	 */
	public function getRelatedToPropertyName(): string|null
	{
		return $this->related_to_property_name;
	}

	/**
	 * @param null|string $related_to_property_name
	 */
	public function setRelatedToPropertyName( ?string $related_to_property_name ): void
	{
		$this->related_to_property_name = $related_to_property_name;
	}

	/**
	 * @param string $database_column_name
	 */
	public function setDatabaseColumnName( string $database_column_name ): void
	{
		$this->database_column_name = $database_column_name;
	}

	/**
	 * @return bool
	 */
	public function isId(): bool
	{
		return $this->is_id;
	}

	/**
	 * @param bool $is_id
	 */
	public function setIsId( bool $is_id ): void
	{
		$this->is_id = $is_id;
	}

	/**
	 * @return bool
	 */
	public function isKey(): bool
	{
		return $this->is_key;
	}

	/**
	 * @param bool $is_key
	 */
	public function setIsKey( bool $is_key ): void
	{
		$this->is_key = $is_key;
	}

	/**
	 * @return bool
	 */
	public function isUnique(): bool
	{
		return $this->is_unique;
	}

	/**
	 * @param bool $is_unique
	 */
	public function setIsUnique( bool $is_unique ): void
	{
		$this->is_unique = $is_unique;
	}

	/**
	 * @return bool
	 */
	public function isDoNotExport(): bool
	{
		return $this->do_not_export;
	}

	/**
	 * @param bool $do_not_export
	 */
	public function setDoNotExport( bool $do_not_export ): void
	{
		$this->do_not_export = $do_not_export;
	}



	/**
	 * @param string|bool $type
	 */
	public function setFormFieldType( string|bool $type ): void
	{
		$this->form_field_type = $type;
	}

	/**
	 * @param string $form_field_creator_method_name
	 */
	public function setFormFieldCreatorMethodName( string $form_field_creator_method_name ): void
	{
		$this->form_field_creator_method_name = $form_field_creator_method_name;
	}

	/**
	 * @param callable|array|null $form_field_get_select_options_callback
	 */
	public function setFormFieldGetSelectOptionsCallback( callable|array|null $form_field_get_select_options_callback ): void
	{
		$this->form_field_get_select_options_callback = $form_field_get_select_options_callback;
	}

	/**
	 * @param string $form_setter_name
	 */
	public function setFormSetterName( string $form_setter_name ): void
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
	 * @param bool $form_field_is_required
	 */
	public function setFormFieldIsRequired( bool $form_field_is_required ): void
	{
		$this->form_field_is_required = $form_field_is_required;
	}

	/**
	 * @param null|string $form_field_validation_regexp
	 */
	public function setFormFieldValidationRegexp( ?string $form_field_validation_regexp ): void
	{
		$this->form_field_validation_regexp = $form_field_validation_regexp;
	}

	/**
	 * @param float|int|null $form_field_min_value
	 */
	public function setFormFieldMinValue( float|int|null $form_field_min_value ): void
	{
		$this->form_field_min_value = $form_field_min_value;
	}

	/**
	 * @param float|int|null $form_field_max_value
	 */
	public function setFormFieldMaxValue( float|int|null $form_field_max_value ): void
	{
		$this->form_field_max_value = $form_field_max_value;
	}


	/**
	 * @param array $options
	 */
	public function setFormFieldOptions( array $options ): void
	{
		$this->form_field_options = $options;
	}

	/**
	 * @param string $label
	 */
	public function setFormFieldLabel( string $label ): void
	{
		$this->form_field_label = $label;
	}


	/**
	 *
	 * @param ClassCreator_Class $class
	 * @param string $property_type
	 * @param string $data_model_type
	 * @param array $attributes
	 *
	 * @return ClassCreator_Class_Property
	 */
	public function createClassProperty_main( ClassCreator_Class $class,
	                                          string $property_type,
	                                          string $data_model_type,
	                                          array $attributes = []
	): ClassCreator_Class_Property
	{
		$declared_type = $property_type;

		$property = new ClassCreator_Class_Property( $this->getName(), $property_type, $declared_type );

		$property->setDefaultValue( $this->getDefaultValue() );

		if( $this->getRelatedToClassName() ) {

			if( !str_contains( $this->getRelatedToClassName(), ':' ) ) {
				$to_scope = 'main';
				$to_model_class_name = $this->getRelatedToClassName();
			} else {
				[
					$to_scope,
					$to_model_class_name
				] = explode( ':', $this->getRelatedToClassName() );
			}

			$related_to_model = DataModels::getClass( $to_model_class_name )->getDefinition();
			if( $related_to_model ) {
				$related_to_property = $related_to_model->getProperty( $this->getRelatedToPropertyName() );

				$property->setAttribute( 'DataModel_Definition', 'related_to', $to_scope . '.' . $related_to_property->getName() );
			} else {
				$class->addError( 'Unable to get related DataModel definition (related model:' . $to_model_class_name . ')' );
			}

		} else {

			$property->setAttribute( 'DataModel_Definition', 'type', $data_model_type );

		}

		if( $this->database_column_name ) {
			$property->setAttribute( 'DataModel_Definition', 'database_column_name', $this->database_column_name );
		}


		if( $this->getIsId() ) {
			$property->setAttribute( 'DataModel_Definition', 'is_id', true );

		}
		if( $this->is_key ) {
			$property->setAttribute( 'DataModel_Definition', 'is_key', true );
		}
		if( $this->do_not_export ) {
			$property->setAttribute( 'DataModel_Definition', 'do_not_export', true );
		}


		foreach( $attributes as $a ) {
			$property->setAttribute( $a[0], $a[1], $a[2] );
		}

		if( $this->form_field_type!==false ) {
			$class->addUse( (new ClassCreator_UseClass( 'Jet', 'Form' )) );

			if($this->form_field_type) {
				$property->setAttribute( 'DataModel_Definition', 'form_field_type', DataModel_Definition_Property::getFormFieldTypes()[$this->form_field_type]['type'] );
			}

			if( $this->getFormFieldIsRequired() ) {
				$property->setAttribute( 'DataModel_Definition', 'form_field_is_required', true );
			}

			if($this->getFormFieldLabel()) {
				$property->setAttribute( 'DataModel_Definition', 'form_field_label', $this->getFormFieldLabel() );
			}

			if( $this->getFormFieldValidationRegexp() ) {
				$property->setAttribute( 'DataModel_Definition', 'form_field_validation_regexp', $this->getFormFieldValidationRegexp() );
			}

			if( $this->getFormFieldMinValue() !== null && $this->getFormFieldMinValue() !== '' ) {
				$property->setAttribute( 'DataModel_Definition', 'form_field_min_value', $this->getFormFieldMinValue() );
			}
			if( $this->getFormFieldMaxValue() !== null && $this->getFormFieldMaxValue() !== '' ) {
				$property->setAttribute( 'DataModel_Definition', 'form_field_max_value', $this->getFormFieldMaxValue() );
			}

			$callback = $this->getFormFieldGetSelectOptionsCallback();
			if(
				is_array( $callback ) &&
				!empty( $callback[0] ) &&
				!empty( $callback[1] )
			) {
				$property->setAttribute( 'DataModel_Definition', 'form_field_get_select_options_callback', $callback );
			}

			if( $this->getFormSetterName() ) {
				$property->setAttribute( 'DataModel_Definition', 'form_setter_name', $this->getFormSetterName() );
			}

			if( $this->getFormFieldCreatorMethodName() ) {
				$property->setAttribute( 'DataModel_Definition', 'form_field_creator_method_name', $this->getFormFieldCreatorMethodName() );
			}


			$error_messages = $this->getFormFieldErrorMessages();


			if(!($this instanceof DataModel_Definition_Property_DataModel)) {
				$field_type = $this->form_field_type;

				if(!$field_type) {
					if( $this->max_len <= 255 ) {
						$field_type = Form::TYPE_INPUT;
					} else {
						$field_type = Form::TYPE_TEXTAREA;
					}
				}


				$field_class = 'Form_Field_' . $field_type;
				$reflection = new ReflectionClass( '\Jet\\' . $field_class );
				$constants = array_flip( $reflection->getConstants() );


				$e_msg = [];
				foreach( $error_messages as $k => $v ) {
					if( !$v ) {
						unset( $error_messages[$k] );
					} else {
						if(!isset($constants[$k])) {
							$e_msg[$k] = $v;
						} else {
							$class->addUse( new ClassCreator_UseClass( 'Jet', $field_class ) );

							$constant = $field_class . '::' . $constants[$k];
							$e_msg[$constant] = $v;
						}
					}
				}

				if( $e_msg ) {
					$property->setAttribute( 'DataModel_Definition', 'form_field_error_messages', $e_msg );
				}

			}

		} else {
			$property->setAttribute( 'DataModel_Definition', 'form_field_type', false );
		}

		return $property;
	}


	/**
	 * @return string
	 */
	public function getSetterGetterMethodName(): string
	{
		return static::generateSetterGetterMethodName( $this->getName() );
	}

	/**
	 * @param string $name
	 *
	 * @return string
	 */
	public static function generateSetterGetterMethodName( string $name ): string
	{
		$name = explode( '_', $name );

		foreach( $name as $i => $n ) {
			$name[$i] = ucfirst( strtolower( $n ) );
		}

		return implode( '', $name );

	}

	/**
	 *
	 */
	public function prepare(): void
	{
		if( !$this->database_column_name ) {
			$this->database_column_name = $this->getName();
		}
	}


	/**
	 * @param DataModel_Class $class
	 *
	 * @return bool
	 */
	public function update( DataModel_Class $class ): bool
	{
		$ok = true;
		try {
			$model = $class->getDefinition();

			$created_class = $model->createClass();

			if( $created_class->getErrors() ) {
				return false;
			}

			$script = IO_File::read( $class->getScriptPath() );

			$parser = new ClassParser( $script );

			$parser->actualize_updateProperty(
				$class->getClassName(),
				$this->createClassProperty( $created_class )
			);

			$parser->actualize_setUse( $created_class->getUse() );

			IO_File::write(
				$class->getScriptPath(),
				$parser->toString()
			);

			Cache::resetOPCache();


		} catch( Exception $e ) {
			$ok = false;
			Application::handleError( $e );
		}

		return $ok;
	}

	/**
	 * @param DataModel_Class $class
	 *
	 * @return bool
	 */
	public function add( DataModel_Class $class ): bool
	{
		$ok = true;
		try {
			$model = $class->getDefinition();

			$created_class = $model->createClass();

			if( $created_class->getErrors() ) {
				return false;
			}

			$script = IO_File::read( $class->getScriptPath() );

			$parser = new ClassParser( $script );

			$parser->actualize_addProperty(
				$class->getClassName(),
				$this->createClassProperty( $created_class )
			);

			$created_methods = $this->createClassMethods( $created_class );

			foreach( $created_methods as $name ) {
				$method = $created_class->getMethod( $name );

				$parser->actualize_addMethod(
					$class->getClassName(),
					$method
				);
			}

			$parser->actualize_setUse( $created_class->getUse() );

			IO_File::write(
				$class->getScriptPath(),
				$parser->toString()
			);

			Cache::resetOPCache();


		} catch( Exception $e ) {
			$ok = false;
			Application::handleError( $e );
		}

		return $ok;
	}

}