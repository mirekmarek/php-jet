<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudioModule\Setup;

use Jet\Http_Request;
use JetStudio\JetStudio;
use JetStudio\JetStudio_Module;
use JetStudio\JetStudio_Module_Controller;
use JetStudio\JetStudio_Module_Service_SetupModule;


class Controller extends JetStudio_Module_Controller
{
	public function default_Action() : void
	{
		/**
		 * @var JetStudio_Module_Service_SetupModule[]|JetStudio_Module[] $modules
		 */
		$modules = JetStudio::getServiceModules( JetStudio_Module_Service_SetupModule::class );
		
		$GET = Http_Request::GET();
		
		$selected_module_name = $GET->getString(
			key: 'id',
			default_value: '',
			valid_values: array_keys( $modules )
		);
		$selected_module = $modules[$selected_module_name]??null;
		
		
		$this->view->setVar( 'modules', $modules );
		$this->view->setVar( 'selected_module', $selected_module );
		
		
		$this->output('main');
	}
}