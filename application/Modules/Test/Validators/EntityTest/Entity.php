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

use Jet\Locale;
use Jet\Entity_Validator_Interface;
use Jet\Entity_Validator_Trait;
use Jet\Validator;
use Jet\Validator_Int;


class EntityTest_Entity extends BaseObject implements Entity_Validator_Interface
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


	
	#[Entity_Validator_Definition(
		is_sub_validators: true
	)]
	protected array $sub_entities = [];
	
	#[Entity_Validator_Definition(
		is_sub_validator: true
	)]
	protected EntityTest_Entity_Sub2 $sub_entity;
	
	
	public static function getLocales() : array
	{
		$res = [];
		
		$res[] = new Locale('cs_CZ');
		$res[] = new Locale('en_EU');
		$res[] = new Locale('de_DE');
		$res[] = new Locale('sk_SK');
		
		return $res;
	}
	

	public function __construct()
	{
		foreach(static::getLocales() as $locale) {
			$this->sub_entities[$locale->toString()] = new EntityTest_Entity_Sub1();
		}
		
		$this->sub_entity = new EntityTest_Entity_Sub2();
	}
	
}