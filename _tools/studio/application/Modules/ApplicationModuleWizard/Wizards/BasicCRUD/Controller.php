<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\ApplicationModuleWizard\BasicCRUD;

use Jet\AJAX;
use Jet\Http_Request;
use Jet\MVC;
use JetStudio\JetStudio;
use JetStudioModule\ApplicationModuleWizard\Wizard_Controller;


class Controller extends Wizard_Controller
{
	public function generate_page_id_Action() : void
	{
		$name = Http_Request::GET()->getString( 'name' );
		
		$id = JetStudio::generateIdentifier( $name, function( $id ) {
			return (bool)MVC::getPage( $id );
		} );
		
		AJAX::commonResponse(
			[
				'id' => $id
			]
		);
	
	}
	
	public function select_data_model_Action() : void
	{
		$this->wizard->catchSelectModelForm();
	}
}