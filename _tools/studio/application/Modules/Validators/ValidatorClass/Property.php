<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\Validators;

use Jet\Cache;
use Jet\Entity_Validator_Definition_PropertyValidator;
use Jet\Entity_Validator_Definition_SubEntity_Validator;
use Jet\Entity_Validator_Definition_SubEntity_Validators;
use Jet\Entity_Validator_Interface;
use Jet\Entity_Validator_Trait;
use Jet\Exception;
use Jet\Factory_Validator;
use Jet\Form;
use Jet\Form_Field_Checkbox;
use Jet\Entity_Validator_Definition_ValidatorOption;
use Jet\Form_Field_Float;
use Jet\Form_Field_Hidden;
use Jet\Form_Field_Input;
use Jet\Form_Field_Int;
use Jet\IO_File;
use Jet\Tr;

use Jet\Validator;
use JetStudio\ClassCreator_Class_UseTrait;
use JetStudio\ClassCreator_Config;
use JetStudio\JetStudio;
use JetStudio\Form_Field_Callable;
use JetStudio\Form_Field_Array;
use JetStudio\Form_Field_AssocArray;
use JetStudio\ClassCreator_Attribute;
use JetStudio\ClassCreator_UseClass;
use JetStudio\ClassParser;

use ReflectionClass;
use ReflectionProperty;


class ValidatorClass_Property
{
	protected ValidatorClass $class;
	
	protected string $name;
	
	protected ?ReflectionProperty $reflection = null;
	
	protected null|Entity_Validator_Definition_PropertyValidator|Entity_Validator_Definition_SubEntity_Validator|Entity_Validator_Definition_SubEntity_Validators $validator_definition = null;
	
	protected ?Form $__select_type_form = null;
	
	protected ?Form $__definition_form = null;
	
