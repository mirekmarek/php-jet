<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class Validator_Null extends Validator
{
	protected static string $type = self::TYPE_NULL;
	
	/**
	 * @var array<string,string>
	 */
	protected array $error_messages = [
	];
	
	public function validate_value( mixed $value ) : bool
	{
		return true;
	}
	
	public function getErrorCodeScope(): array
	{
		return [];
	}
}