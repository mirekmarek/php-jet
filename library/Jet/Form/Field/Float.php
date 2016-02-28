<?php 
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

class Form_Field_Float extends Form_Field_Input {
	const ERROR_CODE_OUT_OF_RANGE = 'out_of_range';

	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_FLOAT;
	/**
	 * @var string
	 */
	protected $_input_type = 'number';

	/**
	 * @var array
	 */
	protected $error_messages = [
				self::ERROR_CODE_EMPTY => '',
				self::ERROR_CODE_OUT_OF_RANGE => '',
	];

	/**
	 * @var null|float
	 */
	protected $min_value = null;
	/**
	 * @var null|float
	 */
	protected $max_value = null;

	/**
	 * @var float
	 */
	protected $step = 0.01;

	/**
	 * @var null|int
	 */
	protected $places = null;

	/**
	 * @return float|null
	 */
	public function getMinValue() {
		return $this->min_value;
	}

	/**
	 * @param float $min
	 */
	public function setMinValue($min) {
		$this->min_value = (float)$min;
	}

	/**
	 * @return float|null
	 */
	public function getMaxValue() {
		return $this->max_value;
	}

	/**
	 * @param float $max
	 */
	public function setMaxValue($max) {
		$this->max_value = (float)$max;
	}

	/**
	 * @return float
	 */
	public function getStep()
	{
		return $this->step;
	}

	/**
	 * @param float $step
	 */
	public function setStep($step)
	{
		$this->step = $step;
	}


	/**
	 * @return int|null
	 */
	public function getPlaces() {
		return $this->places;
	}

	/**
	 * @param int $places
	 */
	public function setPlaces($places) {
		$this->places = (int)$places;
	}


	/**
	 * @return bool
	 */
	public function validateValue() {

		if(!$this->is_required && $this->_value_raw === ''){
			$this->_setValueIsValid();
			return true;
		}

		$this->_value = (float)$this->_value_raw;


		$min = (float)$this->min_value;
		$max = (float)$this->max_value;

		if(
			$min!==null &&
			$this->_value < $min
		) {
			$this->setValueError(self::ERROR_CODE_OUT_OF_RANGE);
			return false;
		}

		if(
			$max!==null &&
			$this->_value > $max
		) {
			$this->setValueError(self::ERROR_CODE_OUT_OF_RANGE);
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

		if($this->is_required ) {
			$codes[] = self::ERROR_CODE_EMPTY;
		}

		if($this->min_value!==null || $this->max_value!==null) {
			$codes[] = self::ERROR_CODE_OUT_OF_RANGE;
		}

		return $codes;
	}


	/**
	 * @param Form_Parser_TagData $tag_data
	 */
	protected function _getReplacement_field_prepareParams( Form_Parser_TagData $tag_data )
	{
		parent::_getReplacement_field_prepareParams($tag_data);

		if($this->min_value!==null) {
			$tag_data->setProperty( 'min', $this->min_value);
		}
		if($this->max_value!==null) {
			$tag_data->setProperty( 'max', $this->max_value);
		}

		$tag_data->setProperty( 'step', $this->step);

	}

}