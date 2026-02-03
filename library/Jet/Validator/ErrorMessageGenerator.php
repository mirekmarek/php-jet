<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

abstract class Validator_ErrorMessageGenerator
{
	protected Validator $validator;
	
	public function setValidator( Validator $validator ): void
	{
		$this->validator = $validator;
	}
	
	
	
	public function getValidator(): Validator
	{
		return $this->validator;
	}
	
	/**
	 * @param string $error_code
	 * @param array<string,mixed> $error_data
	 * @return string
	 */
	abstract public function generateErrorMessage( string $error_code, array $error_data ) : string;
}