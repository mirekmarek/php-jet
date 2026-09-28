<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Admin\ControlCentre;


use Jet\Http_Request;
use Jet\MVC_Controller_Default;
use Jet\UI;
use Jet\Navigation_Breadcrumb;
use JetApplication\Admin_ControlCentre;
use JetApplication\Admin_ControlCentre_Module_Interface;


class Controller_Main extends MVC_Controller_Default
{

	/**
	 *
	 */
	public function default_Action() : void
	{
		$modules = Admin_ControlCentre::getModuleList();

		$GET = Http_Request::GET();
		
		$selected_module_name = $GET->getString(
			key: 'id',
			default_value: '',
			valid_values: array_keys( $modules )
		);
		$selected_module = $modules[$selected_module_name]??null;
		
		if($selected_module) {
			/**
			 * @var Admin_ControlCentre_Module_Interface $selected_module
			 */
			Navigation_Breadcrumb::addURL( UI::icon( $selected_module->getControlCentreIcon() ).' '.$selected_module->getControlCentreTitle() );
			
			$this->view->setVar( 'selected_module', $selected_module );
			
			if($selected_module->getControlCentreSpecificationMode()) {
				
				$specifications = $selected_module->getControlCentreSpecificationList();
				
				$selected_specification_id = $GET->getString(
					key: 'specification',
					default_value: array_keys( $specifications )[0],
					valid_values: array_keys( $specifications )
				);
				
				$this->view->setVar( 'selected_specification_id', $selected_specification_id );
			}

		}
		
		
		$this->output('default');
	}
}