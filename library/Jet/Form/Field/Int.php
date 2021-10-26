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
class Form_Field_Int extends Form_Field_Input
{
	const ERROR_CODE_OUT_OF_RANGE = 'out_of_range';

	/**
	 * @var string
	 */
	protected string $_type = Form::TYPE_INT;

	/**
	 * @var array
	 */
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY        => '',
		self::ERROR_CODE_OUT_OF_RANGE => '',
	];

	/**
	 * @var null|int
	 */
	protected null|int $min_value = null;

	/**
	 * @var null|int
	 */
	protected null|int $max_value = null;

	/**
	 * @var null|int
	 */
	protected null|int $step = null;


	/**
	 * @return int|null
	 */
	public function getMinValue(): int|null
	{
		return $this->min_value;
	}

	/**
	 * @param int $min
	 */
	public function setMinValue( int $min )
	{
		$this->min_value = $min;
	}

	/**
	 * @return int|null
	 */
	public function getMaxValue(): int|null
	{
		return $this->max_value;
	}

	/**
	 * @param int $max
	 */
	public function setMaxValue( int $max )
	{
		$this->max_value = $max;
	}

	/**
	 * @return int|null
	 */
	public function getStep(): int|null
	{
		return $this->step;
	}

	/**
	 * @param int $step
	 */
	public function setStep( int $step )
	{
		$this->step = $step;
	}

	/**
	 * @return bool
	 */
	public function validate(): bool
	{

		if(
			!$this->is_required &&
			$this->_value_raw === ''
		) {
			$this->setIsValid();

			return true;
		}

		$this->_value = (int)$this->_value_raw;

		if(
			$this->min_value !== null &&
			$this->_value < $this->min_value
		) {
			$this->setError( self::ERROR_CODE_OUT_OF_RANGE );

			return false;
		}

		if(
			$this->max_value !== null &&
			$this->_value > $this->max_value
		) {
			$this->setError( self::ERROR_CODE_OUT_OF_RANGE );

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

		if(
			$this->min_value !== null ||
			$this->max_value !== null
		) {
			$codes[] = self::ERROR_CODE_OUT_OF_RANGE;
		}

		return $codes;
	}
}