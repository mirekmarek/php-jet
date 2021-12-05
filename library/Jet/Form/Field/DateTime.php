<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use DateTime;

/**
 *
 */
class Form_Field_DateTime extends Form_Field_Input
{

	/**
	 * @var string
	 */
	protected string $_type = Form::TYPE_DATE_TIME;

	/**
	 * @var array
	 */
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY          => '',
		self::ERROR_CODE_INVALID_FORMAT => '',
	];

	/**
	 * @param Data_Array $data
	 */
	public function catchInput( Data_Array $data ): void
	{
		parent::catchInput( $data );

		if( $this->_value === '' ) {
			$this->_value = null;
		}

	}

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

		if( $this->_value ) {

			$check = DateTime::createFromFormat( 'Y-m-d\TH:i', $this->_value );
			$check_c = DateTime::createFromFormat( 'Y-m-d\TH:i:s', $this->_value );

			if( !$check && !$check_c ) {
				$this->setError( self::ERROR_CODE_INVALID_FORMAT );

				return false;
			}
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