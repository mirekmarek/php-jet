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
class Config_Definition_Property_Int extends Config_Definition_Property
{
	/**
	 * @var string
	 */
	protected string $_type = Config::TYPE_INT;

	/**
	 * @var int|null
	 */
	protected int|null $min_value = null;
	/**
	 * @var int|null
	 */
	protected int|null $max_value = null;

	/**
	 * @param ?array $definition_data
	 *
	 * @throws Config_Exception
	 */
	public function setUp( ?array $definition_data = null ): void
	{
		parent::setUp( $definition_data );
	}

	/**
	 * @return int|null
	 */
	public function getMinValue(): int|null
	{
		return $this->min_value;
	}

	/**
	 * @param int $min_value
	 */
	public function setMinValue( int $min_value ): void
	{
		$this->min_value = $min_value;
	}

	/**
	 * @return int|null
	 */
	public function getMaxValue(): int|null
	{
		return $this->max_value;
	}

	/**
	 * @param int $max_value
	 */
	public function setMaxValue( int $max_value ): void
	{
		$this->max_value = $max_value;
	}

	/**
	 * @param mixed &$value
	 */
	protected function checkValueType( mixed &$value ): void
	{
		$value = (int)$value;
	}

	/**
	 * Column value test - checks range
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
				'Configuration property ' . $this->_configuration_class . '::' . $this->name . ' value ' . $value . ' is under the minimal value. Minimal value: ' . $this->min_value . ', current value: ' . $value,
				Config_Exception::CODE_CONFIG_CHECK_ERROR
			);
		}

		if(
			$this->max_value !== null &&
			$value > $this->max_value
		) {
			throw new Config_Exception(
				'Configuration property ' . $this->_configuration_class . '::' . $this->name . ' value is above the maximum value. Maximum value: ' . $this->max_value . ', current value: ' . $value,
				Config_Exception::CODE_CONFIG_CHECK_ERROR
			);
		}

	}

}