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
class Form_Field_Color extends Form_Field_Input
{

	/**
	 * @var string
	 */
	protected string $_type = Form_Field::TYPE_COLOR;
	
	/**
	 * @var array
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY        => '',
		Form_Field::ERROR_CODE_OUT_OF_RANGE => '',
	];
	
	
	protected function validate_format() : bool
	{
		
		if(
			$this->_value!=='' &&
			!preg_match( '/^#[a-f0-9]{6}$/i', $this->_value )
		) {
			$this->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );
			
			return false;
		}
		
		return true;
	}
	
	/**
	 * validate value
	 *
	 * @return bool
	 */
	public function validate(): bool
	{
		if(
			!$this->validate_required() ||
			!$this->validate_format() ||
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
		$codes[] = Form_Field::ERROR_CODE_INVALID_FORMAT;

		return $codes;
	}

}