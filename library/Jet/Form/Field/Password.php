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
class Form_Field_Password extends Form_Field
{


	/**
	 * @var string
	 */
	protected string $_type = Form_Field::TYPE_PASSWORD;
	
	/**
	 * @var array
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY => ''
	];

	/**
	 * @var bool
	 */
	protected bool $is_required = true;
	
	/**
	 * @return bool
	 */
	public function validate(): bool
	{
		if(
			!$this->validate_required() ||
			!$this->validate_validator()
		) {
			return false;
		}
		
		$this->setIsValid();
		return true;
	}
	

	/**
	 * @return array
	 */
	public function getRequiredErrorCodes(): array
	{
		$codes = [];

		if( $this->is_required ) {
			$codes[] = Form_Field::ERROR_CODE_EMPTY;
		}

		return $codes;
	}

	/**
	 * @param string $field_name
	 * @param string $field_label
	 * @param string $error_message_empty
	 * @param string $error_message_not_match
	 *
	 * @return Form_Field_Password
	 */
	public function generateCheckField(
		string $field_name,
		string $field_label,
		string $error_message_empty,
		string $error_message_not_match
	) : Form_Field_Password
	{
		$password = $this;

		$password_check = new Form_Field_Password( $field_name, $field_label );
		$password_check->setIsRequired( true );

		$password_check->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY     => $error_message_empty,
			self::ERROR_CODE_CHECK_NOT_MATCH => $error_message_not_match,
		] );

		$password_check->setValidator(function() use ($password, $password_check) {
			if(
				$password->getValue() &&
				$password->getValue()!=$password_check->getValue()
			) {
				$password_check->setError(self::ERROR_CODE_CHECK_NOT_MATCH);

				return false;
			}

			return true;
		});

		return $password_check;
	}
}