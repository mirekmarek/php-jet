<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\Forms;

use Jet\BaseObject;
use Jet\Form_Definition;
use Jet\Form_Definition_Interface;
use Jet\Form_Definition_Trait;
use Jet\Form_Field;


class DefinitionTest_FormGenerator_Sub2 extends BaseObject implements Form_Definition_Interface
{
	use Form_Definition_Trait;
	
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		label: 'Some text',
	)]
	protected string $some_text = '';
	
	/**
	 * @return string
	 */
	public function getSomeText(): string
	{
		return $this->some_text;
	}
	
	/**
	 * @param string $some_text
	 */
	public function setSomeText( string $some_text ): void
	{
		$this->some_text = $some_text;
	}
	
	
}