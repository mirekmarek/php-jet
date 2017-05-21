<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
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
	protected $error_messages = [
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
	protected $validation_regexp;

	/**
	 *
	 * @var bool
	 */
	protected $is_valid = false;

	/**
	 *
	 * @var string
	 */
	protected $last_error_code = '';

	/**
	 *
	 * @var string
	 */
	protected $last_error_message = '';

	/**
	 * @return callable
	 */
	public function getValidator()
	{
		return $this->validator;
	}

	/**
	 * @param callable $validator
	 */
	public function setValidator( $validator )
	{
		$this->validator = $validator;
	}

	/**
	 * @return array
	 */
	abstract public function getRequiredErrorCodes();

	/**
	 *
	 * @return array
	 */
	public function getErrorMessages()
	{
		return $this->error_messages;
	}

	/**
	 *
	 * @param array $error_messages
	 *
	 * @throws Form_Exception
	 */
	public function setErrorMessages( array $error_messages )
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
	public function getErrorMessage( $code )
	{
		$message = isset( $this->error_messages[$code] ) ? $this->error_messages[$code] : false;

		return $this->_( $message );

	}

	/**
	 *
	 * @param string $code
	 */
	public function setError( $code )
	{
		/**
		 * @var Form_Field $this
		 */
		$this->is_valid = false;
		$this->_form->setIsNotValid();
		$this->last_error_code = $code;
		$this->last_error_message = $this->getErrorMessage( $code );
	}

	/**
	 *
	 * @param string $error_message
	 * @param string $code
	 */
	public function setCustomError( $error_message, $code='custom' )
	{
		/**
		 * @var Form_Field $this
		 */
		$this->is_valid = false;
		$this->_form->setIsNotValid();
		$this->last_error_code = $code;
		$this->last_error_message = $error_message;
	}

	/**
	 *
	 * @return string
	 */
	public function getLastErrorCode()
	{
		return $this->last_error_code;
	}

	/**
	 *
	 * @return string
	 */
	public function getLastErrorMessage()
	{
		return $this->last_error_message;
	}

	/**
	 *
	 * @param bool $raw
	 *
	 * @return string
	 */
	public function getValidationRegexp( $raw=false )
	{
		if($raw) {
			return $this->validation_regexp;
		}

		$regexp = $this->validation_regexp;

		if( $regexp[0]=='/' ) {
			$regexp = substr( $regexp, 1 );
			$regexp = substr( $regexp, 0, strrpos( $regexp, '/' ) );
		}

		return $regexp;
	}

	/**
	 *
	 * @param string $validation_regexp
	 */
	public function setValidationRegexp( $validation_regexp )
	{
		$this->validation_regexp = $validation_regexp;
	}

	/**
	 *
	 * @param Data_Array $data
	 */
	public function catchInput( Data_Array $data )
	{
		$this->_value = null;
		$this->_has_value = $data->exists( $this->_name );

		if( $this->_has_value ) {
			$this->_value_raw = $data->getRaw( $this->_name );
			$this->_value = trim( $data->getString( $this->_name ) );
		} else {
			$this->_value_raw = null;
		}
	}

	/**
	 *
	 * @return bool
	 */
	public function checkValueIsNotEmpty()
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
	 * validate value
	 *
	 * @return bool
	 */
	public function validate()
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
	protected function validateFormat()
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
	 * set value is OK
	 */
	protected function setIsValid()
	{
		$this->is_valid = true;
		$this->last_error_code = false;
		$this->last_error_message = false;
	}

	/**
	 * returns true if field is valid
	 *
	 * @return bool
	 */
	public function isValid()
	{
		return $this->is_valid;
	}

}