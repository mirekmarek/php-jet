<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Admin\ControlCentreModule\Services\Web;

use JetApplication\Admin_ControlCentre_Module_Controller;

class Controller_ControlCentre extends Admin_ControlCentre_Module_Controller
{
	protected Handler $handler;

	public function default_Action() : void
	{
		/**
		 * @var Main $main
		 */
		$main = $this->module;
		
		$homepage = $main->getBaseBySpecificationId( $this->getSpecificationId() );
		
		$this->handler = new Handler($homepage->getBase(), $homepage->getLocale());
		
		$this->view->setVar('form', $this->handler->getEditForm() );
		$this->view->setVar('services', $this->handler->getList() );
		
		$this->output('default');
	}
	
}