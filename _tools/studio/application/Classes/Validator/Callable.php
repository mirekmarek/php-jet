<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Autoloader;
use Jet\Form_Field;
use Jet\IO_File;
use Jet\Validator;

class Validator_Callable extends Validator {
	
	public const ERROR_CODE_NOT_CALLABLE = 'not_callable';
	
	protected static string $type = 'callable';
	
	protected string $class_context = '';
	
	public function getClassContext(): string
	{
		return $this->class_context;
	}
	
	public function setClassContext( string $class_context ): void
	{
		$this->class_context = $class_context;
	}

	
	public function validate_required( mixed $value ) : bool
	{
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
		
		return true;
	}
	
	
	public function validate_value( mixed $value ): bool
	{
		
		if(
			is_array($value) &&
			array_key_exists( 0, $value ) &&
			array_key_exists( 1, $value )
		) {
			$test_value = $value;
			
			if(!$test_value[0] && !$test_value[1]) {
				return true;
			}
			
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
	
	public function getErrorCodeScope(): array
	{
		return [
			static::ERROR_CODE_NOT_CALLABLE
		];
	}
}