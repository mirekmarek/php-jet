<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\Pages;

use Jet\AJAX;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Tr;
use Jet\UI_messages;
use JetStudio\JetStudio;
use JetStudio\JetStudio_Module_Controller;

class Controller extends JetStudio_Module_Controller {
	
	protected function resolve() : string
	{
		
		$action = Http_Request::GET()->getString('pages_action', 'default');
		return match ($action) {
			'content_delete',
			'add',
			'content_add',
			'edit',
			'generate_id',
			'get_module_controller_actions',
			'get_module_controllers',
			=> $action,
			default => 'default'
		} ;
		
	}
	
	public function default_Action() : void
	{
		$this->output('main');
	}
	
	public function content_delete_Action() : void
	{
		$current = Main::getCurrentPage();
		
		if(
			$current &&
			$current->catchDeleteContentForm()
		) {
			
			if( $current->save() ) {
				UI_messages::info( Tr::_( 'Content has been deleted' ) );
				
				Http_Headers::reload( [], ['pages_action'] );
			}
		}
	}
	
	public function content_add_Action() : void
	{
		$page = Main::getCurrentPage();
		

		
		$this->view->setVar( 'page', $page );
		
		$ok = false;
		$data = [];
		$snippets = [];
		
		if(
			$page &&
			($new_content = $page->catchContentCreateForm())
		) {
			$page->addContent( $new_content );
			$form = $page->getContentCreateForm();
			
			if( $page->save() ) {
				$ok = true;
				
				$form->setCommonMessage(
					UI_messages::createSuccess(
						Tr::_( 'New content has been created' )
					)
				);
				
				$snippets['content_list_area'] = $this->view->render( 'page/content/edit/form/list' );
			} else {
				$form->setCommonMessage( implode( '', UI_messages::get() ) );
			}
			
			
		}
		
		
		$snippets['content_create_form_area'] = $this->view->render( 'page/content/create/form' );
		
		AJAX::operationResponse(
			$ok,
			$snippets,
			$data
		);
		
	}
	
	public function add_Action() : void
	{
		$form = Page::getCreateForm();
		$ok = false;
		$data = [];
		
		if( ($new_page = Page::catchCreateForm()) ) {
			
			if( $new_page->save() ) {
				$ok = true;
				
				UI_messages::success(
					Tr::_( 'Page <strong>%key%</strong> has been created', [
						'key' => $new_page->getName()
					] )
				);
				
				$data = [
					'new_page_id' => $new_page->getId()
				];
			} else {
				Page::getCreateForm()->setCommonMessage( implode('', UI_messages::get()) );
			}
			
		}
		
		AJAX::operationResponse(
			$ok,
			[
				$form->getId() . '_form_area' => $this->view->render( 'page/create/form' )
			],
			$data
		);
		
	}
	
	public function edit_Action() : void
	{
		$current = Main::getCurrentPage();
		
		$what = Main::whatToEdit();
		
		$res = false;
		if( $current ) {
			$res = match ($what) {
				'main' => $current->catchEditForm_main(),
				'content' => $current->catchEditForm_content(),
				'static_content' => $current->catchEditForm_static_content(),
				'callback' => $current->catchEditForm_callback(),
			};
		}
		
		if( $res ) {
			if( $current->save() ) {
				UI_messages::success( Tr::_( 'Saved ...' ) );
			}
			
			Http_Headers::reload( [], ['pages_action'] );
			
		} else {
			UI_messages::danger(
				Tr::_( 'There are some problems ... Please check the form.' )
			);
			
			$this->output('main');
		}
		
	}
	
	public function generate_id_Action() : void
	{
		$name = Http_Request::GET()->getString( 'name' );
		
		$id = JetStudio::generateIdentifier( $name, function( $id ) {
			return Main::exists( $id );
		} );
		
		AJAX::commonResponse(
			[
				'id' => $id
			]
		);
	}
	
	public function get_module_controller_actions_Action() : void
	{
		$GET = Http_Request::GET();
		
		$module_name = $GET->getString( 'module' );
		$controller = $GET->getString( 'controller' );
		
		
		AJAX::commonResponse(
			[
				'actions' => Page::getModuleControllerActions( $module_name, $controller )
			]
		);
	}
	
	public function get_module_controllers_Action() : void
	{
		$GET = Http_Request::GET();
		
		$default_controller = '';
		$actions = [];
		$module_name = $GET->getString( 'module' );
		
		$controllers = Page::getModuleControllers( $module_name );
		
		foreach( $controllers as $default_controller ) {
			$actions = Page::getModuleControllerActions( $module_name, $default_controller );
			break;
		}
		
		AJAX::commonResponse(
			[
				'controllers'        => $controllers,
				'default_controller' => $default_controller,
				'actions'            => $actions
			]
		);
		
	}
}