<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudioModule\SyncProjectFilesClient;

use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\IO_File;
use Jet\MVC_Layout;
use Jet\SysConf_Path;
use Jet\Tr;
use Jet\UI_messages;
use JetStudio\JetStudio;
use JetStudio\JetStudio_Conf_Path;
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
			$this->client_config->getAllowedExtensions()
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
		
		$diff = $this->client?->getDiff();
		
		$GET = Http_Request::GET();
		
		if( $diff ) {
			if( $GET->getString('synchronize') ) {
				set_time_limit(-1);
				
				$POST = Http_Request::POST();
				
				$add = $POST->getRaw('add', []);
				$update = $POST->getRaw('update', []);
				$delete = $POST->getRaw('delete', []);
				
				if($this->client->sync( $add, $update, $delete )) {
					UI_messages::success( Tr::_( 'Project has been successfully synchronized.' ), 'db_sync_client' );
				} else {
					UI_messages::danger( Tr::_( 'Error during synchronization: %ERROR%', ['ERROR'=>$this->client->getErrorMessage()] ), 'db_sync_client' );
				}
				
				Http_Headers::reload(unset_GET_params: ['synchronize']);
			}
			
			if(
				($file=$GET->getString('show_local')) &&
				in_array($file, $diff['add'])
			) {
				$content = IO_File::read( JetStudio_Conf_Path::getRoot().$file );
				
				$this->view->setVar('file', $file);
				$this->view->setVar('content', $content);
				
				JetStudio::initLayout( 'empty' );
				
				$this->output('show-diff/add');
				return;
			}
			
			if(
				($file=$GET->getString('show_remote')) &&
				in_array($file, $diff['delete'])
			) {
				$this->view->setVar('file', $file);
				$this->view->setVar('content', base64_decode($diff['backup'][$file]) );
				
				JetStudio::initLayout( 'empty' );
				
				$this->output('show-diff/delete');
				return;
			}
			
			if(
				($file=$GET->getString('show_diff')) &&
				in_array($file, $diff['update'])
			) {
				require 'Diff/Diff.php';
				require 'Diff/Diff/Renderer/Html/SideBySide.php';
				
				
				$this->view->setVar('file', $file);
				$this->view->setVar('locale', IO_File::read(JetStudio_Conf_Path::getRoot().$file) );
				$this->view->setVar('remote', base64_decode($diff['backup'][$file]) );
				
				JetStudio::initLayout( 'empty' );
				
				$this->output('show-diff/update');
				return;
			}
			
		}
		
		
		$this->view->setVar('config', $this->client_config);
		$this->view->setVar('client', $this->client);
		$this->view->setVar('form', $form);
		$this->view->setVar('diff', $diff );
		
		$this->output('main');
		
	}
	
	
	
}