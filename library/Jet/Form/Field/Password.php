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
	protected string $_type = Form_Field::TYPE_PASSWORD;
	protected string $_validator_type = Validator::TYPE_PASSWORD;
	protected string $_input_catcher_type = InputCatcher::TYPE_STRING;
	
	public const ERROR_CODE_WEAK_PASSWORD =  Validator_Password::ERROR_CODE_WEAK_PASSWORD;
	
	#[Form_Definition_FieldOption(
		type: Form_Definition_FieldOption::TYPE_FLOAT,
		label: 'Minimal password check score',
		getter: 'getMinimalScore',
		setter: 'setMinimalScore',
	)]
	protected float $minimal_score = 0;
	
	/**
	 * @var array<string,string>
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY => 'Please enter a value',
		self::ERROR_CODE_WEAK_PASSWORD => 'Week password',
		self::ERROR_CODE_CHECK_NOT_MATCH => 'Password verification does not match',
	];
	
	protected bool $is_required = true;
	
	public function getMinimalScore(): float
	{
		return $this->minimal_score;
	}
	
	public function setMinimalScore( float $minimal_score ): void
	{
		$this->minimal_score = $minimal_score;
	}
	
	public function getValidator() : Validator|Validator_Password
	{
		if(!$this->validator) {
			$this->validator = $this->validatorFactory();
		}
		
		/**
		 * @var Validator|Validator_Password $validator;
		 */
		$validator = $this->validator;
		
		$validator->setIsRequired( true );
		
		if( $validator instanceof Validator_Password ) {
			$validator->setMinimalScore( $this->getMinimalScore() );
		}
		
		
		return $validator;
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