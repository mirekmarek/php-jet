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
class Form_Field_Url extends Form_Field_Input
{
	/**
	 * @var string
	 */
	protected string $_type = Form::TYPE_URL;


	/**
	 * @var array
	 */
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY          => '',
		self::ERROR_CODE_INVALID_FORMAT => '',
	];

	/**
	 * validate value
	 *
	 * @return bool
	 */
	public function validate(): bool
	{
		if(
			!$this->is_required &&
			$this->_value === ''
		) {
			return true;
		}

		if( !filter_var( $this->_value, FILTER_VALIDATE_URL ) ) {
			$this->setError( self::ERROR_CODE_INVALID_FORMAT );

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
			$codes[] = self::ERROR_CODE_EMPTY;
		}
		$codes[] = self::ERROR_CODE_INVALID_FORMAT;

		return $codes;
	}
}