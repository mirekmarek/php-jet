<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\InputCatchers;

use Jet\BaseObject;
use Jet\Entity_InputCatcher_Definition;
use Jet\Entity_InputCatcher_Interface;
use Jet\Entity_InputCatcher_Trait;

use Jet\InputCatcher;

class EntityTest_Entity extends BaseObject implements Entity_InputCatcher_Interface
{
	
	use Entity_InputCatcher_Trait;

	#[Entity_InputCatcher_Definition(
		type: InputCatcher::TYPE_INT,
	)]
	protected int $int_value = 0;

	
	#[Entity_InputCatcher_Definition(
		is_sub_input_catchers: true,
		factory_method_name: 'catchInput_Factory_sub_entities'
	)]
	protected array $sub_entities = [];
	
	#[Entity_InputCatcher_Definition(
		is_sub_input_catcher: true,
		factory_method_name: 'catchInput_Factory_sub_entity'
	)]
	protected EntityTest_Entity_Sub2 $sub_entity;
	
	protected function catchInput_Factory_sub_entity() : void
	{
		$this->sub_entity = new EntityTest_Entity_Sub2();
	}
	
	
	protected function catchInput_Factory_sub_entities( array $array_keys ) : void
	{
		$this->sub_entities = [];
		foreach($array_keys as $key) {
			$this->sub_entities[$key] = new EntityTest_Entity_Sub1();
		}
	}
	
}