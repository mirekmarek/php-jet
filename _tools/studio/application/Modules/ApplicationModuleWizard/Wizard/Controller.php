<?php

/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\ApplicationModuleWizard;

use Jet\BaseObject;
use Jet\Http_Request;
use Jet\MVC_View;

abstract class Wizard_Controller extends BaseObject
{
	protected MVC_View $view;
	protected Wizard $wizard;
	protected string $output = '';
	
	public function __construct( MVC_View $view, Wizard $wizard )
	{
		$this->view = $view;
		$this->wizard = $wizard;
	}
	
	
	public function handle() : string
	{
		$action = Http_Request::GET()->getString( 'wizard_action', 'default' );

		$method = $action.'_Action';
		
		$this->{$method}();
		
		return $this->output;
	}
	
	public function default_Action() : void
	{
		$this->output('main');
	}
	
	public function create_Action() : void
	{
		if(
			$this->wizard->catchSetupForm() &&
			$this->wizard->create()
		) {
			$this->wizard->redirectToModuleEditing();
		} else {
			$this->output('main');
		}
	}
	
	public function output( string $view_script ) : void
	{
		$this->output .= $this->view->render( $view_script );
	}
}