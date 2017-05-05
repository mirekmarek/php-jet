<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Form_Field_DateTime
 * @package Jet
 */
class Form_Field_DateTime extends Form_Field_Input
{
	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_DATE_TIME;

	/**
	 * @var string
	 */
	protected $_input_type = 'datetime-local';

	/**
	 * @var array
	 */
	protected $error_messages = [
		self::ERROR_CODE_EMPTY => '', self::ERROR_CODE_INVALID_FORMAT => '',
	];

	/**
	 * validate value
	 *
	 * @return bool
	 */
	public function validateValue()
	{
		if( !$this->is_required&&$this->_value==='' ) {
			$this->_value = null;

			return true;
		}


		if( $this->_value ) {
			$check = \DateTime::createFromFormat( 'Y-m-d\TH:i', $this->_value );

			if( !$check ) {
				$this->setValueError( self::ERROR_CODE_INVALID_FORMAT );

				return false;
			}
		}

		$this->_setValueIsValid();

		return true;
	}

	/**
	 * returns field value
	 *
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->_value;
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