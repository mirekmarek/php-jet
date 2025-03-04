<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudioModule\SyncProjectDBClient;

use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Tr;
use Jet\UI_messages;
use JetStudio\JetStudio_Module_Controller;

class Controller extends JetStudio_Module_Controller
{
	
	protected ClientConfig $client_config;
	protected ?Client $client = null;
	
	protected function resolve(): string
	{
		$this->client_config = ClientConfig::get();
		if(
			$this->client_config->getServerURL() &&
			$this->client_config->getServerKey() &&
			$this->client_config->getSelectedClasses()
		) {
			$this->client = new Client( $this->client_config );
		}
		
		return 'default';
	}
	
	public function default_Action() : void
	{
		$form = $this->client_config->getForm();
		
		if($form->catch()) {
			$this->client_config->save();
			
			Http_Headers::reload();
		}
		
		$diffs = $this->client?->getDiffs();
		
		
		if(
			$diffs &&
			Http_Request::GET()->getString('synchronize')
		) {
			set_time_limit(-1);
			
			if($this->client->sync()) {
				UI_messages::success( Tr::_( 'Database has been successfully synchronized.' ), 'db_sync_client' );
			} else {
				UI_messages::danger( Tr::_( 'Error during synchronization: %ERROR%', ['ERROR'=>$this->client->getErrorMessage()] ), 'db_sync_client' );
			}
			
			Http_Headers::reload(unset_GET_params: ['synchronize']);
		}
		
		$this->view->setVar('config', $this->client_config);
		$this->view->setVar('client', $this->client);
		$this->view->setVar('form', $form);
		$this->view->setVar('diffs', $diffs );
		
		$this->output('main');
		
	}
	
	
	
}