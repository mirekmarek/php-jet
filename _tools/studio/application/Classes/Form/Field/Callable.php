<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Autoloader;
use Jet\Data_Array;
use Jet\Factory_Form;
use Jet\Form_Field;
use Jet\InputCatcher;
use Jet\IO_File;
use Jet\SysConf_Jet_Form_DefaultViews;

/**
 *
 */
class Form_Field_Callable extends Form_Field
{
	public const ERROR_CODE_NOT_CALLABLE = 'not_callable';
	
	protected string $class_context = '';
	
	protected string $_type = 'callable';
	
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
	
	
	public function getInputCatcher() : InputCatcher
	{
		if( !$this->_input_catcher ) {
			$this->_input_catcher = new class ( $this->getName(), $this->getDefaultValue() ) extends InputCatcher {
				public function catchInput( Data_Array $data ): void
				{
					$this->value = null;
					$name = (($this->name[0]=='/') ? $this->name : '/'.$this->name).'/';
					
					$this->value_exists_in_the_input = $data->exists( $name.'class' ) && $data->exists( $name.'method' );
					
					if( $this->value_exists_in_the_input ) {
						$this->value = [
							trim( $data->getString( $name.'class' ) ),
							trim( $data->getString( $name.'method' ) )
						];
						$this->value_raw = $this->value;
						
					} else {
						$this->value_raw = null;
						$this->value = $this->default_value;
					}
					
				}
				
				
				protected function checkValue(): void
				{
				}
			};
		}
		
		return $this->_input_catcher;
		
	}
	
	
	/**
	 * @return bool
	 */
	public function validate(): bool
	{
		$value = $this->getValue();
		
		if($this->getIsRequired()) {
			if(
				!is_array($value) ||
				count($value)!=2 ||
				!$value[0] ||
				!$value[1]
			) {
				$this->setError(Form_Field::ERROR_CODE_EMPTY);
				
				return false;
			}
		}
		
		if(
			is_array($value) &&
			!empty($value[0]) &&
			!empty($value[1])
		) {
			$test_value = $value;
			
			if(
				$this->class_context
			) {
				if($test_value[0]=='this') {
					if(!method_exists($this->class_context, $test_value[1])) {
						$this->setError( static::ERROR_CODE_NOT_CALLABLE );
						
						return false;
					}
					
					$this->setIsValid();
					
					return true;
					
				}
				
				if($test_value[0]=='self::class') {
					$test_value[0] = $this->class_context;
				}
			}
			
			if(
				!($class_path=Autoloader::getScriptPath($test_value[0])) ||
				!IO_File::exists($class_path)
			) {
				$this->setError( static::ERROR_CODE_NOT_CALLABLE );
				
				return false;
			}
			
			if(!is_callable( $test_value )) {
				$this->setError( static::ERROR_CODE_NOT_CALLABLE );
				
				return false;
			}
		}
		
		$this->setIsValid();
		
		return true;
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
	
	/**
	 * @return string
	 */
	public function getClassContext(): string
	{
		return $this->class_context;
	}
	
	/**
	 * @param string $class_context
	 */
	public function setClassContext( string $class_context ): void
	{
		$this->class_context = $class_context;
	}
	
	
	
	/**
	 * @return string
	 */
	public function getMethodArguments(): string
	{
		return $this->method_arguments;
	}
	
	/**
	 * @param string $method_arguments
	 */
	public function setMethodArguments( string $method_arguments ): void
	{
		$this->method_arguments = $method_arguments;
	}
	
	/**
	 * @return string
	 */
	public function getMethodReturnType(): string
	{
		return $this->method_return_type;
	}
	
	/**
	 * @param string $method_return_type
	 */
	public function setMethodReturnType( string $method_return_type ): void
	{
		$this->method_return_type = $method_return_type;
	}
	
	
	
}

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