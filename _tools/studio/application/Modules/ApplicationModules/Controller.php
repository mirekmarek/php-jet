<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudioModule\ApplicationModules;

use Jet\AJAX;
use Jet\Application_Modules;
use Jet\Exception;
use Jet\Http_Headers;
use Jet\SysConf_Path;
use Jet\Tr;
use Jet\Translator;
use Jet\UI_messages;
use Jet\Http_Request;
use JetStudio\JetStudio;
use JetStudio\JetStudio_Module_Controller;
use JetStudio\JetStudio_Conf_Path;


class Controller extends JetStudio_Module_Controller
{
	
	protected function resolve() : string
	{
		
		return Http_Request::GET()->getString('action', 'default');
	}
	
	public function default_Action() : void
	{
		if(Main::getCurrentModule()) {
			$this->output('main');
		} else {
			$this->output('dashboard');
		}
	}
	
	public function edit_Action() : void
	{
		$current = Main::getCurrentModule();
		
		if(
			$current &&
			$current->catchEditForm()
		) {
			if( $current->save() ) {
				UI_messages::success( Tr::_( 'Saved ...' ) );
				
				Http_Headers::reload( [], ['action'] );
			}
			
		} else {
			UI_messages::danger(
				Tr::_( 'There are some problems ... Please check the form.' )
			);
		}
		
	}
	
	public function install_Action() : void
	{
		$current = Main::getCurrentModule();
		
		if( !$current ) {
			die();
		}
		
		$tr_dir = SysConf_Path::getDictionaries();
		$ok = true;
		try {
			SysConf_Path::setDictionaries(JetStudio_Conf_Path::getDictionaries());
			Application_Modules::installModule( $current->getName() );
		} catch( Exception $e ) {
			$ok = false;
			UI_messages::danger( $e->getMessage() );
		}
		SysConf_Path::setDictionaries($tr_dir);
		
		if( $ok ) {
			UI_messages::success( Tr::_( 'Module <b>%module%</b> has been installed', [
				'module' => $current->getName()
			] ) );
		}
		
		Http_Headers::reload( [], ['action'] );
		
	}
	
	public function activate_Action() : void
	{
		$current = Main::getCurrentModule();
		
		if( !$current ) {
			die();
		}
		
		$tr_dir = SysConf_Path::getDictionaries();
		$ok = true;
		try {
			SysConf_Path::setDictionaries(JetStudio_Conf_Path::getDictionaries());
			Application_Modules::activateModule( $current->getName() );
		} catch( Exception $e ) {
			$ok = false;
			UI_messages::danger( $e->getMessage() );
		}
		SysConf_Path::setDictionaries($tr_dir);
		
		if( $ok ) {
			UI_messages::success( Tr::_( 'Module <b>%module%</b> has been activated', [
				'module' => $current->getName()
			] ) );
		}
		
		Http_Headers::reload( [], ['action'] );
	}
	
	
	public function install_activate_Action() : void
	{
		$current = Main::getCurrentModule();
		
		if( !$current ) {
			die();
		}
		
		$tr_dir = SysConf_Path::getDictionaries();
		$ok = true;
		try {
			SysConf_Path::setDictionaries(JetStudio_Conf_Path::getDictionaries());
			Application_Modules::installModule( $current->getName() );
			Application_Modules::activateModule( $current->getName() );
		} catch( Exception $e ) {
			$ok = false;
			UI_messages::danger( $e->getMessage() );
		}
		SysConf_Path::setDictionaries($tr_dir);
		
		if( $ok ) {
			UI_messages::success( Tr::_( 'Module <b>%module%</b> has been installed and activated', [
				'module' => $current->getName()
			] ) );
		}
		
		Http_Headers::reload( [], ['action'] );
	}
	
	public function uninstall_Action() : void
	{
		$current = Main::getCurrentModule();
		
		if( !$current ) {
			die();
		}
		
		$tr_dir = SysConf_Path::getDictionaries();
		$ok = true;
		try {
			SysConf_Path::setDictionaries(JetStudio_Conf_Path::getDictionaries());
			Application_Modules::uninstallModule( $current->getName() );
		} catch( Exception $e ) {
			$ok = false;
			UI_messages::danger( $e->getMessage() );
		}
		SysConf_Path::setDictionaries($tr_dir);
		
		if( $ok ) {
			UI_messages::info( Tr::_( 'Module <b>%module%</b> has been uninstalled', [
				'module' => $current->getName()
			] ) );
		}
		
		Http_Headers::reload( [], ['action'] );
		
	}
	
	public function active_Action() : void
	{
		$current = Main::getCurrentModule();
		
		if( !$current ) {
			die();
		}
		
		if( $current->isInstalled() ) {
			
			$ok = true;
			try {
				Application_Modules::activateModule( $current->getName() );
			} catch( Exception $e ) {
				$ok = false;
				UI_messages::danger( $e->getMessage() );
			}
			
			if( $ok ) {
				UI_messages::success( Tr::_( 'Module <b>%module%</b> has been activated', [
					'module' => $current->getName()
				] ) );
			}
		}
		
		
		Http_Headers::reload( [], ['action'] );
	}
	
