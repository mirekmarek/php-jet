<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


interface Entity_Validator_Interface
{
	
	/**
	 * @return Entity_Validator_Definition_PropertyValidator[]
	 */
	public function getPropertyValidatorsDefinition() : array;
	
	/**
	 *
	 * @param array<string> $only_properties=[]
	 * @param array<string> $exclude_properties=[]
	 *
	 * @return Entity_Validator
	 * @throws Entity_Validator_Definition_Exception
	 *
	 */
	public function createValidator( array $only_properties=[], array $exclude_properties=[] ): Entity_Validator;

}