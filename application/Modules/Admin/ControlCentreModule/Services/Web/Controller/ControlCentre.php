<?php
/**
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license EUPL 1.2  https://eupl.eu/1.2/en/
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
		
		$homepage = $main->getHomepageBySpecificationId( $this->getSpecificationId() );
		
		$this->handler = new Handler($homepage->getBase(), $homepage->getLocale());
		
		$this->view->setVar('form', $this->handler->getEditForm() );
		$this->view->setVar('services', $this->handler->getList() );
		
		$this->output('default');
	}
	
}