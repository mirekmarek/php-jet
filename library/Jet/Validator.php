<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use ReflectionClass;

abstract class Validator extends BaseObject {
	
	public const TYPE_REGEXP = 'RegExp';
	public const TYPE_INT = 'Int';
	public const TYPE_FLOAT = 'Float';
	public const TYPE_DATE = 'Date';
	public const TYPE_DATE_TIME = 'DateTime';
	public const TYPE_MONTH = 'Month';
	public const TYPE_WEEK = 'Week';
	public const TYPE_TIME = 'Time';
	public const TYPE_EMAIL = 'Email';
	public const TYPE_TEL = 'Tel';
	public const TYPE_URL = 'Url';
	public const TYPE_SEARCH = 'Search';
	public const TYPE_COLOR = 'Color';
	public const TYPE_OPTION = 'Option';
	public const TYPE_OPTIONS = 'Options';
	public const TYPE_PASSWORD = 'Password';
	public const TYPE_FILE = 'File';
	public const TYPE_FILE_IMAGE = 'FileImage';
	public const TYPE_NULL = 'Null';
	
	public const ERROR_CODE_EMPTY = 'empty';
	
	protected ?Validator_ErrorMessageGenerator $error_message_generator = null;
	
	protected static string $type;
	protected bool $is_valid = false;
	
	protected bool $is_required = false;
	
	/**
	 * @var array<Validator_ValidationError>
	 */
	protected array $errors = [];
	protected string $last_error_code = '';
	protected string $last_error_message = '';
	
	/**
	 * @var array<string,mixed>
	 */
	protected array $last_error_data = [];
	
	/**
	 * @var array<string,string>
	 */
	protected array $error_messages = [];
	
	/**
	 * @var Entity_Validator_Definition_ValidatorOption[][]
	 */
	protected static array $validator_options_definition = [];
	
	
	public static function getType(): string
	{
		return self::$type;
	}
	
	/**
	 *
	 * @return array<string,string>
	 */
	public function getErrorMessages(): array
	{
		return $this->error_messages;
	}
	
	/**
	 *
	 * @param array<string,string> $error_messages
	 *
	 */
	public function setErrorMessages( array $error_messages ): void
	{
		foreach( $error_messages as $key => $message ) {
			$this->error_messages[$key] = $message;
		}
	}
	
	
	public function getErrorMessageGenerator(): Validator_ErrorMessageGenerator
	{
		if(!$this->error_message_generator) {
			$this->setErrorMessageGenerator( new class extends Validator_ErrorMessageGenerator {
				
				public function generateErrorMessage( string $error_code, array $error_data ): string
				{
					$error_message = $this->validator->getErrorMessages()[$error_code]??$error_code;
					
					return Tr::_($error_message, $error_data);
				}
			} );
		}
		return $this->error_message_generator;
	}
	
	public function setErrorMessageGenerator( ?Validator_ErrorMessageGenerator $error_message_generator ): void
	{
		$error_message_generator->setValidator( $this );
		$this->error_message_generator = $error_message_generator;
	}
	
	/**
	 * @param string $code
	 * @param array<string,mixed> $data
	 * @return string
	 */
	public function generateErrorMessage( string $code, array $data ) : string
	{
		return $this->getErrorMessageGenerator()->generateErrorMessage( $code, $data )??'';
	}
	
	
	/**
	 * @param string $code
	 * @param array<string,mixed> $data
	 */
	public function setError( string $code, array $data = [] ): void
	{
		$this->is_valid = false;
		
		$message = $this->generateErrorMessage( $code, $data );
		
		$this->errors[] = new Validator_ValidationError($this, $code, $message, $data);
		
		$this->last_error_code = $code;
		$this->last_error_message = $message;
		$this->last_error_data = $data;
	}
	
	/**
	 * @return array<Validator_ValidationError>
	 */
	public function getAllErrors() : array
	{
		return $this->errors;
	}
	

	public function getLastErrorCode(): string
	{
		return $this->last_error_code;
	}
	
	public function getLastErrorMessage(): string
	{
		return $this->last_error_message;
	}
	
	/**
	 * @return array<string,mixed>
	 */
	public function getLastErrorData(): array
	{
		return $this->last_error_data;
	}
	
	public function getIsRequired(): bool
	{
		return $this->is_required;
	}
	
	public function setIsRequired( bool $is_required ): void
	{
		$this->is_required = $is_required;
	}
	
	
	
	protected function setIsValid(): void
	{
		$this->is_valid = true;
		$this->errors = [];
		$this->last_error_code = '';
		$this->last_error_message = '';
		$this->last_error_data = [];
	}
	
	public function isValid(): bool
	{
		return $this->is_valid;
	}
	
	protected function validate_required( mixed $value ) : bool
	{
		if(
			$value === '' ||
			$value === null
		) {
			$this->setError( static::ERROR_CODE_EMPTY );
			
			return false;
		}
		
		return true;
	}
	
	abstract public function validate_value( mixed $value ) : bool;
	
	/**
	 * @return array<string>
	 */
	abstract public function getErrorCodeScope() : array;
	
	public function validate( mixed $value ): bool
	{
		$this->setIsValid();
		
		if(
			$this->is_required &&
			!$this->validate_required($value)
		) {
			return false;
		}
		
		if(!$this->validate_value( $value )) {
			return false;
		}
		
		return true;
	}
	
	
	/**
	 * @return array<string,Entity_Validator_Definition_ValidatorOption>
	 * @throws Entity_Validator_Definition_Exception
	 */
	public static function getValidatorOptionsDefinition() : array
	{
		$class = static::class;
		
		if(!array_key_exists($class, static::$validator_options_definition)) {
			$properties = Attributes::getClassPropertyDefinition( new ReflectionClass($class), Entity_Validator_Definition_ValidatorOption::class );
			static::$validator_options_definition[$class] = [];
			
			foreach($properties as $option_name=>$def_data) {
				static::$validator_options_definition[$class][$option_name] = new Entity_Validator_Definition_ValidatorOption();
				static::$validator_options_definition[$class][$option_name]->setup($class, $option_name, $def_data);
				
			}
			
			
		}
		return static::$validator_options_definition[$class];
	}
	
	
}