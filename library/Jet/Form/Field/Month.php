<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use DateTime;

/**
 *
 */
class Form_Field_Month extends Form_Field
{
	/**
	 * @var string
	 */
	protected string $_type = Form_Field::TYPE_MONTH;
	
	/**
	 * @var array
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY        => 'Please enter a value',
		Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid value',
	];
	
	
	/**
	 * @return bool
	 */
	protected function validate_format() : bool
	{
		if( $this->_value ) {
			$check = DateTime::createFromFormat( 'Y-m-d', $this->_value . '-01' );
			
			if( !$check ) {
				$this->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );
				
				return false;
			}
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
		
		if($this->is_required) {
			$codes[] = Form_Field::ERROR_CODE_EMPTY;
		}
		
		$codes[] = Form_Field::ERROR_CODE_INVALID_FORMAT;
		
		return $codes;
	}
}