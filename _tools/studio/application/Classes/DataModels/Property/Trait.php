<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel_Definition_Property;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\Form_Field_Checkbox;
use Jet\UI;
use Jet\Tr;

trait DataModels_Property_Trait
{
	/**
	 * @var string
	 */
	protected $internal_id = '';

	/**
	 * @var int
	 */
	protected $internal_priority = 0;

	/**
	 * @var bool
	 */
	protected $is_inherited = false;

	/**
	 * @var string
	 */
	protected $inherited_model_id = '';

	/**
	 * @var string
	 */
	protected $inherited_property_id = '';

	/**
	 * @var bool
	 */
	protected $overload = false;

	/**
	 * @var
	 */
	protected $__edit_form;

	/**
	 * @param DataModels_Parser_Class $class
	 * @param DataModels_Parser_Class_Property $property
	 *
	 * @return DataModels_Property_Interface
	 */
	public static function createByParser( DataModels_Parser_Class $class, DataModels_Parser_Class_Property $property )
	{
		/**
		 * @var DataModels_Property_Interface $res
		 */
		$res = new static();

		$res->setName( $property->getName() );

		foreach( $property->getParameters() as $param ) {
			$param_name = $param->getName();

			$res->{$param_name} = $param->getValue();
		}

		if( $property->isInherited() ) {
			$res->setIsInherited( true );
			$res->setInheritedModelId( $property->getInheritedClassName() );
			$res->setInheritedPropertyId( $property->getName() );

			if( $property->isOverload() ) {
				$res->setOverload( true );
			}
		}


		return $res;
	}


	/**
	 *
	 */
	public function __construct()
	{
		$this->internal_id = uniqid();
	}

	/**
	 *
	 */
	public function __clone()
	{
		$this->internal_id = uniqid();
	}

	/**
	 * @return string
	 */
	public function getInternalId()
	{
		/**
		 * @var DataModels_Model $this
		 */
		return $this->internal_id;
	}

	/**
	 * @param string $id
	 */
	public function setInternalId( $id )
	{
		$this->internal_id = $id;
	}

	/**
	 * @return int
	 */
	public function getInternalPriority()
	{
		return $this->internal_priority;
	}

	/**
	 * @param int $internal_priority
	 */
	public function setInternalPriority($internal_priority)
	{
		$this->internal_priority = $internal_priority;
	}

	/**
	 * @return bool
	 */
	public function isInherited()
	{
		return $this->is_inherited;
	}

	/**
	 * @param bool $is_inherited
	 */
	public function setIsInherited( $is_inherited )
	{
		$this->is_inherited = $is_inherited;
	}

	/**
	 * @return string
	 */
	public function getInheritedModelId()
	{
		return $this->inherited_model_id;
	}

	/**
	 * @param string $inherited_model_id
	 */
	public function setInheritedModelId( $inherited_model_id )
	{
		$this->inherited_model_id = $inherited_model_id;
	}

	/**
	 * @return string
	 */
	public function getInheritedPropertyId()
	{
		return $this->inherited_property_id;
	}

	/**
	 * @param string $inherited_property_id
	 */
	public function setInheritedPropertyId( $inherited_property_id )
	{
		$this->inherited_property_id = $inherited_property_id;
	}

	/**
	 * @return bool
	 */
	public function isOverload()
	{
		return $this->overload;
	}

	/**
	 * @param bool $overload
	 */
	public function setOverload( $overload )
	{
		$this->overload = $overload;
	}


	/**
	 *
	 * @param mixed &$value
	 */
	public function checkValueType(&$value)
	{
	}




	/**
	 * @return string
	 */
	public function getDataModelClassName()
	{
		return $this->data_model_class_name;
	}

