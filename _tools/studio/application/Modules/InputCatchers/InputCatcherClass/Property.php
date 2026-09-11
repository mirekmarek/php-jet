<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\InputCatchers;

use Jet\Cache;
use Jet\Entity_InputCatcher_Definition_PropertyInputCatcher;
use Jet\Entity_InputCatcher_Definition_SubEntity_InputCatcher;
use Jet\Entity_InputCatcher_Definition_SubEntity_InputCatchers;
use Jet\Entity_InputCatcher_Interface;
use Jet\Entity_InputCatcher_Trait;
use Jet\Exception;
use Jet\Factory_InputCatcher;
use Jet\Form;
use Jet\Form_Field_Checkbox;
use Jet\Entity_InputCatcher_Definition_InputCatcherOption;
use Jet\Form_Field_Float;
use Jet\Form_Field_Hidden;
use Jet\Form_Field_Input;
use Jet\Form_Field_Int;
use Jet\InputCatcher;
use Jet\IO_File;
use Jet\Tr;

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


class InputCatcherClass_Property
{
	protected InputCatcherClass $class;
	
	protected string $name;
	
	protected ?ReflectionProperty $reflection = null;
	
	protected null|Entity_InputCatcher_Definition_PropertyInputCatcher|Entity_InputCatcher_Definition_SubEntity_InputCatcher|Entity_InputCatcher_Definition_SubEntity_InputCatchers $input_catcher_definition = null;
	
	protected ?Form $__select_type_form = null;
	
	protected ?Form $__definition_form = null;
	
