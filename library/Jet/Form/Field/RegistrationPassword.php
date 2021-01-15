<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

/**
 *
 */
class Form_Field_RegistrationPassword extends Form_Field
{
	const ERROR_CODE_CHECK_EMPTY = 'check_empty';
	const ERROR_CODE_CHECK_NOT_MATCH = 'check_not_match';
	const ERROR_CODE_WEAK_PASSWORD = 'weak_password';

	/**
	 * @var string
	 */
	protected static string $default_renderer_script = 'field-RegistrationPassword';

	/**
	 * @var string
	 */
	protected static string $default_row_start_renderer_script = 'Field/row/start';

	/**
	 * @var string
	 */
	protected static string $default_row_end_renderer_script = 'Field/row/end';

	/**
	 * @var string
	 */
	protected static string $default_input_container_start_renderer_script = 'Field/input/container/start';

	/**
	 * @var string
	 */
	protected static string $default_input_container_end_renderer_script = 'Field/input/container/end';

	/**
	 * @var string
	 */
	protected static string $default_error_renderer = 'Field/error';

	/**
	 * @var string
	 */
	protected static string $default_label_renderer = 'Field/label';

	/**
	 * @var string string
	 */
	protected static string $default_input_renderer = 'Field/input/RegistrationPassword';

	/**
	 * @var string
	 */
	protected string $_type = Form::TYPE_REGISTRATION_PASSWORD;

	/**
	 * @var array
	 */
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY           => '',
		self::ERROR_CODE_CHECK_EMPTY     => '',
		self::ERROR_CODE_CHECK_NOT_MATCH => '',
		self::ERROR_CODE_WEAK_PASSWORD   => '',
	];

	/**
	 * @var bool
	 */
	protected bool $is_required = true;

	/**
	 * @var string
	 */
	protected string $password_confirmation_label = '';

	/**
	 * @var callable|null
	 */
	protected $password_strength_check_callback = null;

	/**
	 * @var ?Form_Field_Password
	 */
	protected ?Form_Field_Password $confirmation_input = null;

	/**
	 * @return ?Form_Renderer_Single
	 */
	protected ?Form_Renderer_Single $_tag_label_confirmation = null;

	/**
	 * @return ?Form_Renderer_Single
	 */
	protected ?Form_Renderer_Single $_tag_field_confirmation = null;


	/**
	 *
	 * @param string $name
	 * @param string $label
	 * @param string $default_value
	 * @param bool $is_required
	 */
	public function __construct( string $name, string $label = '', string $default_value = '', bool $is_required = false )
	{
		parent::__construct( $name, $label, $default_value, $is_required );

		$this->confirmation_input = new Form_Field_Password( $name . '_confirmation' );
	}

	/**
	 * @param Form $form
	 */
	public function setForm( Form $form ): void
	{
		parent::setForm( $form );
		$this->confirmation_input->setForm( $form );
	}

	/**
	 *
	 * @param Data_Array $data
	 */
	public function catchInput( Data_Array $data ): void
	{

		parent::catchInput( $data );

		$this->confirmation_input->catchInput( $data );

	}

	/**
	 * @return bool
	 */
	public function checkValueIsNotEmpty(): bool
	{
		if( !$this->_value ) {
			$this->setError( self::ERROR_CODE_EMPTY );

			return false;
		}

		if( !$this->confirmation_input->_value ) {
			$this->setError( self::ERROR_CODE_CHECK_EMPTY );

			return false;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function validate(): bool
	{

		if( $this->_value != $this->confirmation_input->_value ) {
			$this->setError( self::ERROR_CODE_CHECK_NOT_MATCH );

			return false;
		}

		$check_callback = $this->getPasswordStrengthCheckCallback();

		if( $check_callback ) {
			if( !$check_callback( $this->_value ) ) {
				$this->setError( self::ERROR_CODE_WEAK_PASSWORD );

				return false;
			}
		}


		$this->setIsValid();

		return true;
	}

	/**
	 * @return callable|null
	 */
	public function getPasswordStrengthCheckCallback(): callable|null
	{
		return $this->password_strength_check_callback;
	}

	/**
	 * @param callable $password_strength_check_callback
	 */
	public function setPasswordStrengthCheckCallback( callable $password_strength_check_callback ): void
	{
		$this->password_strength_check_callback = $password_strength_check_callback;
	}

	/**
	 * @return array
	 */
	public function getRequiredErrorCodes(): array
	{
		$codes = [];


		$codes[] = self::ERROR_CODE_EMPTY;
		$codes[] = self::ERROR_CODE_CHECK_EMPTY;
		$codes[] = self::ERROR_CODE_CHECK_NOT_MATCH;


		if( $this->password_strength_check_callback ) {
			$codes[] = self::ERROR_CODE_WEAK_PASSWORD;
		}

		return $codes;
	}

	/**
	 * @return string
	 */
	public function render(): string
	{
		if( $this->getIsReadonly() ) {
			return '';
		}

		return parent::render();
	}

	/**
	 * @return Form_Renderer_Single
	 */
	public function label_confirmation(): Form_Renderer_Single
	{
		if( !$this->_tag_label_confirmation ) {
			$this->_tag_label_confirmation = $this->confirmation_input->label();
		}

		return $this->_tag_label_confirmation;
	}

	/**
	 * @return string
	 */
	public function getPasswordConfirmationLabel(): string
	{
		return $this->confirmation_input->getLabel();
	}

	/**
	 * @param string $password_confirmation_label
	 */
	public function setPasswordConfirmationLabel( string $password_confirmation_label ): void
	{
		$this->confirmation_input->setLabel( $password_confirmation_label );
	}

	/**
	 * @return Form_Renderer_Single
	 */
	public function input_confirmation(): Form_Renderer_Single
	{
		if( !$this->_tag_field_confirmation ) {

			$this->_tag_field_confirmation = $this->confirmation_input->input();
		}

		return $this->_tag_field_confirmation;
	}
}