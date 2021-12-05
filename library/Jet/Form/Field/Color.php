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
class Form_Field_Color extends Form_Field_Input
{

	/**
	 * @var string
	 */
	protected string $_type = Form::TYPE_COLOR;

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
			$this->is_required &&
			$this->_value === ''
		) {
			$this->setError( self::ERROR_CODE_EMPTY );

			return false;
		}



		if(
			$this->_value!=='' &&
			!preg_match( '/^#[a-f0-9]{6}$/i', $this->_value )
		) {
			$this->setError( self::ERROR_CODE_INVALID_FORMAT );

			return false;
		}

		$validator = $this->getValidator();
		if(
			$validator &&
			!$validator( $this )
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
			$codes[] = self::ERROR_CODE_EMPTY;
		}
		$codes[] = self::ERROR_CODE_INVALID_FORMAT;

		return $codes;
	}

}