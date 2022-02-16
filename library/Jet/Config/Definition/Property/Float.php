<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	protected string $_type = Config::TYPE_FLOAT;

	/**
	 * @var float|null
	 */
	protected float|null $min_value = null;

	/**
	 * @var float|null
	 */
	protected float|null $max_value = null;

	/**
	 * @param array|null $definition_data
	 *
	 * @throws Config_Exception
	 */
	public function setUp( array $definition_data = null ): void
	{
		parent::setUp( $definition_data );
	}

	/**
	 * @return float|null
	 */
	public function getMinValue(): float|null
	{
		return $this->min_value;
	}

	/**
	 * @param float $min_value
	 */
	public function setMinValue( float $min_value ) : void
	{
		$this->min_value = $min_value;
	}

	/**
	 * @return float|null
	 */
	public function getMaxValue(): float|null
	{
		return $this->max_value;
	}

	/**
	 * @param float $max_value
	 */
	public function setMaxValue( float $max_value ) : void
	{
		$this->max_value = $max_value;
	}

	/**
	 * @param mixed &$value
	 */
	protected function checkValueType( mixed &$value ): void
	{
		$value = (float)$value;
	}


	/**
	 *
	 * @param mixed $value
	 *
	 * @throws Config_Exception
	 */
	protected function checkValue( mixed $value ): void
	{

		if(
			$this->min_value !== null &&
			$value < $this->min_value
		) {
			throw new Config_Exception(
				'Configuration property ' . $this->_configuration_class . '::' . $this->name . ' value is under the minimal value (by definition). Minimal value: ' . $this->min_value . ', current value: ' . $value,
				Config_Exception::CODE_CONFIG_CHECK_ERROR
			);
		}

		if(
			$this->max_value !== null &&
			$value > $this->max_value
		) {
			throw new Config_Exception(
				'Configuration property ' . $this->_configuration_class . '::' . $this->name . ' value is above the maximum value (by definition). Maximum value: ' . $this->max_value . ', current value: ' . $value,
				Config_Exception::CODE_CONFIG_CHECK_ERROR
			);
		}

	}

}