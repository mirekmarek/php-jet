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
class Form_Field_Time extends Form_Field_Input
{
	/**
	 * @var string
	 */
	protected string $_type = Form_Field::TYPE_TIME;
	
	/**
	 * @var array
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY        => '',
		Form_Field::ERROR_CODE_INVALID_FORMAT => '',
	];
	
	
	/**
	 * @return bool
	 */
	protected function validate_format() : bool
	{
		if($this->_value) {
			$check = DateTime::createFromFormat( 'Y-m-d H:i', '2011-01-01 ' . $this->_value );
			$check_c = DateTime::createFromFormat( 'Y-m-d H:i:s', '2011-01-01 ' . $this->_value );
			
			if( !$check && !$check_c ) {
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
		
		if( $this->is_required ) {
			$codes[] = Form_Field::ERROR_CODE_EMPTY;
		}
		$codes[] = Form_Field::ERROR_CODE_INVALID_FORMAT;
		
		return $codes;
	}
	
}