<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Factory_Form;
use Jet\Form;
use Jet\Form_Definition_Field;
use Jet\Form_Field;
use Jet\Form_Field_Checkbox;
use Jet\Form_Definition_FieldOption;
use Jet\Form_Field_Float;
use Jet\Form_Field_Hidden;
use Jet\Form_Field_Input;
use Jet\Form_Field_Int;
use Jet\Form_Field_Select;
use Jet\Tr;

use ReflectionClass;
use ReflectionProperty;


class Forms_Class_Property
{
	protected string $name = '';
	
	protected ?ReflectionProperty $reflection = null;
	
	protected ?Form_Definition_Field $field_definition = null;
	
	protected ?Form $select_type_form = null;
	
	/**
	 * @param string $name
	 * @param ReflectionProperty|null $reflection
	 * @param Form_Definition_Field|null $field_definition
	 */
	public function __construct( string $name, ?ReflectionProperty $reflection, ?Form_Definition_Field $field_definition )
	{
		$this->name = $name;
		$this->reflection = $reflection;
		$this->field_definition = $field_definition;
	}
	
	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}
	
	/**
	 * @return ReflectionProperty|null
	 */
	public function getReflection(): ?ReflectionProperty
	{
		return $this->reflection;
	}
	
	/**
	 * @return Form_Definition_Field|null
	 */
	public function getFieldDefinition(): ?Form_Definition_Field
	{
		return $this->field_definition;
	}
	
	/**
	 * @return string
	 */
	public function getIcons() : string
	{
		return '';
	}
	
	/**
	 * @return Form
	 */
	public function getSelectTypeForm() : Form
	{
		if(!$this->select_type_form) {
			
			$types = [
				'' => Tr::_('- none -')
			];
			
			$_constants = (new ReflectionClass( Form_Field::class ))->getConstants();
			
			$constants = [];
			foreach($_constants as $constant=>$value) {
				
				$constants[$value] = 'Form_Field::'.$constant;
			}
			
			foreach(Factory_Form::getRegisteredFieldTypes() as $type) {
				$types[$type] = $constants[$type]??$type;
			}
			
			$type_field = new Form_Field_Select('type', 'Field type:');
			$type_field->setErrorMessages([
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select field type'
			]);
			$type_field->setSelectOptions( $types );

			if(($def=$this->getFieldDefinition())) {
				$type_field->setDefaultValue( $def->getType() );
			}
			
			$this->select_type_form = new Form('select_field_type', [$type_field]);
		}
		
		return $this->select_type_form;
	}
	
	/**
	 * @param string|null $force_type
	 *
	 * @return Form|null
	 */
	public function getDefinitionForm( ?string $force_type=null ) : ?Form
	{
		$def=$this->getFieldDefinition();
		
		if($force_type===null) {
			$type = $def?$def->getType() : '';
		} else {
			$type = $force_type;
		}
		
		$fields = [];
		if($type) {
		
			
			$label_field = new Form_Field_Input('/main/label', 'Label:');
			if($def) {
				$label_field->setDefaultValue($def->getLabel());
			}
			$fields[] = $label_field;
			
			$is_required_field = new Form_Field_Checkbox('/main/is_required', 'Is required');
			if($def) {
				$is_required_field->setDefaultValue($def->getIsRequired());
			}
			$fields[] = $is_required_field;
			
			
			$help_text_field = new Form_Field_Input('/main/help_text', 'Help text:');
			if($def) {
				$help_text_field->setDefaultValue($def->getHelpText());
			}
			$fields[] = $help_text_field;
			
			$setter_name_field = new Form_Field_Input('/main/setter_name', 'Setter method name:');
			if($def) {
				$setter_name_field->setDefaultValue( $def->getSetterName( true ) );
			}
			$fields[] = $setter_name_field;
		
		

			/**
			 * @var Form_Field $field_class
			 */
			$field_class = Factory_Form::getFieldClassName($type);
			
			$field_options = $field_class::getFieldOptionsDefinition();
			
			foreach($field_options as $field_option) {

				$default_value = null;
				if($def && $def->getType()==$type) {
					$default_value = $def->getOtherOption( $field_option->getName() );
				}
				
				switch($field_option->getType()) {
					case Form_Definition_FieldOption::TYPE_STRING:
						$spec_field = new Form_Field_Input('/other/'.$field_option->getName(), $field_option->getLabel() );
						if($default_value!==null) {
							$spec_field->setDefaultValue($default_value);
						}
						
						$fields[] = $spec_field;
						break;
					case Form_Definition_FieldOption::TYPE_INT:
						$spec_field = new Form_Field_Int('/other/'.$field_option->getName(), $field_option->getLabel() );
						if($default_value!==null) {
							$spec_field->setDefaultValue($default_value);
						}
						
						$fields[] = $spec_field;
						break;
					case Form_Definition_FieldOption::TYPE_FLOAT:
						$spec_field = new Form_Field_Float('/other/'.$field_option->getName(), $field_option->getLabel() );
						if($default_value!==null) {
							$spec_field->setDefaultValue($default_value);
						}
						
						$fields[] = $spec_field;
						break;
					case Form_Definition_FieldOption::TYPE_BOOL:
						$spec_field = new Form_Field_Checkbox('/other/'.$field_option->getName(), $field_option->getLabel() );
						if($default_value!==null) {
							$spec_field->setDefaultValue($default_value);
						}
						
						$fields[] = $spec_field;
						break;
					case Form_Definition_FieldOption::TYPE_CALLABLE:
						//TODO:
						break;
					case Form_Definition_FieldOption::TYPE_ARRAY:
						//TODO:
						break;
					case Form_Definition_FieldOption::TYPE_ASSOC_ARRAY:
						//TODO:
						break;
						
				}
				
				/*
				*/
				
			}
			
			$_constants = (new ReflectionClass( Form_Field::class ))->getConstants();
			$_field = Factory_Form::getFieldInstance( $type, '', '' );
			
			$constants = [];
			foreach($_constants as $constant=>$value) {
				
				$constants[$value] = $constant;
			}
			$defined_error_messages = $def ? $def->getErrorMessages() : [];
			
			$predefined_error_codes = array_keys($_field->getErrorMessages());
			$i=0;
			
			foreach($predefined_error_codes as $error_code) {
				$i++;
				$code_field = new Form_Field_Hidden( '/error_messages/'.$i.'/code' );
				$code_field->setDefaultValue( $error_code );
				
				$message_field = new Form_Field_Input( '/error_messages/'.$i.'/message', $constants[$error_code] ?? $error_code );
				$message_field->setDefaultValue( $defined_error_messages[$error_code]??'' );
				
				if( isset($defined_error_messages[$error_code]) ) {
					unset($defined_error_messages[$error_code]);
				}
				
				$fields[] = $code_field;
				$fields[] = $message_field;
			}
			
			foreach($defined_error_messages as $error_code=>$message) {
				$i++;
				$code_field = new Form_Field_Hidden( '/error_messages/'.$i.'/code' );
				$code_field->setDefaultValue( $error_code );
				
				
				$message_field = new Form_Field_Input( '/error_messages/'.$i.'/message', $error_code );
				$message_field->setDefaultValue( $message );
				
				$fields[] = $code_field;
				$fields[] = $message_field;
			}

			for($c=0;$c<3;$c++) {
				$i++;
				$code_field = new Form_Field_Input( '/error_messages/'.$i.'/code' );
				$message_field = new Form_Field_Input( '/error_messages/'.$i.'/message' );
				
				$fields[] = $code_field;
				$fields[] = $message_field;
			}
			
		} else {
			//TODO:
		}
		
		/*
		//TODO: custom properties
		//TODO: $error_messages = [];
		*/
		
		
		
		
		
		$form = new Form('definition_form', $fields);
		
		$form->setAction( Forms::getActionUrl('save').'&type='.$type );
		
		return $form;
	}
}