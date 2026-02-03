<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class Validator_RegExp extends Validator implements Validator_Part_RegExp_Interface
{
	use Validator_Part_RegExp_Trait;
	
	protected static string $type = self::TYPE_REGEXP;
	
	
	public function getErrorCodeScope(): array
	{
		$codes = [];
		
		if( $this->is_required ) {
			$codes[] = static::ERROR_CODE_EMPTY;
		}
		if( $this->validation_regexp ) {
			$codes[] = static::ERROR_CODE_INVALID_FORMAT;
		}
		
		return $codes;
	}

}