	/**
	 * @param ValidatorClass $class
	 * @param string $name
	 * @param ReflectionProperty|null $reflection
	 * @param null|Entity_Validator_Definition_PropertyValidator|Entity_Validator_Definition_SubEntity_Validator|Entity_Validator_Definition_SubEntity_Validators $validator_definition
	 */
	public function __construct( ValidatorClass $class, string $name, ?ReflectionProperty $reflection, null|Entity_Validator_Definition_PropertyValidator|Entity_Validator_Definition_SubEntity_Validator|Entity_Validator_Definition_SubEntity_Validators $validator_definition )
	{
		$this->class = $class;
		$this->name = $name;
		$this->reflection = $reflection;
		$this->validator_definition = $validator_definition;
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
	 * @return null|Entity_Validator_Definition_PropertyValidator|Entity_Validator_Definition_SubEntity_Validator|Entity_Validator_Definition_SubEntity_Validators
	 */
	public function getValidatorDefinition(): null|Entity_Validator_Definition_PropertyValidator|Entity_Validator_Definition_SubEntity_Validator|Entity_Validator_Definition_SubEntity_Validators
	{
		return $this->validator_definition;
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
		$_constants = (new ReflectionClass( Validator::class ))->getConstants();
		$constants = [];
		foreach($_constants as $constant=>$value) {
			$constants[$value] = 'Validator::'.$constant;
		}
		
		$types = [];
		foreach(Factory_Validator::getRegisteredValidatorTypes() as $type) {
			$types[$type] = $constants[$type]??$type;
		}
		
		
		return $types;
	}
	
	public static function getTypesList() : array
	{
		$types = [
			'' => Tr::_('- none -'),
			'__sub_validator__' => Tr::_('- Sub validator -'),
			'__sub_validators__' => Tr::_('- Sub validators -'),
		];
		
		
		foreach(static::getTypesScope() as $type=>$label) {
			$types[$type] = $label;
		}

		return $types;
	}
	
	public static function getErrorCodesScope() : array
	{
		$_constants = (new ReflectionClass( Validator::class ))->getConstants();
		
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
		
		$form->setAction( Main::getActionUrl( 'select_type') );
		
		return $form;
	}
	
	
	/**
	 * @param Entity_Validator_Definition_PropertyValidator $def
	 * @return Form
	 */
	public function getDefinitionForm_Field( Entity_Validator_Definition_PropertyValidator $def ) : Form
	{
		$fields = [];
		
		$type = $def->getType();
		
		
		$is_required_field = new Form_Field_Checkbox('/main/is_required', 'Is required');
		$is_required_field->setDefaultValue($def->getIsRequired());
		$fields[] = $is_required_field;
		
		
		$creator_field = new Form_Field_Callable('/main/creator', 'Creator:');
		$creator_field->setClassContext( $this->class->getFullClassName() );
		$creator_field->setErrorMessages([
			Form_Field_Callable::ERROR_CODE_NOT_CALLABLE => 'Is not callable'
		]);
		$creator_field->setMethodReturnType('Validator');
		$creator_field->setMethodArguments('Validator $pre_created_validator');
		$creator_field->setDefaultValue( $def->getCreator() );
		$fields[] = $creator_field;
		
		
		/**
		 * @var Validator $validator_class
		 */
		$validator_class = Factory_Validator::getValidatorClassName($type);
		
		$validator_options = $validator_class::getValidatorOptionsDefinition();
		
		foreach($validator_options as $validator_option) {
			
			$default_value = $def->getOtherOption( $validator_option->getName() );
			
			switch($validator_option->getType()) {
				case Entity_Validator_Definition_ValidatorOption::TYPE_INT:
					$spec_field = new Form_Field_Int('/other/'.$validator_option->getName(), $validator_option->getLabel() );
					break;
				case Entity_Validator_Definition_ValidatorOption::TYPE_FLOAT:
					$spec_field = new Form_Field_Float('/other/'.$validator_option->getName(), $validator_option->getLabel() );
					break;
				case Entity_Validator_Definition_ValidatorOption::TYPE_BOOL:
					$spec_field = new Form_Field_Checkbox('/other/'.$validator_option->getName(), $validator_option->getLabel() );
					break;
				case Entity_Validator_Definition_ValidatorOption::TYPE_CALLABLE:
					
					$spec_field = new Form_Field_Callable('/other/'.$validator_option->getName(), $validator_option->getLabel() );
					$spec_field->setClassContext( Main::getCurrentClassName() );
					$spec_field->setErrorMessages([
						Form_Field_Callable::ERROR_CODE_NOT_CALLABLE => 'Is not callable'
					]);
					break;
				case Entity_Validator_Definition_ValidatorOption::TYPE_ARRAY:
					$spec_field = new Form_Field_Array('/other/'.$validator_option->getName(), $validator_option->getLabel() );
					break;
				case Entity_Validator_Definition_ValidatorOption::TYPE_ASSOC_ARRAY:
					$spec_field = new Form_Field_AssocArray('/other/'.$validator_option->getName(), $validator_option->getLabel() );
					break;
				default:
					$spec_field = new Form_Field_Input('/other/'.$validator_option->getName(), $validator_option->getLabel() );
					break;
				
			}
			
			$spec_field->setDefaultValue($default_value);
			
			$fields[] = $spec_field;
			
		}
		
		$base_error_codes = static::getErrorCodesScope();
		
		
		$_validator = Factory_Validator::getValidatorInstance( $type );
		$defined_error_messages = $def->getErrorMessages();
		
		$predefined_error_codes = array_keys($_validator->getErrorMessages());
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
		
		$form->setAction( Main::getActionUrl('save_validator') );
		
		return $form;
		
	}
	
	
	/**
	 * @param Entity_Validator_Definition_SubEntity_Validator $def
	 * @return Form
	 */
	public function getDefinitionForm_SubForm( Entity_Validator_Definition_SubEntity_Validator $def ) : Form
	{
		$fields = [];
		
		
		$creator_field = new Form_Field_Callable('/main/creator', 'Creator:');
		$creator_field->setClassContext( $this->class->getFullClassName() );
		$creator_field->setErrorMessages([
			Form_Field_Callable::ERROR_CODE_NOT_CALLABLE => 'Is not callable'
		]);
		$creator_field->setMethodReturnType('Validator[]');
		$creator_field->setMethodArguments('Validator[] $pre_created_validators');
		$creator_field->setDefaultValue( $def->getCreator() );
		$fields[] = $creator_field;
		
		
		$form = new Form('definition_form', $fields);
		
		$form->setAction( Main::getActionUrl('save_sub_validator') );
		
		return $form;
	}
	
	
	/**
	 * @param Entity_Validator_Definition_SubEntity_Validators $def
	 * @return Form
	 */
	public function getDefinitionForm_SubForms( Entity_Validator_Definition_SubEntity_Validators $def ) : Form
	{
		$fields = [];
		
		
		$creator_field = new Form_Field_Callable('/main/creator', 'Creator:');
		$creator_field->setClassContext( $this->class->getFullClassName() );
		$creator_field->setErrorMessages([
			Form_Field_Callable::ERROR_CODE_NOT_CALLABLE => 'Is not callable'
		]);
		$creator_field->setMethodReturnType('Validator[]');
		$creator_field->setMethodArguments('Validator[] $pre_created_validators');
		$creator_field->setDefaultValue( $def->getCreator() );
		$fields[] = $creator_field;
		
		
		$form = new Form('definition_form', $fields);
		
		$form->setAction( Main::getActionUrl('save_sub_validators') );
		
		return $form;
	}
	
	
	/**
	 *
	 * @return Form|null
	 */
	public function getDefinitionForm() : ?Form
	{
		$def=$this->getValidatorDefinition();
		
		if($def) {
			if(!$this->__definition_form) {
				if($def instanceof Entity_Validator_Definition_PropertyValidator ) {
					$this->__definition_form = $this->getDefinitionForm_Field( $def );
				}
				
				if($def instanceof Entity_Validator_Definition_SubEntity_Validator ) {
					$this->__definition_form = $this->getDefinitionForm_SubForm( $def );
				}
				
				if($def instanceof Entity_Validator_Definition_SubEntity_Validators ) {
					$this->__definition_form = $this->getDefinitionForm_SubForms( $def );
				}
			}
			
			return $this->__definition_form;
		}
		
		
		return null;
		
	}
	
	/**
	 * @return string
	 */
	public function getTypeName() : string
	{
		$def = $this->getValidatorDefinition();
		if(!$def) {
			return Tr::_( 'Not defined' );
		}
		
		if($def instanceof Entity_Validator_Definition_SubEntity_Validator) {
			return 'Sub Form';
		}
		
		if($def instanceof Entity_Validator_Definition_SubEntity_Validators) {
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
			
			if($this->validator_definition instanceof Entity_Validator_Definition_PropertyValidator) {
				$new_attribute = $this->generateAttribute_Validator($data);
			}
			
			if($this->validator_definition instanceof Entity_Validator_Definition_SubEntity_Validator) {
				$new_attribute = $this->generateAttribute_SubValidator($data);
			}
			
			if($this->validator_definition instanceof Entity_Validator_Definition_SubEntity_Validators) {
				$new_attribute = $this->generateAttribute_SubValidators($data);
			}
			
			
			
			$script = IO_File::read( $this->class->getScriptPath() );
			$parser = new ClassParser( $script );
			
			$parser_property = $parser->classes[$this->class->getClassName()]->properties[$this->name];
			$new_str = $parser_property->toString();
			
			
			
			$is_first = true;
			
			
			foreach( $parser_property->attributes as $attribute ) {
				if($attribute->name!='Entity_Validator_Definition') {
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
			
			
			$use_field = new ClassCreator_UseClass( 'Jet', 'Validator' );
			$use_definition = new ClassCreator_UseClass( 'Jet', 'Entity_Validator_Definition' );
			
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
			JetStudio::handleError( $e );
		}
		
		return $ok;
	}
	
	protected function generateAttribute_Validator( array $data, ?string $force_type=null ) : ClassCreator_Attribute
	{
		$new_attribute = new ClassCreator_Attribute('Entity_Validator_Definition');
		
		$type = $force_type ? : $this->validator_definition->getType();
		$type_scope = static::getTypesScope();
		
		$new_attribute->setArgument('type', $type_scope[$type]??$type );
		
		if(isset($data['main'])) {
			foreach($data['main'] as $key=>$val) {
				if($val==='' || $val===[] || $val===['',''] || $val===null) {
					continue;
				}
				$new_attribute->setArgument($key, $val);
			}
		}
		
		if(isset($data['other'])) {
			foreach($data['other'] as $key=>$val) {
				if($val==='' || $val===[] || $val===['',''] || $val===null) {
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
				
				
				$error_messages[isset($base_error_codes[$code])?('Validator::'.$base_error_codes[$code]):$code] = $message;
			}
			
			$new_attribute->setArgument('error_messages', $error_messages );
		}
		
		return $new_attribute;
	}
	
	protected function generateAttribute_SubValidator( array $data) : ClassCreator_Attribute
	{
		$new_attribute = new ClassCreator_Attribute('Entity_Validator_Definition');
		
		$new_attribute->setArgument('is_sub_validator', true );
		
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
	
	protected function generateAttribute_SubValidators( array $data) : ClassCreator_Attribute
	{
		$new_attribute = new ClassCreator_Attribute('Entity_Validator_Definition');
		
		$new_attribute->setArgument('is_sub_validators', true );
		
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
				'__sub_validator__' => $this->generateAttribute_SubValidator( $data ),
				'__sub_validators__' => $this->generateAttribute_SubValidators( $data ),
				default => $this->generateAttribute_Validator( $data, $data['type'] ),
			};
			
			$script = IO_File::read( $this->class->getScriptPath() );
			$parser = new ClassParser( $script );
			$parser_class = $parser->classes[$this->class->getClassName()];
			
			$parser_property = $parser_class->properties[$this->name];
			$new_str = $parser_property->toString();
			
			
			foreach( $parser_property->attributes as $attribute ) {
				if($attribute->name!='Entity_Validator_Definition') {
					continue;
				}
				
				$new_str = str_replace($attribute->toString(), '', $new_str );
			}
			
			$parser->insertBefore(
				$parser_property->declaration_start,
				trim($new_attribute->toString(1)).ClassCreator_Config::getNl().ClassCreator_Config::getIndentation()
			);
			$parser_class = $parser->classes[$this->class->getClassName()];
			
			if(!in_array(Entity_Validator_Trait::class, $this->class->getUseTraits())) {
				$trait = new ClassCreator_Class_UseTrait('Entity_Validator_Trait');
				$parser_class->addUseTrait( $trait->toString() );
			}
			
			$parser_class = $parser->classes[$this->class->getClassName()];
			if(!in_array(Entity_Validator_Interface::class, $this->class->getImplements())) {
				$parser_class->addImplementsInterface( 'Entity_Validator_Interface' );
			}
			
			
			$use_field = new ClassCreator_UseClass( 'Jet', 'Validator' );
			$use_definition = new ClassCreator_UseClass( 'Jet', 'Entity_Validator_Definition' );
			$use_interface = new ClassCreator_UseClass( 'Jet', 'Entity_Validator_Interface' );
			$use_trait = new ClassCreator_UseClass( 'Jet', 'Entity_Validator_Trait' );
			
			$parser->actualize_setUse([
				$use_field,
				$use_definition,
				$use_interface,
				$use_trait
			]);
			
			
			
			
			
			
			IO_File::write(
				$this->class->getScriptPath(),
				$parser->toString()
			);
			
			Cache::resetOPCache();
			
		} catch( Exception $e ) {
			$ok = false;
			JetStudio::handleError( $e );
		}
		
		return $ok;
	}
	
}