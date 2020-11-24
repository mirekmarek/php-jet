<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel;
use Jet\Exception;
use Jet\Form;
use Jet\Form_Field_Checkbox;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\IO_File;
use Jet\Tr;
use Jet\UI;


/**
 *
 */
trait DataModel_Definition_Property_Trait
{

	/**
	 * @var bool
	 */
	protected $_is_inherited = false;

	/**
	 * @var string
	 */
	protected $_declaring_class_name = '';

	/**
	 * @var bool
	 */
	protected $_is_overload = false;

	/**
	 * @var
	 */
	protected $__edit_form;

	/**
	 * @var DataModel_Class $_class
	 */
	public function setClass( DataModel_Class $_class )
	{
		$reflection = $_class->getReflection();

		$property_reflection = $reflection->getProperty( $this->getName() );

		$declaring_class_name = $property_reflection->getDeclaringClass()->getName();
		if($_class->getFullClassName()!=$declaring_class_name) {
			$this->_is_inherited = true;
			$this->_declaring_class_name = $declaring_class_name;
			$this->_is_overload = false;
		} else {

			$declaring_class_name = $_class->getPropertyDeclaringClass( $this->getName() );
			if($declaring_class_name) {
				$this->_is_inherited = true;
				$this->_declaring_class_name = $declaring_class_name;
				$this->_is_overload = true;
			}
		}
	}

	/**
	 * @return bool
	 */
	public function isInherited()
	{
		return $this->_is_inherited;
	}


	/**
	 * @return bool
	 */
	public function isOverload()
	{
		return $this->_is_overload;
	}

