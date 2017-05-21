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
class Form_Field_RegistrationEmail extends Form_Field_Email
{
	const ERROR_CODE_USER_ALREADY_EXISTS = 'user_already_exists';

	/**
	 * @var string
	 */
	protected static $default_renderer_script = 'field';

	/**
	 * @var string
	 */
	protected static $default_row_start_renderer_script = 'Field/row/start';

	/**
	 * @var string
	 */
	protected static $default_row_end_renderer_script = 'Field/row/end';

	/**
	 * @var string
	 */
	protected static $default_input_container_start_renderer_script = 'Field/input/container/start';

	/**
	 * @var string
	 */
	protected static $default_input_container_end_renderer_script = 'Field/input/container/end';

	/**
	 * @var string
	 */
	protected static $default_error_renderer = 'Field/error';

	/**
	 * @var string
	 */
	protected static $default_label_renderer = 'Field/label';

	/**
	 * @var string string
	 */
	protected static $default_input_renderer = 'Field/input/RegistrationEmail';


	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_REGISTRATION_EMAIL;

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
	public function validate()
	{

		if( !$this->_value ) {
			$this->setError( self::ERROR_CODE_EMPTY );

			return false;
		}

		if( !filter_var( $this->_value, FILTER_VALIDATE_EMAIL ) ) {
			$this->setError( self::ERROR_CODE_INVALID_FORMAT );

			return false;
		}

		$callback = $this->getUserExistsCheckCallback();

		if( !$callback( $this->_value ) ) {
			$this->setError( self::ERROR_CODE_USER_ALREADY_EXISTS );

			return false;
		}

		$this->setIsValid();

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
		$codes[] = self::ERROR_CODE_INVALID_FORMAT;
		$codes[] = self::ERROR_CODE_USER_ALREADY_EXISTS;

		return $codes;
	}
}