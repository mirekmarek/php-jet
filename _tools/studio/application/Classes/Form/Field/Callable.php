<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Factory_Form;
use Jet\Factory_InputCatcher;
use Jet\Factory_Validator;
use Jet\Form_Field;
use Jet\SysConf_Jet_Form_DefaultViews;
use Jet\Validator;

/**
 *
 */
class Form_Field_Callable extends Form_Field
{
	public const ERROR_CODE_NOT_CALLABLE = 'not_callable';
	
	protected string $class_context = '';
	
	protected string $_type = 'callable';
	protected string $_validator_type = 'callable';
	protected string $_input_catcher_type = 'callable';
	
	protected string $method_arguments = '';
	
	protected string $method_return_type = 'void';
	
	
	public function getRequiredErrorCodes(): array
	{
		$errors = [];
		if($this->getIsRequired()) {
			$errors[] = Form_Field::ERROR_CODE_EMPTY;
		}
		
		$errors[] = static::ERROR_CODE_NOT_CALLABLE;
		
		return $errors;
	}
	
	protected function initValidator( Validator|Validator_Callable $validator ) : void
	{
		parent::initValidator( $validator );
		$validator->setClassContext( $this->class_context );
	}
	
	public function getValue_class() : string
	{
		$value = $this->getValue();
		if(!is_array($value) || count($value)!=2 ) {
			return '';
		}
		
		if(
			$this->class_context &&
			$value[0]==$this->class_context
		) {
			return 'self::class';
		}
		
		return $value[0];
	}
	
	public function getValue_method() : string
	{
		$value = $this->getValue();
		if(!is_array($value) || count($value)!=2 ) {
			return '';
		}
		return $value[1];
	}

	public function getClassContext(): string
	{
		return $this->class_context;
	}
	
	public function setClassContext( string $class_context ): void
	{
		$this->class_context = $class_context;
	}
	
	public function getMethodArguments(): string
	{
		return $this->method_arguments;
	}
	
	public function setMethodArguments( string $method_arguments ): void
	{
		$this->method_arguments = $method_arguments;
	}
	
	public function getMethodReturnType(): string
	{
		return $this->method_return_type;
	}
	
	public function setMethodReturnType( string $method_return_type ): void
	{
		$this->method_return_type = $method_return_type;
	}
	
	public static function register() : void
	{
		Factory_InputCatcher::registerNewInputCatcherType( InputCatcher_Callable::getType(), InputCatcher_Callable::class );
		Factory_Validator::registerNewValidatorType( Validator_Callable::getType(), Validator_Callable::class );
		
		Factory_Form::registerNewFieldType(
			field_type: 'callable',
			field_class_name: Form_Field_Callable::class,
			renderers: [
				'input' => Form_Renderer_Field_Input_Callable::class
			]
		);
		
		SysConf_Jet_Form_DefaultViews::registerNewFieldType('callable', [
			'input' => 'field/input/callable'
		]);
	}
	
}

Form_Field_Callable::register();