<?php
/**
 *
 * @copyright Copyright (c) 2011-2022 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

trait Validator_Part_Options_Trait
{
	public const ERROR_CODE_INVALID_VALUE = 'invalid_value';
	
	/**
	 * @var array<string|int|float>
	 */
	protected array $valid_options = [];
	
	/**
	 * @return array<string|int|float>
	 */
	public function getValidOptions(): array
	{
		return $this->valid_options;
	}
	
	/**
	 * @param array<string|int|float> $valid_options
	 * @return void
	 */
	public function setValidOptions( array $valid_options ): void
	{
		$this->valid_options = $valid_options;
	}

	
}