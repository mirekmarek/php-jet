<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\OAuth\Backend\GitHub;

use Exception;
use Jet\Http_Headers;
use Jet\Tr;
use Jet\UI_messages;
use JetApplication\Admin_ControlCentre_Module_Controller;
use JetApplication\Admin_ControlCentre_Module_Interface_BaseIsSpecification;
use JetApplication\Admin_ControlCentre_Module_Trait_BaseIsSpecification;


class Controller_ControlCentre extends Admin_ControlCentre_Module_Controller implements Admin_ControlCentre_Module_Interface_BaseIsSpecification
{
	use Admin_ControlCentre_Module_Trait_BaseIsSpecification;
	
	public function default_Action() : void
	{
		/**
		 * @var Main $module
		 */
		$module = $this->getModule();
		
		$base = $this->getBaseBySpecificationId( $this->getSpecificationId() );
		$config = $module->getConfig( $this->getSpecificationId() );
		
		$form = $config->createForm('config_form');
		
		if( $form->catch() ) {
			
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
			
			Http_Headers::reload();
		}
		
		$this->view->setVar('module', $module );
		$this->view->setVar('base', $base );
		$this->view->setVar('config', $config );
		$this->view->setVar('form', $form );
		
		$this->output('control-centre/default');
	}
}