	/**
	 * @param InputCatcherClass $class
	 * @param string $name
	 * @param ReflectionProperty|null $reflection
	 * @param null|Entity_InputCatcher_Definition_PropertyInputCatcher|Entity_InputCatcher_Definition_SubEntity_InputCatcher|Entity_InputCatcher_Definition_SubEntity_InputCatchers $input_catcher_definition
	 */
	public function __construct( InputCatcherClass $class, string $name, ?ReflectionProperty $reflection, null|Entity_InputCatcher_Definition_PropertyInputCatcher|Entity_InputCatcher_Definition_SubEntity_InputCatcher|Entity_InputCatcher_Definition_SubEntity_InputCatchers $input_catcher_definition )
	{
		$this->class = $class;
		$this->name = $name;
		$this->reflection = $reflection;
		$this->input_catcher_definition = $input_catcher_definition;
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
	 * @return null|Entity_InputCatcher_Definition_PropertyInputCatcher|Entity_InputCatcher_Definition_SubEntity_InputCatcher|Entity_InputCatcher_Definition_SubEntity_InputCatchers
	 */
	public function getInputCatcherDefinition(): null|Entity_InputCatcher_Definition_PropertyInputCatcher|Entity_InputCatcher_Definition_SubEntity_InputCatcher|Entity_InputCatcher_Definition_SubEntity_InputCatchers
	{
		return $this->input_catcher_definition;
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
		$_constants = (new ReflectionClass( InputCatcher::class ))->getConstants();
		$constants = [];
		foreach($_constants as $constant=>$value) {
			$constants[$value] = 'InputCatcher::'.$constant;
		}
		
		$types = [];
		foreach(Factory_InputCatcher::getRegisteredInputCatcherTypes() as $type) {
			$types[$type] = $constants[$type]??$type;
		}
		
		
		return $types;
	}
	
	public static function getTypesList() : array
	{
		$types = [
			'' => Tr::_('- none -'),
			'__sub_input_catcher__' => Tr::_('- Sub Input Catchers -'),
			'__sub_input_catchers__' => Tr::_('- Sub Input Catchers -'),
		];
		
		
		foreach(static::getTypesScope() as $type=>$label) {
			$types[$type] = $label;
		}

		return $types;
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
	 * @param Entity_InputCatcher_Definition_PropertyInputCatcher $def
	 * @return Form
	 */
	public function getDefinitionForm_Field( Entity_InputCatcher_Definition_PropertyInputCatcher $def ) : Form
	{
		$fields = [];
		
		$type = $def->getType();
		
		
		
		$creator_field = new Form_Field_Callable('/main/creator', 'Creator:');
		$creator_field->setClassContext( $this->class->getFullClassName() );
		$creator_field->setErrorMessages([
			Form_Field_Callable::ERROR_CODE_NOT_CALLABLE => 'Is not callable'
		]);
		$creator_field->setMethodReturnType('InputCatcher');
		$creator_field->setMethodArguments('InputCatcher $pre_created_input_catcher');
		$creator_field->setDefaultValue( $def->getCreator() );
		$fields[] = $creator_field;
		
		
		/**
		 * @var InputCatcher $input_catcher_class
		 */
		$input_catcher_class = Factory_InputCatcher::getInputCatcherClassName($type);
		
		$input_catcher_options = $input_catcher_class::getInputCatcherOptionsDefinition();
		
		foreach($input_catcher_options as $input_catcher_option) {
			
			$default_value = $def->getOtherOption( $input_catcher_option->getName() );
			
			switch($input_catcher_option->getType()) {
				case Entity_InputCatcher_Definition_InputCatcherOption::TYPE_INT:
					$spec_field = new Form_Field_Int('/other/'.$input_catcher_option->getName(), $input_catcher_option->getLabel() );
					break;
				case Entity_InputCatcher_Definition_InputCatcherOption::TYPE_FLOAT:
					$spec_field = new Form_Field_Float('/other/'.$input_catcher_option->getName(), $input_catcher_option->getLabel() );
					break;
				case Entity_InputCatcher_Definition_InputCatcherOption::TYPE_BOOL:
					$spec_field = new Form_Field_Checkbox('/other/'.$input_catcher_option->getName(), $input_catcher_option->getLabel() );
					break;
				case Entity_InputCatcher_Definition_InputCatcherOption::TYPE_CALLABLE:
					
					$spec_field = new Form_Field_Callable('/other/'.$input_catcher_option->getName(), $input_catcher_option->getLabel() );
					$spec_field->setClassContext( Main::getCurrentClassName() );
					$spec_field->setErrorMessages([
						Form_Field_Callable::ERROR_CODE_NOT_CALLABLE => 'Is not callable'
					]);
					break;
				case Entity_InputCatcher_Definition_InputCatcherOption::TYPE_ARRAY:
					$spec_field = new Form_Field_Array('/other/'.$input_catcher_option->getName(), $input_catcher_option->getLabel() );
					break;
				case Entity_InputCatcher_Definition_InputCatcherOption::TYPE_ASSOC_ARRAY:
					$spec_field = new Form_Field_AssocArray('/other/'.$input_catcher_option->getName(), $input_catcher_option->getLabel() );
					break;
				default:
					$spec_field = new Form_Field_Input('/other/'.$input_catcher_option->getName(), $input_catcher_option->getLabel() );
					break;
				
			}
			
			$spec_field->setDefaultValue($default_value);
			
			$fields[] = $spec_field;
			
		}
		
		
		$form = new Form('definition_form', $fields);
		
		$form->setAction( Main::getActionUrl('save_input_catcher') );
		
		return $form;
		
	}
	
	
	/**
	 * @param Entity_InputCatcher_Definition_SubEntity_InputCatcher $def
	 * @return Form
	 */
	public function getDefinitionForm_SubForm( Entity_InputCatcher_Definition_SubEntity_InputCatcher $def ) : Form
	{
		$fields = [];
		
		
		$creator_field = new Form_Field_Callable('/main/creator', 'Creator:');
		$creator_field->setClassContext( $this->class->getFullClassName() );
		$creator_field->setErrorMessages([
			Form_Field_Callable::ERROR_CODE_NOT_CALLABLE => 'Is not callable'
		]);
		$creator_field->setMethodReturnType('InputCatcher[]');
		$creator_field->setMethodArguments('InputCatcher[] $pre_created_input_catchers');
		$creator_field->setDefaultValue( $def->getCreator() );
		$fields[] = $creator_field;
		
		
		$form = new Form('definition_form', $fields);
		
		$form->setAction( Main::getActionUrl('save_sub_input_catcher') );
		
		return $form;
	}
	
	
	/**
	 * @param Entity_InputCatcher_Definition_SubEntity_InputCatchers $def
	 * @return Form
	 */
	public function getDefinitionForm_SubForms( Entity_InputCatcher_Definition_SubEntity_InputCatchers $def ) : Form
	{
		$fields = [];
		
		
		$creator_field = new Form_Field_Callable('/main/creator', 'Creator:');
		$creator_field->setClassContext( $this->class->getFullClassName() );
		$creator_field->setErrorMessages([
			Form_Field_Callable::ERROR_CODE_NOT_CALLABLE => 'Is not callable'
		]);
		$creator_field->setMethodReturnType('InputCatcher[]');
		$creator_field->setMethodArguments('InputCatcher[] $pre_created_input_catchers');
		$creator_field->setDefaultValue( $def->getCreator() );
		$fields[] = $creator_field;
		
		
		$form = new Form('definition_form', $fields);
		
		$form->setAction( Main::getActionUrl('save_sub_input_catchers') );
		
		return $form;
	}
	
	
	/**
	 *
	 * @return Form|null
	 */
	public function getDefinitionForm() : ?Form
	{
		$def=$this->getInputCatcherDefinition();
		
		if($def) {
			if(!$this->__definition_form) {
				if($def instanceof Entity_InputCatcher_Definition_PropertyInputCatcher ) {
					$this->__definition_form = $this->getDefinitionForm_Field( $def );
				}
				
				if($def instanceof Entity_InputCatcher_Definition_SubEntity_InputCatcher ) {
					$this->__definition_form = $this->getDefinitionForm_SubForm( $def );
				}
				
				if($def instanceof Entity_InputCatcher_Definition_SubEntity_InputCatchers ) {
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
		$def = $this->getInputCatcherDefinition();
		if(!$def) {
			return Tr::_( 'Not defined' );
		}
		
		if($def instanceof Entity_InputCatcher_Definition_SubEntity_InputCatcher) {
			return 'Sub Form';
		}
		
		if($def instanceof Entity_InputCatcher_Definition_SubEntity_InputCatchers) {
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
			
			if($this->input_catcher_definition instanceof Entity_InputCatcher_Definition_PropertyInputCatcher) {
				$new_attribute = $this->generateAttribute_InputCathcer($data);
			}
			
			if($this->input_catcher_definition instanceof Entity_InputCatcher_Definition_SubEntity_InputCatcher) {
				$new_attribute = $this->generateAttribute_SubInputCathcer($data);
			}
			
			if($this->input_catcher_definition instanceof Entity_InputCatcher_Definition_SubEntity_InputCatchers) {
				$new_attribute = $this->generateAttribute_SubInputCathcers($data);
			}
			
			
			
			$script = IO_File::read( $this->class->getScriptPath() );
			$parser = new ClassParser( $script );
			
			$parser_property = $parser->classes[$this->class->getClassName()]->properties[$this->name];
			$new_str = $parser_property->toString();
			
			
			
			$is_first = true;
			
			
			foreach( $parser_property->attributes as $attribute ) {
				if($attribute->name!='Entity_InputCatcher_Definition') {
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
			
			
			$use_field = new ClassCreator_UseClass( 'Jet', 'InputCatcher' );
			$use_definition = new ClassCreator_UseClass( 'Jet', 'Entity_InputCatcher_Definition' );
			
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
	
	protected function generateAttribute_InputCathcer( array $data, ?string $force_type=null ) : ClassCreator_Attribute
	{
		$new_attribute = new ClassCreator_Attribute('Entity_InputCatcher_Definition');
		
		$type = $force_type ? : $this->input_catcher_definition->getType();
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
		
		return $new_attribute;
	}
	
	protected function generateAttribute_SubInputCathcer( array $data) : ClassCreator_Attribute
	{
		$new_attribute = new ClassCreator_Attribute('Entity_InputCatcher_Definition');
		
		$new_attribute->setArgument('is_sub_input_catcher', true );
		
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
	
	protected function generateAttribute_SubInputCathcers( array $data) : ClassCreator_Attribute
	{
		$new_attribute = new ClassCreator_Attribute('Entity_InputCatcher_Definition');
		
		$new_attribute->setArgument('is_sub_input_catchers', true );
		
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
				'__sub_input_catcher__' => $this->generateAttribute_SubInputCathcer( $data ),
				'__sub_input_catchers__' => $this->generateAttribute_SubInputCathcers( $data ),
				default => $this->generateAttribute_InputCathcer( $data, $data['type'] ),
			};
			
			$script = IO_File::read( $this->class->getScriptPath() );
			$parser = new ClassParser( $script );
			$parser_class = $parser->classes[$this->class->getClassName()];
			
			$parser_property = $parser_class->properties[$this->name];
			$new_str = $parser_property->toString();
			
			
			foreach( $parser_property->attributes as $attribute ) {
				if($attribute->name!='Entity_InputCatcher_Definition') {
					continue;
				}
				
				$new_str = str_replace($attribute->toString(), '', $new_str );
			}
			
			$parser->insertBefore(
				$parser_property->declaration_start,
				trim($new_attribute->toString(1)).ClassCreator_Config::getNl().ClassCreator_Config::getIndentation()
			);
			$parser_class = $parser->classes[$this->class->getClassName()];
			
			if(!in_array(Entity_InputCatcher_Trait::class, $this->class->getUseTraits())) {
				$trait = new ClassCreator_Class_UseTrait('Entity_InputCatcher_Trait');
				$parser_class->addUseTrait( $trait->toString() );
			}
			
			$parser_class = $parser->classes[$this->class->getClassName()];
			if(!in_array(Entity_InputCatcher_Interface::class, $this->class->getImplements())) {
				$parser_class->addImplementsInterface( 'Entity_InputCatcher_Interface' );
			}
			
			
			$use_field = new ClassCreator_UseClass( 'Jet', 'InputCatcher' );
			$use_definition = new ClassCreator_UseClass( 'Jet', 'Entity_InputCatcher_Definition' );
			$use_interface = new ClassCreator_UseClass( 'Jet', 'Entity_InputCatcher_Interface' );
			$use_trait = new ClassCreator_UseClass( 'Jet', 'Entity_InputCatcher_Trait' );
			
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