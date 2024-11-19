<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudioModule\Menus;

use JetStudio\JetStudio_Module_Controller;

use Jet\AJAX;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Tr;
use Jet\UI_messages;
use JetStudio\JetStudio;

class Controller extends JetStudio_Module_Controller
{
	protected function resolve(): string
	{
		$action = Http_Request::GET()->getString( 'menus_action' );
		return $action ? : 'default';
	}
	
	public function default_Action() : void
	{
		$this->output('main');
	}

	public function item_add_Action() : void
	{
		$form = Menu_Item::getCreateForm();
		$ok = false;
		$data = [];
		
		$set = Main::getCurrentMenuSet();
		$menu = Main::getCurrentMenu();
		
		if( !$set || !$menu ) {
			die();
		}
		
		if( ($new_item = Menu_Item::catchCreateForm()) ) {
			
			$menu->addMenuItem( $new_item );
			
			if( $set->save() ) {
				$ok = true;
				
				UI_messages::success(
					Tr::_( 'Menu item <strong>%item%</strong> has been created', [
						'item' => $new_item->getLabel() . ' (' . $new_item->getId() . ')'
					] )
				);
				
				$data = [
					'new_menu_item_id' => $new_item->getId()
				];
			} else {
				$form->setCommonMessage( implode( '', UI_messages::get() ) );
			}
			
		}
		
		AJAX::operationResponse(
			$ok,
			[
				$form->getId() . '_form_area' => $this->view->render( 'item/create/form' )
			],
			$data
		);
	}
	
	public function item_delete_Action() : void
	{
		$set = Main::getCurrentMenuSet();
		$menu = Main::getCurrentMenu();
		$item = Main::getCurrentMenuItem();
		
		if( !$set || !$menu || !$item ) {
			die();
		}
		
		$menu->deleteMenuItem( $item->getId() );
		
		if( $set->save() ) {
			UI_messages::info( Tr::_( 'Menu item <b>%name%</b> has been deleted', [
				'name' => $item->getLabel()
			] ) );
			
			Http_Headers::reload( [], [
				'action',
				'item'
			] );
		}
	}
	
	public function item_edit_Action() : void
	{
		$set = Main::getCurrentMenuSet();
		$menu = Main::getCurrentMenu();
		$item = Main::getCurrentMenuItem();
		
		if( !$set || !$menu || !$item ) {
			die();
		}
		
		
		if( $item->catchEditForm() ) {
			//$menu->sortItems();
			
			if( $set->save() ) {
				UI_messages::success( Tr::_( 'Saved ...' ) );
				
				Http_Headers::reload( [], ['menus_action'] );
			}
			
		} else {
			UI_messages::danger(
				Tr::_( 'There are some problems ... Please check the form.' )
			);
		}
		
	}
	
	public function item_generate_id_Action() : void
	{
		$name = Http_Request::GET()->getString( 'name' );
		
		$id = JetStudio::generateIdentifier( $name, function( $id ) {
			return Main::menuItemExists( $id );
		} );
		
		AJAX::commonResponse(
			[
				'id' => $id
			]
		);
	}
	
	public function menu_add_Action() : void
	{
		$form = Menu::getCreateForm();
		
		$set = Main::getCurrentMenuSet();
		if( !$set ) {
			die();
		}
		
		$ok = false;
		$data = [];
		
		if(
			($new_menu = Menu::catchCreateForm())
		) {
			
			$set->appendMenu( $new_menu );
			
			if( $set->save() ) {
				$ok = true;
				
				UI_messages::success(
					Tr::_( 'Menu <strong>%menu%</strong> has been created', [
						'menu' => $new_menu->getLabel()
					] )
				);
				
				$data = [
					'new_menu_id' => $new_menu->getId()
				];
			} else {
				$message = implode( '', UI_messages::get() );
				
				$form->setCommonMessage( $message );
			}
			
		}
		
		AJAX::operationResponse(
			$ok,
			[
				$form->getId() . '_form_area' => $this->view->render( 'menu/create/form' )
			],
			$data
		);
	}
	
	public function menu_delete_Action() : void
	{
		$set = Main::getCurrentMenuSet();
		$current = Main::getCurrentMenu();
		
		if(
			$set &&
			$current
		) {
			$menu = $set->deleteMenu( $current->getId() );
			
			
			if( $menu && $set->save() ) {
				UI_messages::info( Tr::_( 'Menu <b>%name%</b> has been deleted', [
					'name' => $menu->getLabel()
				] ) );
				
				Http_Headers::reload( [], [
					'menus_action',
					'menu'
				] );
			}
			
		}
	
	}
	
	public function menu_edit_Action() : void
	{
		$set = Main::getCurrentMenuSet();
		
		if( $set ) {
			$current = Main::getCurrentMenu();
			
			if(
				$current &&
				$current->catchEditForm()
			) {
				$set->sortMenus();
				
				if( $set->save() ) {
					UI_messages::success( Tr::_( 'Saved ...' ) );
					
					Http_Headers::reload( [], ['menus_action'] );
				}
				
			} else {
				UI_messages::danger(
					Tr::_( 'There are some problems ... Please check the form.' )
				);
			}
			
		}
	}
	
	public function menu_generate_id_Action() : void
	{
		$name = Http_Request::GET()->getString( 'name' );
		
		$id = JetStudio::generateIdentifier( $name, function( $id ) {
			return Main::menuExists( $id );
		} );
		
		AJAX::commonResponse(
			[
				'id' => $id
			]
		);
	}
	
	public function set_add_Action() : void
	{
		
		$form = MenuSet::getCreateForm();
		$ok = false;
		$data = [];
		
		if( ($new_set = MenuSet::catchCreateForm()) ) {
			
			if( $new_set->save() ) {
				$ok = true;
				
				UI_messages::success(
					Tr::_( 'Menu set <strong>%set%</strong> has been created', [
						'set' => $new_set->getName()
					] )
				);
				
				$data = [
					'new_set' => $new_set->getName()
				];
			} else {
				$message = implode( '', UI_messages::get() );
				
				$form->setCommonMessage( $message );
			}
		}
		
		AJAX::operationResponse(
			$ok,
			[
				'set_create_form_area' => $this->view->render( 'set/create/form' )
			],
			$data
		);
	}
	
	public function set_edit_Action() : void
	{
		$current = Main::getCurrentMenuSet();
		
		if(
			$current &&
			$current->catchEditForm()
		) {
			if( $current->save() ) {
				UI_messages::success( Tr::_( 'Saved ...' ) );
				
				Http_Headers::reload( [], ['menus_action'] );
			}
			
		} else {
			UI_messages::danger(
				Tr::_( 'There are some problems ... Please check the form.' )
			);
		}
	}

}