	/**
	 * @param string $data_model_class_name
	 */
	public function setDataModelClassName($data_model_class_name)
	{
		$this->data_model_class_name = $data_model_class_name;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return null|string
	 */
	public function getRelatedToClassName()
	{
		return $this->related_to_class_name;
	}

	/**
	 * @param null|string $related_to_class_name
	 */
	public function setRelatedToClassName($related_to_class_name)
	{
		$this->related_to_class_name = $related_to_class_name;
	}

	/**
	 * @return null|string
	 */
	public function getRelatedToPropertyName()
	{
		return $this->related_to_property_name;
	}

	/**
	 * @param null|string $related_to_property_name
	 */
	public function setRelatedToPropertyName($related_to_property_name)
	{
		$this->related_to_property_name = $related_to_property_name;
	}

	/**
	 * @return string
	 */
	public function getDatabaseColumnName()
	{
		return $this->database_column_name;
	}

	/**
	 * @param string $database_column_name
	 */
	public function setDatabaseColumnName($database_column_name)
	{
		$this->database_column_name = $database_column_name;
	}

	/**
	 * @return bool
	 */
	public function isId()
	{
		return $this->is_id;
	}

	/**
	 * @param bool $is_id
	 */
	public function setIsId($is_id)
	{
		$this->is_id = $is_id;
	}

	/**
	 * @return bool
	 */
	public function isKey()
	{
		return $this->is_key;
	}

	/**
	 * @param bool $is_key
	 */
	public function setIsKey($is_key)
	{
		$this->is_key = $is_key;
	}

	/**
	 * @return bool
	 */
	public function isUnique()
	{
		return $this->is_unique;
	}

	/**
	 * @param bool $is_unique
	 */
	public function setIsUnique($is_unique)
	{
		$this->is_unique = $is_unique;
	}

	/**
	 * @return bool
	 */
	public function isDoNotExport()
	{
		return $this->do_not_export;
	}

	/**
	 * @param bool $do_not_export
	 */
	public function setDoNotExport($do_not_export)
	{
		$this->do_not_export = $do_not_export;
	}

	/**
	 * @return string
	 */
	public function getDefaultValue()
	{
		return $this->default_value;
	}

	/**
	 * @param string $default_value
	 */
	public function setDefaultValue($default_value)
	{
		$this->default_value = $default_value;
	}




	/**
	 * @param string $type
	 */
	public function setFormFieldType( $type )
	{
		$this->form_field_type = $type;
	}

	/**
	 * @param string $form_field_creator_method_name
	 */
	public function setFormFieldCreatorMethodName( $form_field_creator_method_name )
	{
		$this->form_field_creator_method_name = $form_field_creator_method_name;
	}

	/**
	 * @param callable $form_field_get_select_options_callback
	 */
	public function setFormFieldGetSelectOptionsCallback( $form_field_get_select_options_callback )
	{
		$this->form_field_get_select_options_callback = $form_field_get_select_options_callback;
	}

	/**
	 * @param string $form_setter_name
	 */
	public function setFormSetterName( $form_setter_name )
	{
		$this->form_setter_name = $form_setter_name;
	}
	


	/**
	 * @param bool $form_field_is_required
	 */
	public function setFormFieldIsRequired($form_field_is_required)
	{
		$this->form_field_is_required = $form_field_is_required;
	}

	/**
	 * @param null|string $form_field_validation_regexp
	 */
	public function setFormFieldValidationRegexp($form_field_validation_regexp)
	{
		$this->form_field_validation_regexp = $form_field_validation_regexp;
	}

	/**
	 * @param float|int|null $form_field_min_value
	 */
	public function setFormFieldMinValue($form_field_min_value)
	{
		$this->form_field_min_value = $form_field_min_value;
	}

	/**
	 * @param float|int|null $form_field_max_value
	 */
	public function setFormFieldMaxValue($form_field_max_value)
	{
		$this->form_field_max_value = $form_field_max_value;
	}


	/**
	 * @param array $options
	 */
	public function setFormFieldOptions( array $options )
	{
		$this->form_field_options = $options;
	}

	/**
	 * @param string $label
	 */
	public function setFormFieldLabel( $label )
	{
		$this->form_field_label = $label;
	}





	/**
	 * @return Form
	 */
	public function getEditForm()
	{

		if(!$this->__edit_form) {

			$name_field = new Form_Field_Input('name', 'Property name:', $this->name);
			$name_field->setIsRequired(true);
			$name_field->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter property name',
				Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid property name format'
			]);
			$name_field->setCatcher( function( $value ) {
				$this->setName( $value );
			} );
			$old_name = $this->getName();
			$name_field->setValidator( function( Form_Field_Input $field ) use ($old_name) {
				return DataModels_Property::checkPropertyName( $field, $old_name );
			} );


			$type_field = new Form_Field_Select('type', 'Type:', $this->getType());
			$type_field->setSelectOptions( DataModels_Property::getPropertyTypes() );
			$type_field->setIsRequired( true );
			$type_field->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please select property type',
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select property type'
			]);

			$database_column_name_field = new Form_Field_Input('database_column_name', 'Custom column name:', $this->getDatabaseColumnName());
			$database_column_name_field->setCatcher( function( $value ) {
				$this->setDatabaseColumnName( $value );
			} );


			$is_id_filed = new Form_Field_Checkbox('is_id', 'Is ID', $this->getIsId());
			$is_id_filed->setCatcher( function( $value ) {
				$this->setIsId( $value );
			} );

			$is_key_filed = new Form_Field_Checkbox('is_key', 'Is key (index)', $this->getIsKey());
			$is_key_filed->setCatcher( function( $value ) {
				$this->setIsKey( $value );
			} );

			$is_unique_filed = new Form_Field_Checkbox('is_unique', 'Is unique (index)', $this->getIsUnique());
			$is_unique_filed->setCatcher( function( $value ) {
				$this->setIsUnique( $value );
			} );


			$is_do_not_export_filed = new Form_Field_Checkbox('is_do_not_export', 'Do not export to XML or JSON', $this->isDoNotExport());
			$is_do_not_export_filed->setCatcher( function( $value ) {
				$this->setDoNotExport( $value );
			} );

			if($this->getIsId() && $this->getRelatedToPropertyName()) {
				$is_do_not_export_filed->setDefaultValue(true);
				$is_do_not_export_filed->setIsReadonly(true);
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

			$form = new Form( 'property_edit_form_'.$this->getInternalId(), $fields );

			$form->setAction( DataModels::getActionUrl('property/edit', ['property' =>$this->getInternalId()]) );

			if(
				$this->getRelatedToClassName() &&
				$form->fieldExists('type')
			) {
				$form->field('type')->setIsReadonly(true);
			}

			if(
				$this->isInherited()
			) {
				if(!$this->isOverload()) {
					foreach( $form->getFields() as $field ) {
						$field->setIsReadonly( true );
					}
				}

				$overload_field = new Form_Field_Checkbox('overload', 'Overload this property', $this->isOverload());
				$overload_field->setCatcher( function($value) {
					$this->setOverload( $value );
				} );

				$form->addField( $overload_field );

			}

			$this->__edit_form = $form;
		}


		return $this->__edit_form;
	}

	/**
	 * @param array $fields
	 */
	public function getEditForm_getFormDefinitionFields( array &$fields )
	{
		$form_field_creator_method_name_filed = new Form_Field_Input('form_field_creator_method_name', 'Field creator method:', $this->getFormFieldCreatorMethodName());
		$form_field_creator_method_name_filed->setCatcher( function( $value ) {
			$this->setFormFieldCreatorMethodName( $value );
		} );

		$form_field_type = $this->getFormFieldType();
		if($form_field_type===false) {
			$form_field_type = 'false';
		}
		$form_field_type_field = new Form_Field_Select('form_field_type', 'Form field type:', $form_field_type );
		$form_field_type_field->setCatcher( function( $value ) {
			if($value==='false') {
				$value = false;
			}
			$this->setFormFieldType( $value );
		} );
		$so = [];
		foreach( DataModels_Property::getFormFieldTypes() as $type=>$td ) {
			$so[$type] = $td['label'];
		}
		$form_field_type_field->setSelectOptions( $so );
		$form_field_type_field->setErrorMessages([
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select type'
		]);

		$form_field_is_required_filed = new Form_Field_Checkbox('form_field_is_required', 'Is required', $this->getFormFieldIsRequired());
		$form_field_is_required_filed->setCatcher( function( $value ) {
			$this->setFormFieldIsRequired( $value );
		} );

		$form_field_label_filed = new Form_Field_Input('form_field_label', 'Label:', $this->getFormFieldLabel());
		$form_field_label_filed->setCatcher( function( $value ) {
			$this->setFormFieldLabel( $value );
		} );


		$form_field_validation_regexp_filed = new Form_Field_Input('form_field_validation_regexp', 'Validation reg. exp:', $this->getFormFieldValidationRegexp());
		$form_field_validation_regexp_filed->setCatcher( function( $value ) {
			$this->setFormFieldValidationRegexp( $value );
		} );

		$form_field_min_value_filed = new Form_Field_Input('form_field_min_value', 'Minimal value:', $this->getFormFieldMinValue());
		$form_field_min_value_filed->setCatcher( function( $value ) {
			$this->setFormFieldMinValue( $value );
		} );

		$form_field_max_value_filed = new Form_Field_Input('form_field_max_value', 'Maximal value:', $this->getFormFieldMaxValue());
		$form_field_max_value_filed->setCatcher( function( $value ) {
			$this->setFormFieldMaxValue( $value );
		} );


		$_callback = $this->getFormFieldGetSelectOptionsCallback();
		if(
			!is_array($_callback) ||
			!isset($_callback[1])
		) {
			$_callback = ['', ''];
		}


		$form_field_get_select_options_callback_filed_class_name = new Form_Field_Input('form_field_get_select_options_callback_class_name', 'Select options callback:', $_callback[0]);
		$form_field_get_select_options_callback_filed_method = new Form_Field_Input('form_field_get_select_options_callback_method', '', $_callback[1]);
		$form_field_get_select_options_callback_filed_method->setCatcher( function( $value ) use ($form_field_get_select_options_callback_filed_class_name) {
			$this->setFormFieldGetSelectOptionsCallback( [
				$form_field_get_select_options_callback_filed_class_name->getValue(),
				$value
			] );
		} );


		$form_setter_name_filed = new Form_Field_Input('form_setter_name', 'Custom value setter method:', $this->getFormSetterName());
		$form_setter_name_filed->setCatcher( function( $value ) {
			$this->setFormSetterName( $value );
		} );


		/**
		 * @var Form_Field_Input[] $form_error_message_fields
		 */
		$form_error_message_fields = [];

		$current_messages = $this->getFormFieldErrorMessages();
		foreach( DataModels_Property::getFormErrorCodes() as $code=>$ec_data ) {
			$default_value = '';
			if($current_messages && isset($current_messages[$code])) {
				$default_value = $current_messages[$code];
			}

			$form_error_message_fields[$code] = new Form_Field_Input('form_field_error_messages/'.$code, $ec_data['label'], $default_value);
			$form_error_message_fields[$code]->setCatcher( function( $value ) use ($code) {
				$messages = $this->getFormFieldErrorMessages();
				if(!$messages || !is_array($messages)) {
					$messages = [];
				}
				$messages[$code] = $value;

				$this->setFormFieldErrorMessages( $messages );
			} );

		}



		$fields[$form_field_creator_method_name_filed->getName()]         = $form_field_creator_method_name_filed;
		$fields[$form_field_type_field->getName()]                        = $form_field_type_field;
		$fields[$form_field_is_required_filed->getName()]                 = $form_field_is_required_filed;
		$fields[$form_field_label_filed->getName()]                       = $form_field_label_filed;
		$fields[$form_field_validation_regexp_filed->getName()]           = $form_field_validation_regexp_filed;
		$fields[$form_field_min_value_filed->getName()]                   = $form_field_min_value_filed;
		$fields[$form_field_max_value_filed->getName()]                   = $form_field_max_value_filed;
		$fields[$form_setter_name_filed->getName()]                       = $form_setter_name_filed;
		$fields[$form_field_get_select_options_callback_filed_class_name->getName()] = $form_field_get_select_options_callback_filed_class_name;
		$fields[$form_field_get_select_options_callback_filed_method->getName()]     = $form_field_get_select_options_callback_filed_method;


		foreach( DataModels_Property::getFormErrorCodes() as $code=>$ec_data ) {
			$field = $form_error_message_fields[$code];
			$fields[$field->getName()] = $field;
		}

	}

	/**
	 * @return bool|DataModels_Property_Interface
	 */
	public function catchEditForm()
	{
		$form = $this->getEditForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$was_id = $this->getIsId();

		$result = $this;

		$need_to_actualize_relations = false;

		if(
			$form->fieldExists('type') &&
			$form->field('type')->getValue()!=$this->getType()
		) {
			$type = $form->field('type')->getValue();

			$class_name = __NAMESPACE__.'\\DataModels_Property_'.$type;

			/**
			 * @var DataModels_Property_Interface $new_property;
			 */
			$new_property = new $class_name();
			$new_property->setName( $this->getName() );
			$new_property->setInternalId( $this->getInternalId() );
			$new_property->setInternalPriority( $this->getInternalPriority() );
			$new_property->setIsId( $this->getIsId() );
			$new_property->setIsKey( $this->getIsKey() );

			DataModels::getCurrentModel()->removeProperty( $this->getInternalId() );
			DataModels::getCurrentModel()->addProperty( $new_property );

			$need_to_actualize_relations = true;

			$result = $new_property;
		}

		$form->catchData();

		$is_id = $result->getIsId();

		if( $was_id || $is_id ) {
			$need_to_actualize_relations = true;
		}

		if( $need_to_actualize_relations ) {
			foreach( DataModels::getCurrentModel()->getChildren() as $ch ) {
				$ch->checkIdProperties();
				$ch->checkSortOfProperties();
			}
		}

		$this->__edit_form = null;

		return $result;
	}

	/**
	 *
	 */
	public function showEditForm()
	{
		$form = $this->getEditForm();

		echo $form->start();

		$default_fields = [
			'overload',

			'type',
			'name',
			'database_column_name',

			'is_id',
			'is_key',
			'is_do_not_export',

		];

		foreach( $default_fields as $fn ) {
			if(!$form->fieldExists($fn)) {
				continue;
			}

			echo $form->field($fn);
		}


		$this->showEditFormFields();

		if(!$form->getIsReadonly()) {
			echo '<div style="text-align: right">';
			echo UI::button_save()->setType('button')->setOnclick('JetStudio.DataModel.property.edit.save(\''.$this->getInternalId().'\')');
			echo '</div>';
		}

		$this->showEditForm_formFieldDefinition();



		echo $form->end();
	}

	/**
	 *
	 */
	public function showEditForm_formFieldDefinition()
	{
		if($this->getRelatedToClassName()) {
			return;
		}

		$form = $this->getEditForm();


		if($this->getDataModelClassName()) {

			$related_model = DataModels::getModel( $this->getDataModelClassName() );

			if(
				$related_model &&
				!($related_model instanceof DataModels_Model_Related_MtoN)
			) {
				echo '<legend>'.Tr::_('Form definition').'</legend>';

				$type = $form->field('form_field_type');
				//TODO: ten select blbne
				$type->setSelectOptions([
					'' => Tr::_('Include to the common form'),
					'false' => Tr::_('DO NOT include to the common form')
				]);

				echo $type;

				if(!$form->getIsReadonly()) {
					echo '<div style="text-align: right">';
					echo UI::button_save()->setType('button')->setOnclick('JetStudio.DataModel.property.edit.save(\''.$this->getInternalId().'\')');
					echo '</div>';
				}

			}



			return;
		}

		echo '<legend>'.Tr::_('Form field definition').'</legend>';


		$fields = [
			'form_field_type',
			'form_field_is_required',
			'form_field_label',
			'form_field_validation_regexp',
			'form_field_min_value',
			'form_field_max_value',
			'form_field_get_select_options_callback',
			'form_setter_name',
			'form_field_creator_method_name',
		];

		$selected_form_field_type = $form->field('form_field_type')->getValue();

		$ff_types = DataModels_Property::getFormFieldTypes();
		$selected_property_data = [
			'required_options' => [],
			'required_error_messages' => [],
		];

		if(isset($ff_types[$selected_form_field_type])) {
			$selected_property_data = $ff_types[$selected_form_field_type];
		}

		foreach( $fields as $f ) {
			if($f=='form_field_get_select_options_callback') {
				if(
					!$form->fieldExists($f.'_class_name') ||
					!$form->fieldExists($f.'_method')
				) {
					continue;
				}

				$field_class_name = $form->field($f.'_class_name');
				$field_method = $form->field($f.'_method');

				$field_class_name->row()
					->addCustomCssClass('ffd-property-'.$this->getInternalId())
					->addCustomCssClass('ffd-option-'.$f);

				if(!in_array($f, $selected_property_data['required_options'])) {
					$field_class_name->row()->addCustomCssStyle('display:none');
				}

				?>
				<?=$field_class_name->row()->start()?>
				<?=$field_class_name->label()?>
				<?=$field_class_name->error()?>
				<div class="input-group" style="padding-left: 15px;margin-right: 15px;">
					<span class="input-group-addon"></span>
					<?=$field_class_name->input()?>
					<span class="input-group-addon">::</span>
					<?=$field_method->input()?>
					<span class="input-group-addon">()</span>
				</div>
				<?=$field_class_name->row()->end()?>
				<?php

			}

			if(!$form->fieldExists($f)) {
				continue;
			}

			$field = $form->field($f);

			if(
				$f=='form_field_type'
			) {
				$field->input()->addJsAction('onchange', "JetStudio.DataModel.property.edit.selectFormFieldType('".$this->getInternalId()."', this.value)");
			} else {
				$field->row()
					->addCustomCssClass('ffd-property-'.$this->getInternalId())
					->addCustomCssClass('ffd-option-'.$f);

				if(!in_array($f, $selected_property_data['required_options'])) {
					$field->row()->addCustomCssStyle('display:none');
				}
			}

			echo $field;
		}

		echo '<legend>'.Tr::_('Form field error messages').'</legend>';

		foreach( DataModels_Property::getFormErrorCodes() as $code=>$ec_data ) {
			$f = 'form_field_error_messages/'.$code;

			if(!$form->fieldExists($f)) {
				continue;
			}

			$field = $form->field($f);

			$field->row()
				->addCustomCssClass('ffd-em-property-'.$this->getInternalId())
				->addCustomCssClass('ffd-em-'.$code);

			if(!in_array($code, $selected_property_data['required_error_messages'])) {
				$field->row()->addCustomCssStyle('display:none');
			} else {
				if($code==Form_Field_Input::ERROR_CODE_EMPTY) {
					if($this->form_field_is_required) {
						$field->setIsRequired(true);
					}
				} else {
					$field->setIsRequired(true);
				}
			}

			echo $field;
		}

		if(!$form->getIsReadonly()) {
			echo '<div style="text-align: right">';
			echo UI::button_save()->setType('button')->setOnclick('JetStudio.DataModel.property.edit.save(\''.$this->getInternalId().'\')');
			echo '</div>';
		}


	}


	/**
	 * @return bool
	 */
	public function canBeDeleted()
	{

		if($this->getDataModelClassName()) {
			$related_model = DataModels::getModel( $this->getDataModelClassName() );

			if(!$related_model) {
				return true;
			}

			return false;
		}

		if(
			$this->getRelatedToClassName()
		) {
			return false;
		}

		return true;
	}


	/**
	 * @return string
	 */
	public function getHeadCssClass()
	{
		$class = 'primary';

		if($this->getIsId()) {
			$class='danger';
		}

		if($this->getRelatedToClassName()) {
			$class = 'info';
		}

		if($this->getDataModelClassName()) {
			$class = 'success';
		}

		return $class;
	}

	/**
	 * @return string
	 */
	public function getIcons()
	{
		$icon = UI::icon('info-circle')
			->setTitle( DataModels_Property::getPropertyTypes()[$this->getType()] )
			->setSize(20)
			->setWidth(30);

		if( $this->getRelatedToPropertyName() ) {
			$icon .= UI::icon('arrows-h')
				->setSize(20)
				->setWidth(30)
				->setTitle( Tr::_('Related to parent models') );
		}

		if( $this->getIsId() ) {
			$icon .= UI::icon('magic')
				->setSize(20)
				->setWidth(30)
				->setTitle( Tr::_('Is ID') );
		}

		if( $this->getIsKey() ) {
			$icon .= UI::icon('flash')
				->setSize(20)
				->setWidth(30)
				->setTitle( Tr::_('Is key') );
		}

		if( $this->isInherited() ) {

			$icon .= UI::icon('angle-double-up')
				->setSize(20)
				->setWidth(20)
				->setTitle( Tr::_('Is inherited') );


			if($this->isOverload()) {
				$icon .= UI::icon('check')
					->setSize(20)
					->setWidth(20)
					->setTitle( Tr::_('Overloaded') );
			} else {
				$icon .= UI::icon('times')
					->setSize(20)
					->setWidth(20)
					->setTitle( Tr::_('Not overloaded') );
			}
		}

		return $icon;
	}

	/**
	 *
	 * @param ClassCreator_Class $class
	 * @param string $property_type
	 * @param string $data_model_type
	 * @param ClassCreator_Annotation[] $annotations
	 *
	 * @return ClassCreator_Class_Property
	 */
	public function createClassProperty_main(
		ClassCreator_Class $class,
		$property_type,
		$data_model_type,
		array $annotations=[]
	) {
		$property = new ClassCreator_Class_Property( $this->getName(), $property_type );

		if($this->getRelatedToClassName()) {
			if(strpos($this->getRelatedToClassName(), ':')===false) {
				$to_scope = 'main';
				$to_model_id = $this->getRelatedToClassName();
			} else {
				list($to_scope, $to_model_id) = explode(':', $this->getRelatedToClassName());
			}

			$related_to_model = DataModels::getModel($to_model_id);
			if($related_to_model) {
				$related_to_property = $related_to_model->getProperty( $this->getRelatedToPropertyName() );

				$property->addAnnotation( new ClassCreator_Annotation('JetDataModel', 'related_to', var_export($to_scope.'.'.$related_to_property->getName(), true)) );
			} else {
				$class->addError('Unable to get related DataModel definition (related model ID:'.$to_model_id.')');
			}

		} else {

			$property->addAnnotation( new ClassCreator_Annotation('JetDataModel', 'type', $data_model_type) );

		}

		if($this->database_column_name) {
			$property->addAnnotation( new ClassCreator_Annotation('JetDataModel', 'database_column_name', var_export($this->database_column_name, true)) );
		}


		if($this->getIsId()) {
			$property->addAnnotation( new ClassCreator_Annotation('JetDataModel', 'is_id', 'true') );

		}
		if($this->is_key) {
			$property->addAnnotation( new ClassCreator_Annotation('JetDataModel', 'is_key', 'true') );
		}
		if($this->do_not_export) {
			$property->addAnnotation( new ClassCreator_Annotation('JetDataModel', 'do_not_export', 'true') );
		}



		foreach( $annotations as $annotation ) {
			$property->addAnnotation( $annotation );
		}


		if($this->getFormFieldType()) {
			$class->addUse( (new ClassCreator_UseClass('Jet', 'Form')) );

			$form_field_type = DataModels_Property::getFormFieldTypes()[$this->getFormFieldType()]['type'];

			$property->addAnnotation( new ClassCreator_Annotation('JetDataModel', 'form_field_type', $form_field_type ) );
			if($this->getFormFieldIsRequired()) {
				$property->addAnnotation( new ClassCreator_Annotation('JetDataModel', 'form_field_is_required', 'true' ) );
			}

			$property->addAnnotation( new ClassCreator_Annotation('JetDataModel', 'form_field_label', var_export($this->getFormFieldLabel(), true) ) );

			if( $this->getFormFieldValidationRegexp() ) {
				$property->addAnnotation( new ClassCreator_Annotation('JetDataModel', 'form_field_validation_regexp', var_export($this->getFormFieldValidationRegexp(), true) ) );
			}

			if( $this->getFormFieldMinValue()!==null && $this->getFormFieldMinValue()!=='' ) {
				$property->addAnnotation( new ClassCreator_Annotation('JetDataModel', 'form_field_min_value', $this->getFormFieldMinValue() ) );
			}
			if( $this->getFormFieldMaxValue()!==null &&  $this->getFormFieldMaxValue()!=='' ) {
				$property->addAnnotation( new ClassCreator_Annotation('JetDataModel', 'form_field_max_value', $this->getFormFieldMaxValue() ) );
			}

			$callback = $this->getFormFieldGetSelectOptionsCallback();
			if(
					is_array($callback) &&
					!empty($callback[0]) &&
					!empty($callback[1])
			) {
				$property->addAnnotation( new ClassCreator_Annotation('JetDataModel', 'form_field_get_select_options_callback', "['{$callback[0]}','{$callback[1]}']" ) );
			}

			if( $this->getFormSetterName() ) {
				$property->addAnnotation( new ClassCreator_Annotation('JetDataModel', 'form_setter_name', var_export($this->getFormSetterName(), true) ) );
			}

			if( $this->getFormFieldCreatorMethodName() ) {
				$property->addAnnotation( new ClassCreator_Annotation('JetDataModel', 'form_field_creator_method_name', var_export($this->getFormFieldCreatorMethodName(), true) ) );
			}


			$error_messages = $this->getFormFieldErrorMessages();

			foreach( $error_messages as $k=>$v ) {
				if(!$v) {
					unset( $error_messages[$k] );
				} else {
					$error_messages[ $k ] = var_export($v, true);
				}
			}

			if($error_messages) {
				$property->addAnnotation( new ClassCreator_Annotation('JetDataModel', 'form_field_error_messages', $error_messages ) );
			}


		} else {
			$property->addAnnotation( new ClassCreator_Annotation('JetDataModel', 'form_field_type', 'false' ) );

		}

		return $property;
	}


	/**
	 * @return string
	 */
	public function getSetterGetterMethodName()
	{
		return static::generateSetterGetterMethodName( $this->getName() );
	}

	/**
	 * @param $name
	 *
	 * @return string
	 */
	public static function generateSetterGetterMethodName( $name )
	{
		$name = explode('_', $name);

		foreach( $name as $i=>$n ) {
			$name[$i] = ucfirst(strtolower($n));
		}

		$name = implode('', $name);

		return $name;

	}

	/**
	 *
	 */
	public function prepare()
	{
		if(!$this->database_column_name) {
			$this->database_column_name = $this->getName();
		}
	}

}