<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Admin\ControlCentreModule\Services\General;

use JetApplication\Admin_ControlCentre_Module_Controller;


class Controller_ControlCentre extends Admin_ControlCentre_Module_Controller
{
	protected Handler $handler;

	public function default_Action() : void
	{
		$this->handler = new Handler();
		
		$this->view->setVar('form', $this->handler->getEditForm() );
		$this->view->setVar('services', $this->handler->getList() );
		
		$this->output('default');
	}
	
	
}