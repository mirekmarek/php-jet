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
class Config_Definition_Property_Section extends Config_Definition_Property
{
	/**
	 * @var string
	 */
	protected string $_type = Config::TYPE_SECTION;

	/**
	 * @var string
	 */
	protected string $section_creator_method_name = '';

	/**
	 * @param ?array $definition_data
	 *
	 * @throws Config_Exception
	 */
	public function setUp( ?array $definition_data = null ): void
	{
		parent::setUp( $definition_data );

		if(
			$definition_data !== null &&
			!$this->section_creator_method_name
		) {
			throw new Config_Exception(
				$this->_configuration_class . '::' . $this->name . ': section_creator_method_name is not defined ',
				Config_Exception::CODE_DEFINITION_NONSENSE
			);

		}
	}

	/**
	 *
	 * @param array $value
	 * @param Config $config
	 *
	 * @return Config_Section
	 *
	 */
	public function prepareValue( mixed $value, Config $config ): Config_Section
	{
		/**
		 * @var Config_Section $section
		 */
		$section = $config->{$this->section_creator_method_name}( $value );
		$section->setConfig( $config );

		return $section;

	}

	/**
	 *
	 * @param mixed &$value
	 */
	protected function checkValueType( mixed &$value ): void
	{
	}

	/**
	 *
	 * @param mixed &$value
	 *
	 */
	protected function checkValue( mixed $value ): void
	{
	}
}