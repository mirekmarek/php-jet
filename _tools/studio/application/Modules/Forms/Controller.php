<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudioModule\Forms;

use Jet\AJAX;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Tr;
use Jet\UI_messages;
use JetStudio\JetStudio_Module_Controller;

class Controller extends JetStudio_Module_Controller
{
	protected function resolve(): string
	{
		$action = Http_Request::GET()->getString( 'action' );
		return $action ? : 'default';
	}
	
	public function default_Action() : void
	{
		$this->output('main');
	}
	
	public function generate_view_script_Action() : void
	{
		$class = Main::getCurrentClass();
		if($class) {
			AJAX::snippetResponse( $class->generateViewScript() );
		}
	}
	
	public function save_field_Action() : void
	{
		$property = Main::getCurrentProperty();
		
		$form = $property->getDefinitionForm();
		if(!$form) {
			return;
		}
		
		if($form->catchInput() && $form->validate()) {
			if($property->update( $form->getValues() )) {
				UI_messages::success( Tr::_( 'Saved ...' ) );
				
				Http_Headers::reload(unset_GET_params: ['action']);
			}
		} else {
			UI_messages::danger(
				Tr::_( 'There are some problems ... Please check the form.' )
			);
			
		}
	}
	
	public function save_sub_form_Action() : void
	{
		$property = Main::getCurrentProperty();
		
		$form = $property->getDefinitionForm();
		if(!$form) {
			return;
		}
		
		if($form->catchInput() && $form->validate()) {
			if($property->update( $form->getValues() )) {
				UI_messages::success( Tr::_( 'Saved ...' ) );
				
				Http_Headers::reload(unset_GET_params: ['action']);
			}
		} else {
			UI_messages::danger(
				Tr::_( 'There are some problems ... Please check the form.' )
			);
			
		}
	}
	
	public function save_sub_forms_Action() : void
	{
		$property = Main::getCurrentProperty();
		
		$form = $property->getDefinitionForm();
		if(!$form) {
			return;
		}
		
		if($form->catchInput() && $form->validate()) {
			if($property->update( $form->getValues() )) {
				UI_messages::success( Tr::_( 'Saved ...' ) );
				
				Http_Headers::reload(unset_GET_params: ['action']);
			}
		} else {
			UI_messages::danger(
				Tr::_( 'There are some problems ... Please check the form.' )
			);
			
		}
	}
	
	public function select_type_Action() : void
	{
		$property = Main::getCurrentProperty();
		
		$form = $property->getSetTypeForm();
		
		if($form->catchInput() && $form->validate()) {
			if($property->setType( $form->getValues() )) {
				UI_messages::success( Tr::_( 'Saved ...' ) );
				
				Http_Headers::reload(unset_GET_params: ['action']);
			}
		} else {
			UI_messages::danger(
				Tr::_( 'There are some problems ... Please check the form.' )
			);
		}
	}
}