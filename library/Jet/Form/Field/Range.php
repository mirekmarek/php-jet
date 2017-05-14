<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Form_Field_Range
 * @package Jet
 */
class Form_Field_Range extends Form_Field_Input
{
	const ERROR_CODE_OUT_OF_RANGE = 'out_of_range';

	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_RANGE;

	/**
	 * @var array
	 */
	protected $error_messages = [
		self::ERROR_CODE_EMPTY        => '',
		self::ERROR_CODE_OUT_OF_RANGE => '',
	];

	/**
	 * @var null|int
	 */
	protected $min_value = null;
	/**
	 * @var null|int
	 */
	protected $max_value = null;

	/**
	 * @var int
	 */
	protected $step = null;


	/**
	 * @return int|null
	 */
	public function getMinValue()
	{
		return $this->min_value;
	}

	/**
	 * @param int $min
	 */
	public function setMinValue( $min )
	{
		$this->min_value = (int)$min;
	}

	/**
	 * @return int|null
	 */
	public function getMaxValue()
	{
		return $this->max_value;
	}

	/**
	 * @param int $max
	 */
	public function setMaxValue( $max )
	{
		$this->max_value = (int)$max;
	}

	/**
	 * @return int
	 */
	public function getStep()
	{
		return $this->step;
	}

	/**
	 * @param int $step
	 */
	public function setStep( $step )
	{
		$this->step = $step;
	}

	/**
	 * @return bool
	 */
	public function validateValue()
	{

		if( !$this->is_required&&$this->_value_raw==='' ) {
			$this->_setValueIsValid();

			return true;
		}

		$this->_value = (int)$this->_value_raw;

		if( $this->min_value!==null&&$this->_value<$this->min_value ) {
			$this->setError( self::ERROR_CODE_OUT_OF_RANGE );

			return false;
		}

		if( $this->max_value!==null&&$this->_value>$this->max_value ) {
			$this->setError( self::ERROR_CODE_OUT_OF_RANGE );

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

		if( $this->min_value!==null||$this->max_value!==null ) {
			$codes[] = self::ERROR_CODE_OUT_OF_RANGE;
		}

		return $codes;
	}

}