	/**
	 * @return string
	 */
	public function getDeclaringClassName()
	{
		return $this->_declaring_class_name;
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
				$this->name = $value;
			} );
			$old_name = $this->getName();
			$name_field->setValidator( function( Form_Field_Input $field ) use ($old_name) {
				return DataModel_Definition_Property::checkPropertyName( $field, $old_name );
			} );


			$type_field = new Form_Field_Select('type', 'Type:', $this->getType());
			$type_field->setSelectOptions( DataModel_Definition_Property::getPropertyTypes() );
			$type_field->setIsRequired( true );
			$type_field->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please select property type',
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select property type'
			]);

			$database_column_name_field = new Form_Field_Input('database_column_name', 'Custom column name:', $this->getDatabaseColumnName());
			$database_column_name_field->setCatcher( function( $value ) {
				$this->database_column_name = $value;
			} );


			$is_id_filed = new Form_Field_Checkbox('is_id', 'Is ID', $this->getIsId());
			$is_id_filed->setCatcher( function( $value ) {
				$this->is_id = $value;
			} );

			$is_key_filed = new Form_Field_Checkbox('is_key', 'Is key (index)', $this->getIsKey());
			$is_key_filed->setCatcher( function( $value ) {
				$this->is_key = $value;
			} );

			$is_unique_filed = new Form_Field_Checkbox('is_unique', 'Is unique (index)', $this->getIsUnique());
			$is_unique_filed->setCatcher( function( $value ) {
				$this->is_unique = $value;
			} );


			$is_do_not_export_filed = new Form_Field_Checkbox('is_do_not_export', 'Do not export to XML or JSON', $this->doNotExport());
			$is_do_not_export_filed->setCatcher( function( $value ) {
				$this->do_not_export = $value;
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

			$form = new Form( 'property_edit_form_'.$this->getName(), $fields );

			$form->setAction( DataModels::getActionUrl('property/edit') );

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
					/*
					foreach( $form->getFields() as $field ) {
						$field->setIsReadonly( true );
					}
					*/
					$form->setIsReadonly();
				}

				/*
				$overload_field = new Form_Field_Checkbox('overload', 'Overload this property', $this->isOverload());
				$overload_field->setCatcher( function($value) {
					$this->setOverload( $value );
				} );

				$form->addField( $overload_field );
				*/

			}

			$form->setAction( DataModels::getActionUrl('property/edit') );

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
		foreach( DataModel_Definition_Property::getFormFieldTypes() as $type=> $td ) {
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
		foreach( DataModel_Definition_Property::getFormErrorCodes() as $code=> $ec_data ) {
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


		foreach( DataModel_Definition_Property::getFormErrorCodes() as $code=> $ec_data ) {
			$field = $form_error_message_fields[$code];
			$fields[$field->getName()] = $field;
		}

	}

	/**
	 * @return bool|DataModel_Definition_Property_Interface
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

		$result = $this;

		if(
			$form->fieldExists('type') &&
			$form->field('type')->getValue()!=$this->getType()
		) {
			$type = $form->field('type')->getValue();

			$class_name = __NAMESPACE__.'\\DataModels_Property_'.$type;

			/**
			 * @var DataModel_Definition_Property_Interface $new_property;
			 */
			$new_property = new $class_name();
			$new_property->setName( $this->getName() );
			$new_property->setIsId( $this->getIsId() );
			$new_property->setIsKey( $this->getIsKey() );

			DataModels::getCurrentModel()->addProperty( $new_property );

			$result = $new_property;
		}

		$form->catchData();


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
			//'overload',

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


		if($this->getType()==DataModel::TYPE_DATA_MODEL) {

			$related_model = DataModels::getClass( $this->getDataModelClass() );

			if(
				$related_model &&
				!($related_model instanceof DataModel_Definition_Model_Related_MtoN)
			) {
				echo '<legend>'.Tr::_('Form definition').'</legend>';

				$type = $form->field('form_field_type');

				$type->setSelectOptions([
					'' => Tr::_('Include to the common form'),
					'false' => Tr::_('DO NOT include to the common form')
				]);

				echo $type;

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

		$ff_types = DataModel_Definition_Property::getFormFieldTypes();
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
					->addCustomCssClass('ffd-property-'.$this->getName())
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
				$field->input()->addJsAction('onchange', "DataModel.property.edit.selectFormFieldType('".$this->getName()."', this.value)");
			} else {
				$field->row()
					->addCustomCssClass('ffd-property-'.$this->getName())
					->addCustomCssClass('ffd-option-'.$f);

				if(!in_array($f, $selected_property_data['required_options'])) {
					$field->row()->addCustomCssStyle('display:none');
				}
			}

			echo $field;
		}

		echo '<legend>'.Tr::_('Form field error messages').'</legend>';

		foreach( DataModel_Definition_Property::getFormErrorCodes() as $code=> $ec_data ) {
			$f = 'form_field_error_messages/'.$code;

			if(!$form->fieldExists($f)) {
				continue;
			}

			$field = $form->field($f);

			$field->row()
				->addCustomCssClass('ffd-em-property-'.$this->getName())
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


	}




	/**
	 * @return string
	 */
	public function getHeadCssClass()
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
	public function getTypeDescription()
	{
		return DataModel_Definition_Property::getPropertyTypes()[$this->getType()];
	}

	/**
	 * @return string
	 */
	public function getIcons()
	{
		$icon = '';

		if( $this->getRelatedToPropertyName() ) {
			$icon .= UI::icon('arrows-alt-h')
				->setSize(12)
				->setWidth(24)
				->setTitle( Tr::_('Related to parent models') );
		}

		if( $this->getIsId() ) {
			$icon .= UI::icon('magic')
				->setSize(12)
				->setWidth(24)
				->setTitle( Tr::_('Is ID') );
		}

		if( $this->getIsKey() ) {
			$icon .= UI::icon('key')
				->setSize(12)
				->setWidth(24)
				->setTitle( Tr::_('Is key') );
		}

		if( $this->isInherited() ) {

			$icon .= UI::icon('angle-double-up')
				->setSize(12)
				->setWidth(24)
				->setTitle( Tr::_('Is inherited') );


			if($this->isOverload()) {
				$icon .= UI::icon('check')
					->setSize(12)
					->setWidth(24)
					->setTitle( Tr::_('Overloaded') );
			} else {
				$icon .= UI::icon('times')
					->setSize(12)
					->setWidth(24)
					->setTitle( Tr::_('Not overloaded') );
			}
		}

		return $icon;
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
				$to_model_class_name = $this->getRelatedToClassName();
			} else {
				[$to_scope, $to_model_class_name] = explode(':', $this->getRelatedToClassName());
			}

			$related_to_model = DataModels::getClass($to_model_class_name)->getDefinition();
			if($related_to_model) {
				$related_to_property = $related_to_model->getProperty( $this->getRelatedToPropertyName() );

				$property->addAnnotation( new ClassCreator_Annotation('JetDataModel', 'related_to', var_export($to_scope.'.'.$related_to_property->getName(), true)) );
			} else {
				$class->addError('Unable to get related DataModel definition (related model:'.$to_model_class_name.')');
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

			$form_field_type = DataModel_Definition_Property::getFormFieldTypes()[$this->getFormFieldType()]['type'];

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


	/**
	 * @param DataModel_Class $class
	 *
	 * @return bool
	 */
	public function update( DataModel_Class $class )
	{
		$ok = true;
		try {
			$model = $class->getDefinition();

			$created_class = $model->createClass();

			if($created_class->getErrors()) {
				return false;
			}

			$script  = IO_File::read($class->getScriptPath());

			$parser = new ClassParser( $script );

			$parser->actualize_updateProperty(
					$class->getClassName(),
					$this->createClassProperty( $created_class )
			);

			IO_File::write(
				$class->getScriptPath(),
				$parser->toString()
			);

			Application::resetOPCache();


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
	public function add( DataModel_Class $class )
	{
		$ok = true;
		try {
			$model = $class->getDefinition();

			$created_class = $model->createClass();

			if($created_class->getErrors()) {
				return false;
			}

			$script  = IO_File::read($class->getScriptPath());

			$parser = new ClassParser( $script );

			$parser->actualize_addProperty(
				$class->getClassName(),
				$this->createClassProperty( $created_class )
			);

			IO_File::write(
				$class->getScriptPath(),
				$parser->toString()
			);

			Application::resetOPCache();


		} catch( Exception $e ) {
			$ok = false;
			Application::handleError( $e );
		}

		return $ok;
	}

}