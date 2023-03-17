<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Cache;
use Jet\Exception;
use Jet\Factory_Form;
use Jet\Form;
use Jet\Form_Definition_Field;
use Jet\Form_Definition_SubForm;
use Jet\Form_Definition_SubForms;
use Jet\Form_Field;
use Jet\Form_Field_Checkbox;
use Jet\Form_Definition_FieldOption;
use Jet\Form_Field_Float;
use Jet\Form_Field_Hidden;
use Jet\Form_Field_Input;
use Jet\Form_Field_Int;
use Jet\IO_File;
use Jet\Tr;

use ReflectionClass;
use ReflectionProperty;


class Forms_Class_Property
{
	protected Forms_Class $class;
	
	protected string $name;
	
	protected ?ReflectionProperty $reflection = null;
	
	protected null|Form_Definition_Field|Form_Definition_SubForm|Form_Definition_SubForms $field_definition = null;
	
	protected ?Form $select_type_form = null;
	
	protected ?Form $definition_form = null;
	
	/**
	 * @param Forms_Class $class
	 * @param string $name
	 * @param ReflectionProperty|null $reflection
	 * @param null|Form_Definition_Field|Form_Definition_SubForm|Form_Definition_SubForms $field_definition
	 */
	public function __construct( Forms_Class $class, string $name, ?ReflectionProperty $reflection,null|Form_Definition_Field|Form_Definition_SubForm|Form_Definition_SubForms $field_definition )
	{
		$this->class = $class;
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
	 * @return null|Form_Definition_Field|Form_Definition_SubForm|Form_Definition_SubForms
	 */
	public function getFieldDefinition(): null|Form_Definition_Field|Form_Definition_SubForm|Form_Definition_SubForms
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
	
	public static function getTypesScope() : array
	{
		$_constants = (new ReflectionClass( Form_Field::class ))->getConstants();
		$constants = [];
		foreach($_constants as $constant=>$value) {
			$constants[$value] = 'Form_Field::'.$constant;
		}
		
		$types = [];
		foreach(Factory_Form::getRegisteredFieldTypes() as $type) {
			if($type==Form_Field::TYPE_CSRF_PROTECTION) {
				continue;
			}
			
			$types[$type] = $constants[$type]??$type;
		}
		
		
		return $types;
	}
	
	public static function getTypesList() : array
	{
		$types = [
			'' => Tr::_('- none -'),
			'__sub_form__' => Tr::_('- Sub form -'),
			'__sub_forms__' => Tr::_('- Sub forms -'),
		];
		
		
		foreach(static::getTypesScope() as $type=>$label) {
			$types[$type] = $label;
		}

		return $types;
	}
	
	public static function getErrorCodesScope() : array
	{
		$_constants = (new ReflectionClass( Form_Field::class ))->getConstants();
		
		$constants = [];
		foreach($_constants as $constant=>$value) {
			if(str_starts_with($constant, 'ERROR')) {
				$constants[$value] = $constant;
			}
		}

		return $constants;
	}
	
	/**
	 * @return Form
	 */
	public function getSetTypeForm() : Form
	{
		$type_field = new Form_Field_Hidden('type');
	
		$form = new Form('select_field_type', [$type_field]);
		
		$form->setAction( Forms::getActionUrl( 'select_type') );
		
		return $form;
	}
	
	
	/**
	 * @param Form_Definition_Field $def
	 * @return Form
	 */
	public function getDefinitionForm_Field( Form_Definition_Field $def ) : Form
	{
		$fields = [];
		
		$type = $def->getType();
		
		$label_field = new Form_Field_Input('/main/label', 'Label:');
		$label_field->setDefaultValue($def->getLabel());
		$fields[] = $label_field;
		
		$is_required_field = new Form_Field_Checkbox('/main/is_required', 'Is required');
		$is_required_field->setDefaultValue($def->getIsRequired());
		$fields[] = $is_required_field;
		
		
		$help_text_field = new Form_Field_Input('/main/help_text', 'Help text:');
		$help_text_field->setDefaultValue($def->getHelpText());
		$fields[] = $help_text_field;
		
		$default_value_getter_name_field = new Form_Field_Input('/main/default_value_getter_name', 'Default value getter method name:');
		$default_value_getter_name_field->setDefaultValue( $def->getDefaultValueGetterName() );
		$fields[] = $default_value_getter_name_field;
		
		
		$setter_name_field = new Form_Field_Input('/main/setter_name', 'Setter method name:');
		$setter_name_field->setDefaultValue( $def->getSetterName( true ) );
		$fields[] = $setter_name_field;
		
		$creator_field = new Form_Field_Callable('/main/creator', 'Creator:');
		$creator_field->setClassContext( $this->class->getFullClassName() );
		$creator_field->setErrorMessages([
			Form_Field_Callable::ERROR_CODE_NOT_CALLABLE => 'Is not callable'
		]);
		$creator_field->setMethodReturnType('Form_Field');
		$creator_field->setMethodArguments('Form_Field $pre_created_field');
		$creator_field->setDefaultValue( $def->getCreator() );
		$fields[] = $creator_field;
		
		
		/**
		 * @var Form_Field $field_class
		 */
		$field_class = Factory_Form::getFieldClassName($type);
		
		$field_options = $field_class::getFieldOptionsDefinition();
		
		foreach($field_options as $field_option) {
			
			$default_value = $def->getOtherOption( $field_option->getName() );
			
			switch($field_option->getType()) {
				case Form_Definition_FieldOption::TYPE_INT:
					$spec_field = new Form_Field_Int('/other/'.$field_option->getName(), $field_option->getLabel() );
					break;
				case Form_Definition_FieldOption::TYPE_FLOAT:
					$spec_field = new Form_Field_Float('/other/'.$field_option->getName(), $field_option->getLabel() );
					break;
				case Form_Definition_FieldOption::TYPE_BOOL:
					$spec_field = new Form_Field_Checkbox('/other/'.$field_option->getName(), $field_option->getLabel() );
					break;
				case Form_Definition_FieldOption::TYPE_CALLABLE:
					
					$spec_field = new Form_Field_Callable('/other/'.$field_option->getName(), $field_option->getLabel() );
					$spec_field->setClassContext( Forms::getCurrentClassName() );
					$spec_field->setErrorMessages([
						Form_Field_Callable::ERROR_CODE_NOT_CALLABLE => 'Is not callable'
					]);
					break;
				case Form_Definition_FieldOption::TYPE_ARRAY:
					$spec_field = new Form_Field_Array('/other/'.$field_option->getName(), $field_option->getLabel() );
					break;
				case Form_Definition_FieldOption::TYPE_ASSOC_ARRAY:
					$spec_field = new Form_Field_AssocArray('/other/'.$field_option->getName(), $field_option->getLabel() );
					break;
				default:
					$spec_field = new Form_Field_Input('/other/'.$field_option->getName(), $field_option->getLabel() );
					break;
				
			}
			
			$spec_field->setDefaultValue($default_value);
			
			$fields[] = $spec_field;
			
		}
		
		$base_error_codes = static::getErrorCodesScope();
		
		
		$_field = Factory_Form::getFieldInstance( $type, '' );
		$defined_error_messages = $def->getErrorMessages();
		
		$predefined_error_codes = array_keys($_field->getErrorMessages());
		$i=0;
		
		foreach($predefined_error_codes as $error_code) {
			$i++;
			$code_field = new Form_Field_Hidden( '/error_messages/'.$i.'/code' );
			$code_field->setDefaultValue( $error_code );
			
			$message_field = new Form_Field_Input( '/error_messages/'.$i.'/message', $base_error_codes[$error_code] ?? $error_code );
			$message_field->setDefaultValue( $defined_error_messages[$error_code]??'' );
			
			if( isset($defined_error_messages[$error_code]) ) {
				unset($defined_error_messages[$error_code]);
			}
			
			$fields[] = $code_field;
			$fields[] = $message_field;
		}
		
		foreach($defined_error_messages as $error_code=>$message) {
			$i++;
			$code_field = new Form_Field_Input( '/error_messages/'.$i.'/code' );
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
		
		
		$form = new Form('definition_form', $fields);
		
		$form->setAction( Forms::getActionUrl('save_field') );
		
		return $form;
		
	}
	
	
	/**
	 * @param Form_Definition_SubForm $def
	 * @return Form
	 */
	public function getDefinitionForm_SubForm( Form_Definition_SubForm $def ) : Form
	{
		$fields = [];
		
		
		$creator_field = new Form_Field_Callable('/main/creator', 'Creator:');
		$creator_field->setClassContext( $this->class->getFullClassName() );
		$creator_field->setErrorMessages([
			Form_Field_Callable::ERROR_CODE_NOT_CALLABLE => 'Is not callable'
		]);
		$creator_field->setMethodReturnType('Form_Field[]');
		$creator_field->setMethodArguments('Form_Field[] $pre_created_fields');
		$creator_field->setDefaultValue( $def->getCreator() );
		$fields[] = $creator_field;
		
		
		$form = new Form('definition_form', $fields);
		
		$form->setAction( Forms::getActionUrl('save_sub_form') );
		
		return $form;
	}
	
	
	/**
	 * @param Form_Definition_SubForms $def
	 * @return Form
	 */
	public function getDefinitionForm_SubForms( Form_Definition_SubForms $def ) : Form
	{
		$fields = [];
		
		
		$creator_field = new Form_Field_Callable('/main/creator', 'Creator:');
		$creator_field->setClassContext( $this->class->getFullClassName() );
		$creator_field->setErrorMessages([
			Form_Field_Callable::ERROR_CODE_NOT_CALLABLE => 'Is not callable'
		]);
		$creator_field->setMethodReturnType('Form_Field[]');
		$creator_field->setMethodArguments('Form_Field[] $pre_created_fields');
		$creator_field->setDefaultValue( $def->getCreator() );
		$fields[] = $creator_field;
		
		
		$form = new Form('definition_form', $fields);
		
		$form->setAction( Forms::getActionUrl('save_sub_forms') );
		
		return $form;
	}
	
	
	/**
	 *
	 * @return Form|null
	 */
	public function getDefinitionForm() : ?Form
	{
		$def=$this->getFieldDefinition();
		
		if($def) {
			if(!$this->definition_form) {
				if($def instanceof Form_Definition_Field ) {
					$this->definition_form = $this->getDefinitionForm_Field( $def );
				}
				
				if($def instanceof Form_Definition_SubForm ) {
					$this->definition_form = $this->getDefinitionForm_SubForm( $def );
				}
				
				if($def instanceof Form_Definition_SubForms ) {
					$this->definition_form = $this->getDefinitionForm_SubForms( $def );
				}
			}
			
			return $this->definition_form;
		}
		
		
		return null;
		
	}
	
	/**
	 * @return string
	 */
	public function getTypeName() : string
	{
		$def = $this->getFieldDefinition();
		
		if($def instanceof Form_Definition_SubForm) {
			return 'Sub Form';
		}
		
		if($def instanceof Form_Definition_SubForms) {
			return 'Sub Forms';
		}
		
		$types = static::getTypesScope();
		
		
		$type = $def->getType();
		
		return $types[$type]??$type;
	}
	
	/**
	 * @param array $data
	 * @return bool
	 */
	public function update( array $data ) : bool
	{
		$ok = true;
		try {
			
			
			$new_attribute = null;
			
			if($this->field_definition instanceof Form_Definition_Field) {
				$new_attribute = $this->generateAttribute_Field($data);
			}
			
			if($this->field_definition instanceof Form_Definition_SubForm) {
				$new_attribute = $this->generateAttribute_SubForm($data);
			}
			
			if($this->field_definition instanceof Form_Definition_SubForms) {
				$new_attribute = $this->generateAttribute_SubForms($data);
			}
			
			
			$script = IO_File::read( $this->class->getScriptPath() );
			$parser = new ClassParser( $script );
			
			$parser_property = $parser->classes[$this->class->getClassName()]->properties[$this->name];
			$new_str = $parser_property->toString();
			
			
			
			$is_first = true;
			
			
			foreach( $parser_property->attributes as $attribute ) {
				if($attribute->name!='Form_Definition') {
					continue;
				}
				
				if($is_first) {
					$new_str = str_replace($attribute->toString(), trim($new_attribute->toString(1)), $new_str );
					
					$is_first = false;
				} else {
					$new_str = str_replace($attribute->toString(), '', $new_str );
				}
			}

			
			$parser_property->replace($new_str);
			
			
			$use_field = new ClassCreator_UseClass( 'Jet', 'Form_Field' );
			$use_definition = new ClassCreator_UseClass( 'Jet', 'Form_Definition' );
			
			$parser->actualize_setUse([
				$use_field,
				$use_definition
			]);
			
			IO_File::write(
				$this->class->getScriptPath(),
				$parser->toString()
			);
			
			Cache::resetOPCache();
			
		} catch( Exception $e ) {
			$ok = false;
			Application::handleError( $e );
		}
		
		return $ok;
	}
	
	protected function generateAttribute_Field( array $data, ?string $force_type=null ) : ClassCreator_Attribute
	{
		$new_attribute = new ClassCreator_Attribute('Form_Definition');
		
		$type = $force_type ? : $this->field_definition->getType();
		$type_scope = static::getTypesScope();
		
		$new_attribute->setArgument('type', $type_scope[$type]??$type );
		
		if(isset($data['main'])) {
			foreach($data['main'] as $key=>$val) {
				if($val==='' || $val===[] || $val===['','']) {
					continue;
				}
				$new_attribute->setArgument($key, $val);
			}
		}
		
		if(isset($data['other'])) {
			foreach($data['other'] as $key=>$val) {
				if($val==='' || $val===[] || $val===['','']) {
					continue;
				}
				
				$new_attribute->setArgument($key, $val);
			}
			
		}
		
		if(isset($data['error_messages'])) {
			$error_messages = [];
			$base_error_codes = static::getErrorCodesScope();
			foreach($data['error_messages'] as $err) {
				$code = $err['code'];
				$message = $err['message'];
				
				if(!$code) {
					continue;
				}
				
				if($message=='') {
					continue;
				}
				
				
				$error_messages[isset($base_error_codes[$code])?('Form_Field::'.$base_error_codes[$code]):$code] = $message;
			}
			
			$new_attribute->setArgument('error_messages', $error_messages );
		}
		
		return $new_attribute;
	}
	
	protected function generateAttribute_SubForm( array $data) : ClassCreator_Attribute
	{
		$new_attribute = new ClassCreator_Attribute('Form_Definition');
		
		$new_attribute->setArgument('is_sub_form', true );
		
		if(isset($data['main'])) {
			foreach($data['main'] as $key=>$val) {
				if($val==='' || $val===[] || $val===['','']) {
					continue;
				}
				
				$new_attribute->setArgument($key, $val);
			}
		}
		
		if(isset($data['other'])) {
			foreach($data['other'] as $key=>$val) {
				if($val==='' || $val===[] || $val===['','']) {
					continue;
				}
				
				$new_attribute->setArgument($key, $val);
			}
			
		}
		
		return $new_attribute;
	}
	
	protected function generateAttribute_SubForms( array $data) : ClassCreator_Attribute
	{
		$new_attribute = new ClassCreator_Attribute('Form_Definition');
		
		$new_attribute->setArgument('is_sub_forms', true );
		
		if(isset($data['main'])) {
			foreach($data['main'] as $key=>$val) {
				if($val==='' || $val===[] || $val===['','']) {
					continue;
				}
				
				$new_attribute->setArgument($key, $val);
			}
		}
		
		if(isset($data['other'])) {
			foreach($data['other'] as $key=>$val) {
				if($val==='' || $val===[] || $val===['','']) {
					continue;
				}
				
				$new_attribute->setArgument($key, $val);
			}
			
		}
		
		return $new_attribute;
	}
	
	
	
	/**
	 * @param array $data
	 * @return bool
	 */
	public function setType( array $data ) : bool
	{
		$ok = true;
		try {
			
			if(empty($data['type'])) {
				return true;
			}
			
			$new_attribute = match ($data['type']) {
				'__sub_form__' => $this->generateAttribute_SubForm( $data ),
				'__sub_forms__' => $this->generateAttribute_SubForms( $data ),
				default => $this->generateAttribute_Field( $data, $data['type'] ),
			};
			
			$script = IO_File::read( $this->class->getScriptPath() );
			$parser = new ClassParser( $script );
			
			$parser_property = $parser->classes[$this->class->getClassName()]->properties[$this->name];
			$new_str = $parser_property->toString();
			
			
			foreach( $parser_property->attributes as $attribute ) {
				if($attribute->name!='Form_Definition') {
					continue;
				}
				
				$new_str = str_replace($attribute->toString(), '', $new_str );
			}
			
			$parser->insertBefore(
				$parser_property->declaration_start,
				trim($new_attribute->toString(1)).ClassCreator_Class::getNl().ClassCreator_Class::getIndentation()
			);
			
			
			$use_field = new ClassCreator_UseClass( 'Jet', 'Form_Field' );
			$use_definition = new ClassCreator_UseClass( 'Jet', 'Form_Definition' );
			
			$parser->actualize_setUse([
				$use_field,
				$use_definition
			]);
			
			IO_File::write(
				$this->class->getScriptPath(),
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