	public function deactivate_Action() : void
	{
		$current = Main::getCurrentModule();
		
		if( !$current ) {
			die();
		}
		
		if( $current->isInstalled() ) {
			
			$ok = true;
			try {
				Application_Modules::deactivateModule( $current->getName() );
			} catch( Exception $e ) {
				$ok = false;
				UI_messages::danger( $e->getMessage() );
			}
			
			if( $ok ) {
				UI_messages::info( Tr::_( 'Module <b>%module%</b> has been deactivated', [
					'module' => $current->getName()
				] ) );
			}
			
		}
		
		
		Http_Headers::reload( [], ['action'] );
		
	}
	
	public function clone_Action() : void
	{
		$current = Main::getCurrentModule();
		
		if(!$current) {
			die();
		}
		
		$view = $this->view;
		
		
		if(!($new=$current->catchCloneForm())) {
			UI_messages::danger(
				Tr::_( 'There are some problems ... Please check the form.' )
			);
			
			AJAX::operationResponse(false, [
				'clone_module_form_area' => $view->render('module/clone/form')
			]);
		}
		
		try {
			Main::clone( $current, $new );
		} catch( Exception $e ) {
			JetStudio::handleError( $e );
			
			AJAX::operationResponse(false, [
				'clone_module_form_area' => $view->render('module/clone/form')
			]);
		}
		
		UI_messages::success(Tr::_('Module %new_module_name% has been cloned', ['new_module_name'=>$new->getName()]));
		
		
		AJAX::operationResponse(success: true, data: [
			'redirect' => Main::getActionUrl(action: '', custom_get_params: ['module'=>$new->getName()])
		]);
		
	}
	
	public function collect_dictionaries_Action() : void
	{
		$current = Main::getCurrentModule();
		
		if( !$current ) {
			die();
		}
		
		
		
		if( $current->isInstalled() ) {
			
			$ok = true;
			$tr_dir = SysConf_Path::getDictionaries();
			
			try {
				
				SysConf_Path::setDictionaries(JetStudio_Conf_Path::getDictionaries());
				Translator::collectApplicationModuleDictionaries( $current );
				
			} catch( Exception $e ) {
				$ok = false;
				UI_messages::danger( $e->getMessage() );
			}
			SysConf_Path::setDictionaries($tr_dir);
			
			if( $ok ) {
				UI_messages::success( Tr::_( 'Module dictionaries has been collected', [
					'module' => $current->getName()
				] ) );
			}
			
		}
		
		
		Http_Headers::reload( [], ['action'] );
	}
	
	public function page_generate_id_Action() : void
	{
		$name = Http_Request::GET()->getString( 'name' );
		$base_id = Http_Request::GET()->getString( 'base_id' );
		
		$id = Modules_Pages::generatePageId( $name, $base_id );
		
		AJAX::commonResponse(
			[
				'id' => $id
			]
		);
	}
	
	public function page_add_Action() : void
	{
		$current = Main::getCurrentModule();
		if(!$current) {
			die();
		}
		
		$ok = false;
		$data = [];
		
		if(
			($new_page = $current->getPages()->catchCratePageForm())
		) {
			$form = $current->getPages()->getPageCreateForm();
			
			if( $current->getPages()->addPage( $new_page ) ) {
				$ok = true;
				UI_messages::success( Tr::_( 'Page <b>%page%</b> has been added', ['page' => $new_page->getName()] ) );
				$data['id'] = $new_page->getKey();
			} else {
				$form->setCommonMessage( implode( '', UI_messages::get() ) );
			}
		}
		
		$snippets = [
			'add_page_form_area' => $this->view->render( 'page/create/form' )
		];
		
		AJAX::operationResponse(
			$ok,
			$snippets,
			$data
		);
	}
	
	public function menu_item_add_Action() : void
	{
		$ok = false;
		$data = [];
		$snippets = [];
		
		$module = Main::getCurrentModule();
		if(
			$module &&
			($form = $module->getMenuItems()->getCreateMenuItemForm()) &&
			($new_item = $module->getMenuItems()->catchCreateMenuItemForm( $set, $menu, $item ))
		) {
			
			$form = $module->getMenuItems()->getCreateMenuItemForm();
			
			$ok = true;
			try {
				$module->getMenuItems()->save();
				
				UI_messages::success(
					Tr::_( 'Menu item <strong>%item%</strong> has been created', [
						'item' => $new_item->getLabel()
					] )
				);
				
				$data['edit_url'] = Http_Request::currentURI(
					set_GET_params: [
						'set' => $set,
						'menu' => $menu,
						'item' => $item
					],
					unset_GET_params: ['action']
				);
				
			} catch( Exception $e ) {
				$ok = false;
				
				$form->setCommonMessage( UI_messages::createDanger($e->getMessage()) );
			}
			
		}
		
		$snippets['create_menu_item_form_area'] = $this->view->render( 'menu_item/create/form' );
		
		AJAX::operationResponse(
			$ok,
			$snippets,
			$data
		);
	}
	
	public function menu_item_generate_id_Action() : void
	{
		$name = Http_Request::GET()->getString( 'name' );
		
		$id = JetStudio::generateIdentifier( $name, function( $id ) {
			return false;
		} );
		
		AJAX::commonResponse(
			[
				'id' => $id
			]
		);
	}
}