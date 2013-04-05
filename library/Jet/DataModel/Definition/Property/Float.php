<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Definition
 */
namespace Jet;

class DataModel_Definition_Property_Float extends DataModel_Definition_Property_Abstract {
	/***
	 * @var string
	 */
	protected $_type = DataModel::TYPE_FLOAT;

	/**
	 * @var float
	 */
	protected $default_value = 0.0;

	/**
	 * @var null|float
	 */
	protected $min_value = null;
	/**
	 * @var null|float
	 */
	protected $max_value = null;

	/**
	 * @var string
	 */
	protected $form_field_type = "Float";

	/**
	 * @param array $definition_data
	 *
	 */
	public function setUp( $definition_data ) {
		if(!$definition_data) {
			return;
		}

		parent::setUp($definition_data);

		if($this->min_value!==null) {
			$this->min_value = (float)$this->min_value;
		}
		if($this->max_value!==null) {
			$this->max_value = (float)$this->max_value;
		}

	}

	/**
	 * @param float &$value
	 */
	public function checkValueType( &$value ) {
		$value = (float)$value;
	}

	/**
	 *
	 * @return float|null
	 */
	public function getMinValue() {
		return $this->min_value;
	}

	/**
	 * @return float|null
	 */
	public function getMaxValue() {
		return $this->max_value;
	}

	/**
	 * @return array
	 */
	public function getFormFieldOptions() {
		if($this->min_value!==null) {
			$this->form_field_options["min_value"] = $this->min_value;
		}
		if($this->max_value!==null) {
			$this->form_field_options["max_value"] = $this->max_value;
		}

		return $this->form_field_options;
	}


	/**
	 * Column value test - checks range
	 *
	 * @param mixed &$value
	 * @param DataModel_ValidationError &$errors
	 *
	 * @return bool
	 */
	public function _validateData_test_value( &$value, &$errors ) {
		if($this->min_value===null && $this->max_value===null) {
			return true;
		}

		if(
			$this->min_value!==null &&
			$value<$this->min_value
		) {
			$errors[] = new DataModel_ValidationError(
					DataModel_ValidationError::CODE_OUT_OF_RANGE,
					$this,
					$value
				);

			return false;
		}

		if(
			$this->max_value!==null &&
			$value>$this->max_value
		) {
			$errors[] = new DataModel_ValidationError(
					DataModel_ValidationError::CODE_OUT_OF_RANGE,
					$this,
					$value
				);

			return false;
		}

		return true;
	}

    /**
     * Property required test
     * has no effect for numbers!
     *
     * @param mixed &$value
     * @param DataModel_ValidationError[] &$errors[]
     *
     * @return bool
     */
    public function _validateData_test_required( &$value, &$errors ) {
        return true;
    }

	/**
	 * @return string
	 */
	public function getTechnicalDescription() {
		$res = "Type: ".$this->getType()." ";

		$res .= ", required: ".($this->is_required ? "yes":"no");

		if($this->is_ID) {
			$res .= ", is ID";
		}

		if($this->default_value) {
			$res .= ", default value: {$this->default_value}";
		}

		if($this->min_value) {
			$res .= ", min. value: {$this->min_value}";
		}

		if($this->max_value) {
			$res .= ", max. value: {$this->max_value}";
		}

		if($this->description) {
			$res .= "\n\n{$this->description}";
		}

		return $res;
	}

}