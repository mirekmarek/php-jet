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


class DefinitionTest_FormGenerator_Sub1 extends BaseObject implements Form_Definition_Interface
{
	use Form_Definition_Trait;
	
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		label: 'Title',
	)]
	protected string $title = '';
	
	#[Form_Definition(
		type: Form_Field::TYPE_TEXTAREA,
		label: 'Text',
	)]
	protected string $text = '';
	
	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}
	
	/**
	 * @param string $title
	 */
	public function setTitle( string $title ): void
	{
		$this->title = $title;
	}
	
	/**
	 * @return string
	 */
	public function getText(): string
	{
		return $this->text;
	}
	
	/**
	 * @param string $text
	 */
	public function setText( string $text ): void
	{
		$this->text = $text;
	}
	
	
}