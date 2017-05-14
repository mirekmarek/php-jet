<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Form_Field_RegistrationUsername
 * @package Jet
 */
class Form_Field_RegistrationUsername extends Form_Field_Input
{
	const ERROR_CODE_USER_ALREADY_EXISTS = 'user_already_exists';

	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_REGISTRATION_USER_NAME;

	/**
	 * @var bool
	 */
	protected $is_required = true;


	/**
	 * @var callable
	 */
	protected $user_exists_check_callback;

	/**
	 * @var array
	 */
	protected $error_messages = [
		self::ERROR_CODE_EMPTY               => '',
		self::ERROR_CODE_INVALID_FORMAT      => '',
		self::ERROR_CODE_USER_ALREADY_EXISTS => '',
	];

	/**
	 * validate value
	 *
	 * @return bool
	 */
	public function validateValue()
	{

		if( !$this->_value ) {

			$this->setError( self::ERROR_CODE_EMPTY );

			return false;
		}

		if( !$this->_validateFormat() ) {
			$this->setError( self::ERROR_CODE_INVALID_FORMAT );

			return false;
		}

		$callback = $this->getUserExistsCheckCallback();

		if( !$callback( $this->_value ) ) {
			$this->setError( self::ERROR_CODE_USER_ALREADY_EXISTS );

			return false;
		}

		$this->_setValueIsValid();

		return true;
	}

	/**
	 * @return callable
	 */
	public function getUserExistsCheckCallback()
	{
		return $this->user_exists_check_callback;
	}

	/**
	 * @param callable $user_exists_check_callback
	 */
	public function setUserExistsCheckCallback( callable $user_exists_check_callback )
	{
		$this->user_exists_check_callback = $user_exists_check_callback;
	}

	/**
	 * @return array
	 */
	public function getRequiredErrorCodes()
	{
		$codes = [];

		$codes[] = self::ERROR_CODE_EMPTY;
		if( $this->validation_regexp ) {
			$codes[] = self::ERROR_CODE_INVALID_FORMAT;
		}
		$codes[] = self::ERROR_CODE_USER_ALREADY_EXISTS;

		return $codes;
	}
}