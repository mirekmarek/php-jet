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
	 * @var array
	 */
	protected array $error_messages = [];

	/**
	 * @var callable
	 */
	protected $validator;

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


	/**
	 * @return callable|null
	 */
	public function getValidator(): callable|null
	{
		return $this->validator;
	}

	/**
	 * @param callable $validator
	 */
	public function setValidator( callable $validator ) : void
	{
		$this->validator = $validator;
	}

	/**
	 * @return array
	 */
	abstract public function getRequiredErrorCodes(): array;

	/**
	 *
	 * @return array
	 */
	public function getErrorMessages(): array
	{
		return $this->error_messages;
	}

	/**
	 *
	 * @param array $error_messages
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
	 * @param array $data
	 *
	 * @return string|bool
	 */
	public function getErrorMessage( string $code, array $data=[] ): string|bool
	{
		$message = $this->error_messages[$code] ?? false;

		return $this->_( $message, $data );

	}

	/**
	 * @param string $code
	 * @param array $data
	 */
	public function setError( string $code, array $data = [] ): void
	{
		/**
		 * @var Form_Field $this
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
		$this->last_error_code = false;
		$this->last_error_message = false;
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
	 * @return bool
	 */
	protected function validate_required() : bool
	{
		if(
			$this->is_required &&
			(
				$this->_value === '' ||
				$this->_value === null
			)
		) {
			$this->setError( Form_Field::ERROR_CODE_EMPTY );
			
			return false;
		}

		return true;
	}
	
	/**
	 * @return bool
	 */
	protected function validate_validator() : bool
	{
		$validator = $this->getValidator();
		if(
			$validator &&
			!$validator( $this )
		) {
			return false;
		}
		
		return true;
	}

	/**
	 *
	 * @return bool
	 */
	abstract public function validate(): bool;

}