<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Validator;

class Validator_Array extends Validator {
	
	protected static string $type = 'array';
	
	public function validate_value( mixed $value ): bool
	{
		return true;
	}
	
	public function getErrorCodeScope(): array
	{
		return [];
	}
}