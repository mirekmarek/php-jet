<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudioModule\Bases;

use JetStudio\JetStudio_Module_Controller;

use Jet\AJAX;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Locale;
use Jet\Tr;
use Jet\UI_messages;
use JetStudio\JetStudio;

class Controller extends JetStudio_Module_Controller
{
	protected null|bool|MVCBase $current = null;
	
	protected function resolve() : string
	{
		$action = Http_Request::GET()->getString('action');

		
		if(
			$action=='create_new_base' ||
			$action=='generate_id'
		) {
			return $action;
		}
		
		$this->current = Main::getCurrentBase();
		
		if( !$this->current ) {
			return 'default';
		}
		
		return match($action) {
			'locale_add' => 'locale_add',
			'locale_sort' => 'locale_sort',
			default => 'edit'
		};
	}
	
	public function default_Action() : void
	{
		$this->output('dashboard');
	}
	
	public function create_new_base_Action() : void
	{
		$form = MVCBase::getCreateForm();
		$ok = false;
		$data = [];
		
		if($form->catchInput()) {
			if( ($new_base = MVCBase::catchCreateForm()) ) {
				
				if( $new_base->create() ) {
					
					UI_messages::success(
						Tr::_( 'Base <strong>%key%</strong> has been created', [
							'key' => $new_base->getName()
						] )
					);
					
					Http_Headers::movedTemporary( Main::getActionUrl('',[], $new_base->getId()) );
				}
				
			}
			
		}
		
		$this->output('create');
	}
	
	public function locale_add_Action() : void
	{
		if(
			$this->current &&
			($new_ld = $this->current->catchAddLocaleForm())
		) {
			if( $this->current->save() ) {
				$locale = new Locale( $this->current->getAddLocaleForm()->getField( 'locale' )->getValue() );
				
				UI_messages::success( Tr::_( 'Locale <b>%locale%</b> has been added', [
					'locale' => $locale->getName()
				] ) );
				
				Http_Headers::reload( [], ['action'] );
			}
		}
	}
	
	public function locale_sort_Action() : void
	{
		if(
			$this->current &&
			$this->current->catchSortLocalesForm()
		) {
			if( $this->current->save() ) {
				UI_messages::success( Tr::_( 'Saved ...' ) );
				Http_Headers::reload( [], ['action'] );
			}
			
		}
	}
	
	public function edit_Action() : void
	{
		if($this->current->getEditForm()->catchInput()) {
			if( $this->current->catchEditForm() ) {
				if( $this->current->save() ) {
					UI_messages::success( Tr::_( 'Saved ...' ) );
				}
				
				Http_Headers::reload( [], ['action'] );
				
			} else {
				UI_messages::danger(
					Tr::_( 'There are some problems ... Please check the form.' )
				);
			}
		}
		
		$this->output('edit');
	}
	
	public function generate_id_Action() : void
	{
		$name = Http_Request::GET()->getString( 'name' );
		
		$id = JetStudio::generateIdentifier( $name, function( $id ) {
			return MVCBase::exists( $id );
		} );
		
		
		AJAX::commonResponse(
			[
				'id' => $id
			]
		);
	}
}