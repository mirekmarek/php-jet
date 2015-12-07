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
	protected $form_field_type = Form::TYPE_FLOAT;

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
			$this->form_field_options['min_value'] = $this->min_value;
		}
		if($this->max_value!==null) {
			$this->form_field_options['max_value'] = $this->max_value;
		}

		return $this->form_field_options;
	}


	/**
	 * Column value test - checks range
	 *
	 * @param mixed &$property
	 * @param DataModel_Validation_Error &$errors
	 *
	 * @return bool
	 */
	public function _validatePropertyValue_test_value( &$property, &$errors ) {
		if($this->min_value===null && $this->max_value===null) {
			return true;
		}

		if(
			$this->min_value!==null &&
			$property<$this->min_value
		) {
			$errors[] = new DataModel_Validation_Error(
					DataModel_Validation_Error::CODE_OUT_OF_RANGE,
					$this,
					$property
				);

			return false;
		}

		if(
			$this->max_value!==null &&
			$property>$this->max_value
		) {
			$errors[] = new DataModel_Validation_Error(
					DataModel_Validation_Error::CODE_OUT_OF_RANGE,
					$this,
					$property
				);

			return false;
		}

		return true;
	}

    /**
     * Property required test
     * has no effect for numbers!
     *
     * @param mixed &$property
     * @param DataModel_Validation_Error[] &$errors[]
     *
     * @return bool
     */
    public function _validatePropertyValue_test_required( &$property, &$errors ) {
        return true;
    }

	/**
	 * @return string
	 */
	public function getTechnicalDescription() {
		$res = 'Type: '.$this->getType().' ';

		$res .= ', required: '.($this->is_required ? 'yes':'no');

		if($this->is_ID) {
			$res .= ', is ID';
		}

		if($this->default_value) {
			$res .= ', default value: '.$this->default_value;
		}

		if($this->min_value) {
			$res .= ', min. value: '.$this->min_value;
		}

		if($this->max_value) {
			$res .= ', max. value: '.$this->max_value;
		}

		if($this->description) {
			$res .= JET_EOL.JET_EOL.$this->description;
		}

		return $res;
	}

}