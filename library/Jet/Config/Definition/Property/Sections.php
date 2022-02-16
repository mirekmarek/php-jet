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
class Config_Definition_Property_Sections extends Config_Definition_Property
{
	/**
	 * @var string
	 */
	protected string $_type = Config::TYPE_SECTIONS;

	/**
	 * @var string
	 */
	protected string $section_creator_method_name = '';

	/**
	 * @param ?array $definition_data
	 *
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
	 * @return Config_Definition_Property_Section[]
	 *
	 */
	public function prepareValue( mixed $value, Config $config ): array
	{

		$sections = [];
		foreach( $value as $name => $data ) {
			$sections[$name] = $config->{$this->section_creator_method_name}( $data );
			$sections[$name]->setConfig( $config );
		}

		return $sections;
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
	 * @param mixed $value
	 */
	protected function checkValue( mixed $value ): void
	{
	}
}