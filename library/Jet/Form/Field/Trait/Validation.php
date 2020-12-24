<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
		self::ERROR_CODE_EMPTY => '',
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
	public function getValidator() : callable|null
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
	abstract public function getRequiredErrorCodes() : array;

	/**
	 *
	 * @return array
	 */
	public function getErrorMessages() : array
	{
		return $this->error_messages;
	}

	/**
	 *
	 * @param array $error_messages
	 *
	 * @throws Form_Exception
	 */
	public function setErrorMessages( array $error_messages ) : void
	{

		foreach( $error_messages as $key => $message ) {
			if( !array_key_exists( $key, $this->error_messages ) ) {
				throw new Form_Exception( 'Unknown form field error code: '.$key.'! Field: '.$this->_name );
			}

			$this->error_messages[$key] = $message;
		}
	}

	/**
	 *
	 * @param string $code
	 *
	 * @return string|bool
	 */
	public function getErrorMessage( string $code ) : string|bool
	{
		$message = isset( $this->error_messages[$code] ) ? $this->error_messages[$code] : false;

		return $this->_( $message );

	}

	/**
	 *
	 * @param string $code
	 */
	public function setError( string $code ) : void
	{
		/**
		 * @var Form_Field $this
		 * @var Form $form
		 */
		$form = $this->_form;

		$this->is_valid = false;
		$form->setIsNotValid();
		$this->last_error_code = $code;
		$this->last_error_message = $this->getErrorMessage( $code );
	}

	/**
	 *
	 * @param string $error_message
	 * @param string $code
	 */
	public function setCustomError( string $error_message, string $code='custom' ) : void
	{
		/**
		 * @var Form_Field $this
		 * @var Form $form
		 */
		$form = $this->_form;

		$this->is_valid = false;
		$form->setIsNotValid();
		$this->last_error_code = $code;
		$this->last_error_message = $error_message;
	}

	/**
	 *
	 * @return string
	 */
	public function getLastErrorCode() : string
	{
		return $this->last_error_code;
	}

	/**
	 *
	 * @return string
	 */
	public function getLastErrorMessage() : string
	{
		return $this->last_error_message;
	}

	/**
	 *
	 * @param bool $raw
	 *
	 * @return string
	 */
	public function getValidationRegexp( bool $raw=false ) : string
	{
		if($raw) {
			return $this->validation_regexp;
		}

		$regexp = $this->validation_regexp;

		if(
			isset($regexp[0]) &&
			$regexp[0]=='/'
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
	public function setValidationRegexp( string $validation_regexp ) : void
	{
		$this->validation_regexp = $validation_regexp;
	}

	/**
	 *
	 * @param Data_Array $data
	 */
	public function catchInput( Data_Array $data ) : void
	{
		$this->_value = null;
		$this->_has_value = $data->exists( $this->_name );

		if( $this->_has_value ) {
			$this->_value_raw = $data->getRaw( $this->_name );
			$this->_value = trim( $data->getString( $this->_name ) );
		} else {
			$this->_value_raw = null;
			$this->_value = $this->default_value;
		}
	}

	/**
	 *
	 * @return bool
	 */
	public function checkValueIsNotEmpty() : bool
	{
		if(
			$this->_value==='' &&
			$this->is_required
		) {
			$this->setError( self::ERROR_CODE_EMPTY );

			return false;
		}

		return true;
	}

	/**
	 *
	 * @return bool
	 */
	public function validate() : bool
	{

		if( !$this->validateFormat() ) {
			$this->setError( self::ERROR_CODE_INVALID_FORMAT );

			return false;
		}

		$this->setIsValid();

		return true;
	}

	/**
	 *
	 * @return bool|int
	 */
	protected function validateFormat() : bool|int
	{

		if( !$this->validation_regexp ) {
			return true;
		}

		if(
			!$this->is_required &&
			$this->_value===''
		) {
			return true;
		}

		if( $this->validation_regexp[0]!='/' ) {
			return preg_match( '/'.$this->validation_regexp.'/', $this->_value );
		} else {
			return preg_match( $this->validation_regexp, $this->_value );
		}

	}

	/**
	 *
	 */
	protected function setIsValid() : void
	{
		$this->is_valid = true;
		$this->last_error_code = false;
		$this->last_error_message = false;
	}

	/**
	 *
	 * @return bool
	 */
	public function isValid() : bool
	{
		return $this->is_valid;
	}

}