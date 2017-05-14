<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Form_Field_Time
 * @package Jet
 */
class Form_Field_Time extends Form_Field_Input
{
	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_TIME;

	/**
	 * @var array
	 */
	protected $error_messages = [
		self::ERROR_CODE_EMPTY          => '',
		self::ERROR_CODE_INVALID_FORMAT => '',
	];


	/**
	 * validate value
	 *
	 * @return bool
	 */
	public function validateValue()
	{
		if( !$this->is_required&&$this->_value==='' ) {
			return true;
		}


		$check = \DateTime::createFromFormat( 'Y-m-d H:i', '2011-01-01 '.$this->_value );

		if( !$check ) {
			$this->setError( self::ERROR_CODE_INVALID_FORMAT );

			return false;
		}

		$this->_setValueIsValid();

		return true;
	}

	/**
	 * @return array
	 */
	public function getRequiredErrorCodes()
	{
		$codes = [];

		if( $this->is_required ) {
			$codes[] = self::ERROR_CODE_EMPTY;
		}
		$codes[] = self::ERROR_CODE_INVALID_FORMAT;

		return $codes;
	}

}