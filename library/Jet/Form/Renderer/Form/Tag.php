<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Form_Renderer_Form_Tag extends Form_Renderer_Pair
{
	
	/**
	 *
	 * @param Form $form
	 */
	public function __construct( Form $form )
	{
		$this->form = $form;
		$this->view_script_start = $form->getStartViewScript();
		$this->view_script_end = $form->getEndViewScript();
	}
	
	
	/**
	 *
	 */
	public function generateTagAttributes_Standard() : void
	{
		$form = $this->form;
		
		$this->tag_attributes['name'] = $form->getName();
		$this->tag_attributes['id'] = $form->getId();
		$this->tag_attributes['method'] = $form->getMethod();
		
		
		if($form->getAction()) {
			$this->tag_attributes['action'] = $form->getAction();
		}
		if($form->getTarget()) {
			$this->tag_attributes['target'] = $form->getTarget();
		}
		if($form->getEnctype()) {
			$this->tag_attributes['enctype'] = $form->getEnctype();
		}
		if($form->getAcceptCharset()) {
			$this->tag_attributes['accept-charset'] = $form->getAcceptCharset();
		}
		if($form->getNovalidate()) {
			$this->tag_attributes['novalidate'] = 'novalidate';
		}
		if(!$form->getAutocomplete()) {
			$this->tag_attributes['autocomplete'] = 'off';
		}
	}
	
}