<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\SysServices\Manager;


use Exception;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Tr;
use Jet\UI_messages;
use JetApplication\Admin_ControlCentre_Module_Controller;

class Controller_ControlCentre extends Admin_ControlCentre_Module_Controller {
	
	public function default_Action() : void
	{
		/**
		 * @var Main $module
		 */
		$module = $this->getModule();
		
		/**
		 * @var Config_General $config
		 */
		$config = $module->getGeneralConfig();
		
		$form = $config->createForm('config_form');
		
		$save = function() use ($config) {
			$ok = true;
			try {
				$config->saveConfigFile();
			} catch( Exception $e ) {
				$ok = false;
				UI_messages::danger( Tr::_('Error during configuration saving: ').$e->getMessage(), context: 'CC' );
			}
			
			if($ok) {
				UI_messages::success( Tr::_('Configuration has been saved'), context: 'CC' );
			}
		};
		
		if( $form->catch() ) {
			$save();
			Http_Headers::reload();
			
		}
		
		if( Http_Request::GET()->getString('action')=='generate_key' ) {
			$config->generateKey();
			$save();
			Http_Headers::reload( unset_GET_params: ['action'] );
		}
		
		$this->view->setVar('config', $config);
		$this->view->setVar('form', $form);
		
		$this->output('control-centre/default');
	}
}