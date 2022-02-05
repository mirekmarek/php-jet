<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
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
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY          => '',
		self::ERROR_CODE_INVALID_FORMAT => '',
	];

	/**
	 * @var callable
	 */
	protected $validator;


	/**
	 * validation regexp
	 *
	 * @var string
	 */
	protected string $validation_regexp = '';

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
	public function setValidator( callable $validator )
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
	 * @param bool $raw
	 *
	 * @return string
	 */
	public function getValidationRegexp( bool $raw = false ): string
	{
		if( $raw ) {
			return $this->validation_regexp;
		}

		$regexp = $this->validation_regexp;

		if(
			isset( $regexp[0] ) &&
			$regexp[0] == '/'
		) {
			$regexp = substr( $regexp, 1 );
			$regexp = substr( $regexp, 0, strrpos( $regexp, '/' ) );
		}

		return $regexp;
	}

	/**
	 *
	 * @param string $validation_regexp
	 */
	public function setValidationRegexp( string $validation_regexp ): void
	{
		$this->validation_regexp = $validation_regexp;
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
	 *
	 * @return bool
	 */
	public function validate(): bool
	{
		if(
			$this->is_required &&
			$this->_value === ''
		) {
			$this->setError( self::ERROR_CODE_EMPTY );

			return false;
		}


		if(
			$this->validation_regexp &&
			$this->_value!==''
		) {

			if( $this->validation_regexp[0] != '/' ) {
				$res = preg_match( '/' . $this->validation_regexp . '/', $this->_value );
			} else {
				$res = preg_match( $this->validation_regexp, $this->_value );
			}

			if(!$res) {
				$this->setError( self::ERROR_CODE_INVALID_FORMAT );
				return false;
			}
		}



		$validator = $this->getValidator();
		if(
			$validator &&
			!$validator( $this )
		) {
			return false;
		}

		$this->setIsValid();
		return true;
	}

}