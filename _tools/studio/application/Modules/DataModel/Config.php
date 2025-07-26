<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\DataModel;

use Jet\Config_Definition;
use Jet\Form;
use Jet\Form_Definition;
use Jet\Form_Field;
use JetStudio\JetStudio_Module_Config;
use Jet\Config as JetConfig;

#[Config_Definition(
	name: 'DataModel'
)]
class Config extends JetStudio_Module_Config {
	
	#[Config_Definition(
		type: JetConfig::TYPE_BOOL,
		is_required: false
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_CHECKBOX,
		label: 'Add doc blocks always'
	)]
	protected bool $add_doc_blocks_always = false;
	
	#[Config_Definition(
		type: JetConfig::TYPE_BOOL,
		is_required: false
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_CHECKBOX,
		label: 'Prefer property hooks'
	)]
	protected bool $prefer_property_hooks = false;
	
	public function getAddDocBlocksAlways(): bool
	{
		return $this->add_doc_blocks_always;
	}
	
	public function setAddDocBlocksAlways( bool $add_doc_blocks_always ): void
	{
		$this->add_doc_blocks_always = $add_doc_blocks_always;
	}
	
	public function getPreferPropertyHooks(): bool
	{
		return $this->prefer_property_hooks;
	}
	
	public function getSetupForm() : Form
	{
		if( PHP_VERSION_ID < 80400 ) {
			$this->prefer_property_hooks = false;
		}
		
		$form = parent::getSetupForm();
		
		if( PHP_VERSION_ID < 80400 ) {
			$form->field('prefer_property_hooks')->setIsReadonly(true);
		}
		
		return $form;
	}
	
	public function setPreferPropertyHooks( bool $prefer_property_hooks ): void
	{
		$this->prefer_property_hooks = $prefer_property_hooks;
	}
	
	
}