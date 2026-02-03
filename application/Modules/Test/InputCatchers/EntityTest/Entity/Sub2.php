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


class EntityTest_Entity_Sub2 extends BaseObject implements Entity_InputCatcher_Interface
{
	use Entity_InputCatcher_Trait;
	
	#[Entity_InputCatcher_Definition(
		type: InputCatcher::TYPE_INT,
	)]
	protected int $int_value = 0;
	
}