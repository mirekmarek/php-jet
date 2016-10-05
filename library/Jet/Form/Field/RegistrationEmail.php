<?php 
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

class Form_Field_RegistrationEmail extends Form_Field_Email {
	const ERROR_CODE_USER_ALREADY_EXISTS = 'user_already_exists';

	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_REGISTRATION_EMAIL;

	/**
	 * @var string
	 */
	protected $_input_type = 'email';

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
		self::ERROR_CODE_EMPTY => '',
		self::ERROR_CODE_INVALID_FORMAT => '',
		self::ERROR_CODE_USER_ALREADY_EXISTS => ''
	];

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
	public function setUserExistsCheckCallback( callable $user_exists_check_callback)
	{
		$this->user_exists_check_callback = $user_exists_check_callback;
	}



	/**
	 * validate value
	 *
	 * @return bool
	 */
	public function validateValue() {

		if(!$this->_value) {
			$this->setValueError(self::ERROR_CODE_EMPTY);

			return false;
		}

		if(!filter_var( $this->_value, FILTER_VALIDATE_EMAIL )) {
			$this->setValueError(self::ERROR_CODE_INVALID_FORMAT);
			return false;
		}

		$callback = $this->getUserExistsCheckCallback();

		if( !$callback($this->_value) ) {
			$this->setValueError(self::ERROR_CODE_USER_ALREADY_EXISTS);
			return false;
		}

		$this->_setValueIsValid();

		return true;
	}


	/**
	 * @return array
	 */
	public function getRequiredErrorCodes()
	{
		$codes = [];

		$codes[] = self::ERROR_CODE_EMPTY;
		$codes[] = self::ERROR_CODE_INVALID_FORMAT;
		$codes[] = self::ERROR_CODE_USER_ALREADY_EXISTS;

		return $codes;
	}
}