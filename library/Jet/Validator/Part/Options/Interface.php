<?php
/**
 *
 * @copyright Copyright (c) 2011-2022 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

interface Validator_Part_Options_Interface
{
	/**
	 * @return array<string|int|float>
	 */
	public function getValidOptions(): array;
	
	/**
	 * @param array<string|int|float> $valid_options
	 * @return void
	 */
	public function setValidOptions( array $valid_options ): void;

}