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
class Config_Definition_Property_String extends Config_Definition_Property
{
	/**
	 * @var string
	 */
	protected string $_type = Config::TYPE_STRING;

	/**
	 * @var string|null
	 */
	protected string|null $validation_regexp = null;

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
	 * @param mixed &$value
	 */
	protected function checkValueType( mixed &$value ): void
	{
		$value = (string)$value;
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
			$this->validation_regexp &&
			!preg_match( $this->validation_regexp, $value )
		) {
			throw new Config_Exception(
				'Configuration property ' . $this->_configuration_class . '::' . $this->name . ' has invalid format. Valid regexp: ' . $this->validation_regexp . ', current value: ' . $value,
				Config_Exception::CODE_CONFIG_CHECK_ERROR
			);
		}

	}

	/**
	 * @return string|null
	 */
	public function getValidationRegexp(): string|null
	{
		return $this->validation_regexp;
	}

	/**
	 * @param string $validation_regexp
	 */
	public function setValidationRegexp( string $validation_regexp ): void
	{
		$this->validation_regexp = $validation_regexp;
	}


}