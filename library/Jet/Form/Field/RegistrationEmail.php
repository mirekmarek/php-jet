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
class Form_Field_RegistrationEmail extends Form_Field_Email
{
	const ERROR_CODE_USER_ALREADY_EXISTS = 'user_already_exists';

	/**
	 * @var string
	 */
	protected static string $default_renderer_script = 'field';

	/**
	 * @var string
	 */
	protected static string $default_row_start_renderer_script = 'field/row/start';

	/**
	 * @var string
	 */
	protected static string $default_row_end_renderer_script = 'field/row/end';

	/**
	 * @var string
	 */
	protected static string $default_input_container_start_renderer_script = 'field/input/container/start';

	/**
	 * @var string
	 */
	protected static string $default_input_container_end_renderer_script = 'field/input/container/end';

	/**
	 * @var string
	 */
	protected static string $default_error_renderer = 'field/error';

	/**
	 * @var string
	 */
	protected static string $default_label_renderer = 'field/label';

	/**
	 * @var string string
	 */
	protected static string $default_input_renderer = 'field/input/registration-email';


	/**
	 * @var string
	 */
	protected string $_type = Form::TYPE_REGISTRATION_EMAIL;

	/**
	 * @var bool
	 */
	protected bool $is_required = true;


	/**
	 * @var callable|null
	 */
	protected $user_exists_check_callback = null;

	/**
	 * @var array
	 */
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY               => '',
		self::ERROR_CODE_INVALID_FORMAT      => '',
		self::ERROR_CODE_USER_ALREADY_EXISTS => '',
	];

	/**
	 * validate value
	 *
	 * @return bool
	 */
	public function validate(): bool
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
	 * @return callable|null
	 */
	public function getUserExistsCheckCallback(): callable|null
	{
		return $this->user_exists_check_callback;
	}

	/**
	 * @param callable $user_exists_check_callback
	 */
	public function setUserExistsCheckCallback( callable $user_exists_check_callback ): void
	{
		$this->user_exists_check_callback = $user_exists_check_callback;
	}

	/**
	 * @return array
	 */
	public function getRequiredErrorCodes(): array
	{
		$codes = [];

		$codes[] = self::ERROR_CODE_EMPTY;
		$codes[] = self::ERROR_CODE_INVALID_FORMAT;
		$codes[] = self::ERROR_CODE_USER_ALREADY_EXISTS;

		return $codes;
	}
}