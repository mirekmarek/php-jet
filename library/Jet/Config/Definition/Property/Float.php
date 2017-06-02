<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Config_Definition_Property_Float extends Config_Definition_Property
{
	/**
	 * @var string
	 */
	protected $_type = Config::TYPE_FLOAT;
	/**
	 * @var float
	 */
	protected $default_value = 0.0;

	/**
	 * @var float
	 */
	protected $min_value = null;

	/**
	 * @var float
	 */
	protected $max_value = null;

	/**
	 * @param array|null $definition_data
	 *
	 * @throws Config_Exception
	 */
	public function setUp( array $definition_data = null )
	{
		parent::setUp( $definition_data );

		if( $this->min_value!==null ) {
			$this->form_field_min_value = $this->min_value;
		}

		if( $this->max_value!==null ) {
			$this->form_field_max_value = $this->max_value;
		}

		if( $this->form_field_type===null ) {
			$this->form_field_type = Form::TYPE_FLOAT;
		}
	}

	/**
	 * @return float|null
	 */
	public function getMinValue()
	{
		return $this->min_value;
	}

	/**
	 * @param float $min_value
	 */
	public function setMinValue( $min_value )
	{
		$this->min_value = (float)$min_value;
		$this->form_field_min_value = $this->min_value;
	}

	/**
	 * @return float|null
	 */
	public function getMaxValue()
	{
		return $this->max_value;
	}

	/**
	 * @param float $max_value
	 */
	public function setMaxValue( $max_value )
	{
		$this->max_value = (float)$max_value;
		$this->form_field_max_value = $this->max_value;
	}

	/**
	 * @param mixed &$value
	 */
	public function checkValueType( &$value )
	{
		$value = (float)$value;
	}


	/**
	 * Column value test - checks range
	 *
	 * @param mixed &$value
	 *
	 * @throws Config_Exception
	 * @return bool
	 */
	protected function _validateProperties_test_value( &$value )
	{
		if(
			$this->min_value===null &&
			$this->max_value===null
		) {
			return true;
		}

		if(
			$this->min_value!==null &&
			$value<$this->min_value
		) {
			throw new Config_Exception(
				'Configuration property '.$this->_configuration_class.'::'.$this->name.' value is under the minimal value (by definition). Minimal value: '.$this->min_value.', current value: '.$value,
				Config_Exception::CODE_CONFIG_CHECK_ERROR
			);
		}

		if(
			$this->max_value!==null &&
			$value>$this->max_value
		) {
			throw new Config_Exception(
				'Configuration property '.$this->_configuration_class.'::'.$this->name.' value is above the maximum value (by definition). Maximum value: '.$this->max_value.', current value: '.$value,
				Config_Exception::CODE_CONFIG_CHECK_ERROR
			);
		}

		return true;
	}

}