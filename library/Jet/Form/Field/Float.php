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
class Form_Field_Float extends Form_Field_Input
{
	const ERROR_CODE_OUT_OF_RANGE = 'out_of_range';

	/**
	 * @var string
	 */
	protected static string $default_renderer_script = 'field';

	/**
	 * @var string
	 */
	protected static string $default_row_start_renderer_script = 'field/row/start';

	/**
	 * @var string
	 */
	protected static string $default_row_end_renderer_script = 'field/row/end';

	/**
	 * @var string
	 */
	protected static string $default_input_container_start_renderer_script = 'field/input/container/start';

	/**
	 * @var string
	 */
	protected static string $default_input_container_end_renderer_script = 'field/input/container/end';

	/**
	 * @var string
	 */
	protected static string $default_error_renderer = 'field/error';

	/**
	 * @var string
	 */
	protected static string $default_label_renderer = 'field/label';

	/**
	 * @var string string
	 */
	protected static string $default_input_renderer = 'field/input/float';


	/**
	 * @var string
	 */
	protected string $_type = Form::TYPE_FLOAT;

	/**
	 * @var array
	 */
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY        => '',
		self::ERROR_CODE_OUT_OF_RANGE => '',
	];

	/**
	 * @var null|float
	 */
	protected null|float $min_value = null;

	/**
	 * @var null|float
	 */
	protected null|float $max_value = null;

	/**
	 * @var float
	 */
	protected float $step = 0.01;

	/**
	 * @var null|int
	 */
	protected null|int $places = null;

	/**
	 * @return float|null
	 */
	public function getMinValue(): float|null
	{
		return $this->min_value;
	}

	/**
	 * @param float $min
	 */
	public function setMinValue( float $min )
	{
		$this->min_value = $min;
	}

	/**
	 * @return float|null
	 */
	public function getMaxValue(): float|null
	{
		return $this->max_value;
	}

	/**
	 * @param float $max
	 */
	public function setMaxValue( float $max )
	{
		$this->max_value = $max;
	}

	/**
	 * @return float
	 */
	public function getStep(): float
	{
		return $this->step;
	}

	/**
	 * @param float $step
	 */
	public function setStep( float $step )
	{
		$this->step = $step;
	}


	/**
	 * @return int|null
	 */
	public function getPlaces(): int|null
	{
		return $this->places;
	}

	/**
	 * @param int $places
	 */
	public function setPlaces( int $places )
	{
		$this->places = $places;
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

		$this->_value_raw = str_replace( ',', '.', $this->_value_raw );
		$this->_value = (float)$this->_value_raw;


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