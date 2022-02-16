<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
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
	 * @var Form
	 */
	protected Form $form;
	
	/**
	 * @param Form $form
	 */
	public function __construct( Form $form )
	{
		$this->form = $form;
		$this->view_dir = $form->renderer()->getViewDir();
		$this->view_script = SysConf_Jet_Form_DefaultViews::get('Form', 'message');
	}
	
	
	/**
	 * @return Form
	 */
	public function getForm(): Form
	{
		return $this->form;
	}
	
}