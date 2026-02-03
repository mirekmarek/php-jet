<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\Validators;

use Jet\BaseObject;
use Jet\Entity_Validator_Definition;
use Jet\Entity_Validator_Interface;
use Jet\Entity_Validator_Trait;
use Jet\Validator;
use Jet\Validator_Int;


class EntityTest_Entity_Sub1 extends BaseObject implements Entity_Validator_Interface
{
	use Entity_Validator_Trait;
	
	
	#[Entity_Validator_Definition(
		type: Validator::TYPE_INT,
		min_value: 10,
		max_value: 999,
		error_messages: [
			Validator_Int::ERROR_CODE_OUT_OF_RANGE => 'Number is out of range (0-999)'
		]
	)]
	protected int $int_invalid = 0;
	#[Entity_Validator_Definition(
		type: Validator::TYPE_INT,
		min_value: 10,
		max_value: 999,
		error_messages: [
			Validator_Int::ERROR_CODE_OUT_OF_RANGE => 'Number is out of range (0-999)'
		]
	)]
	protected int $int_valid = 50;
	

}