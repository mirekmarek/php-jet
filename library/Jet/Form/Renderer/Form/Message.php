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
class Form_Renderer_Form_Message extends Form_Renderer_Single
{
	
	
	/**
	 * @param Form $form
	 */
	public function __construct( Form $form )
	{
		$this->form = $form;
		$this->view_script = $form->getMessageViewScript();
	}
	
	
	/**
	 *
	 */
	protected function generateTagAttributes_Standard() : void
	{
	}
	
}