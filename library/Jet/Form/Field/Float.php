<?php 
/**
 *
 *
 *
 * class representing single form field - type float
 *
 * specific options:
 * 	min_value: min. value
 *      max_value: max. value
 *
 * specific errors:
 *  out_of_range
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

class Form_Field_Float extends Form_Field_Abstract {
	/**
	 * @var string
	 */
	protected $_type = 'Float';

	/**
	 * @var array
	 */
	protected $error_messages = [
				'empty' => 'empty',
				'invalid_format' => 'invalid_format',
				'out_of_range' => 'out_of_range',
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
		
		
		$min = $this->min_value;
		$max = $this->max_value;
		
		if(
			$min!==null &&
			$this->_value < $min
		) {
			$this->setValueError('out_of_range');
			return false;
		}
		
		if(
			$max!==null &&
			$this->_value > $max
		) {
			$this->setValueError('out_of_range');
			return false;
		}
		
		$this->_setValueIsValid();
		
		return true;
		
	}

}