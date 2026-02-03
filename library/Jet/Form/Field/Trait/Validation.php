<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
trait Form_Field_Trait_Validation
{

	/**
	 * @var array<string,string>
	 */
	protected array $error_messages = [];

	protected string $_validator_type;
	
	/**
	 * @var ?Validator
	 */
	protected ?Validator $validator = null;

	/**
	 *
	 * @var bool
	 */
	protected bool $is_valid = false;
	
	/**
	 * @var Form_ValidationError[]
	 */
	protected array $errors = [];
	
	/**
	 *
	 * @var string
	 */
	protected string $last_error_code = '';

	/**
	 *
	 * @var string
	 */
	protected string $last_error_message = '';
	
	public function validatorFactory(): Validator
	{
		$validator = Factory_Validator::getValidatorInstance( $this->_validator_type );
		$this->initValidator( $validator );
		
		return $validator;
	}

	/**
	 * @return Validator
	 */
	public function getValidator(): Validator
	{
		if(!$this->validator) {
			$this->validator = $this->validatorFactory();
		}
		
		return $this->validator;
	}
	
	protected function initValidator( Validator $validator ) : void
	{
		$validator->setErrorMessageGenerator( new class( $this ) extends Validator_ErrorMessageGenerator {
			protected Form_Field $field;
			
			public function __construct( Form_Field $field )
			{
				$this->field = $field;
			}
			
			public function generateErrorMessage( string $error_code, array $error_data ): string
			{
				return $this->field->getErrorMessage( $error_code, $error_data );
			}
		});
		$validator->setIsRequired( $this->getIsRequired() );
		
	}

	/**
	 * @param callable|Validator $validator
	 */
	public function setValidator( callable|Validator $validator ) : void
	{
		if(is_callable($validator)) {
			$validator = new class($this, $validator) extends Validator {
				protected Form_Field $field;
				/**
				 * @var callable $validator;
				 */
				protected $validator;
				
				public function __construct( Form_Field $field, callable $validator )
				{
					$this->field = $field;
					$this->validator = $validator;
				}
				
				public function validate_value( mixed $value ): bool
				{
					$validator = $this->validator;
					return $validator( $this->field );
				}
				
				public function getErrorCodeScope(): array
				{
					$error_codes = [];
					
					if( $this->is_required ) {
						$error_codes[] = static::ERROR_CODE_EMPTY;
					}

					return $error_codes;
				}
			};
		}
		
		$this->initValidator( $validator );
		
		$this->validator = $validator;
	}

	/**
	 * @return array<string>
	 */
	public function getRequiredErrorCodes(): array
	{
		return $this->getValidator()->getErrorCodeScope();
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

	/**
	 * @param string $code
	 * @param array<string,mixed> $data
	 *
	 * @return string|false
	 */
	public function getErrorMessage( string $code, array $data=[] ): string|false
	{
		$message = $this->error_messages[$code] ?? false;

		return $this->_( $message, $data );

	}

	/**
	 * @param string $code
	 * @param array<string,string> $data
	 */
	public function setError( string $code, array $data = [] ): void
	{
		/**
		 * @var Form $form
		 */
		$form = $this->_form;
		
		$message = $this->getErrorMessage( $code, $data );
		
		$this->is_valid = false;
		$form->setIsNotValid();
		
		$this->errors[] = new Form_ValidationError($this, $code, $message);
		
		$this->last_error_code = $code;
		$this->last_error_message = $message;
	}
	
	/**
	 * @return Form_ValidationError[]
	 */
	public function getAllErrors() : array
	{
		return $this->errors;
	}

	/**
	 *
	 * @return string
	 */
	public function getLastErrorCode(): string
	{
		return $this->last_error_code;
	}

	/**
	 *
	 * @return string
	 */
	public function getLastErrorMessage(): string
	{
		return $this->last_error_message;
	}

	/**
	 *
	 */
	protected function setIsValid(): void
	{
		$this->is_valid = true;
		$this->errors = [];
		$this->last_error_code = '';
		$this->last_error_message = '';
	}

	/**
	 *
	 * @return bool
	 */
	public function isValid(): bool
	{
		return $this->is_valid;
	}
	
	/**
	 *
	 * @return bool
	 */
	public function validate(): bool
	{
		$this->setIsValid();
		$validator = $this->getValidator();
		
		if($validator->validate( $this->getInputCatcher()->getValueRaw() )) {
			return true;
		}
		
		/**
		 * @var Form $form
		 */
		$form = $this->_form;
		$this->is_valid = false;
		$form->setIsNotValid();
		
		$this->last_error_code = $validator->getLastErrorCode();
		$this->last_error_message = $validator->getLastErrorMessage();
		
		
		foreach($validator->getAllErrors() as $error) {
			$this->errors[] = new Form_ValidationError( $this, $error->getCode(), $error->getMessage() );
		}
		
		return false;
	}

}