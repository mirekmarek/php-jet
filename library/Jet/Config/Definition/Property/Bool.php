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
class Config_Definition_Property_Bool extends Config_Definition_Property
{
	/**
	 * @var string
	 */
	protected string $_type = Config::TYPE_BOOL;

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
		$value = (bool)$value;
	}

	/**
	 *
	 * @param mixed $value
	 *
	 */
	protected function checkValue( mixed $value ): void
	{
	}
}