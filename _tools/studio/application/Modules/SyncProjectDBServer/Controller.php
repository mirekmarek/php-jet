<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudioModule\SyncProjectDBServer;

use Error;
use Exception;
use Jet\DataModel;
use Jet\DataModel_LoadedData;
use Jet\Http_Headers;
use Jet\RESTServer;
use JetStudio\JetStudio_Module_Controller;

use Jet\Http_Request;

class Controller extends JetStudio_Module_Controller
{
	protected ServerConfig $server_config;
	
	protected function resolve(): string
	{
		$this->server_config = ServerConfig::get();
		
		/**
		 * @var Main $module
		 */
		$module = $this->module;
		
		if($module->getServerActivated()) {
			return 'server';
		}
		
		if(($action=Http_Request::GET()->getString('action'))) {
			if($action=='regenerate_key') {
				$this->server_config->generate();
			}
			
			Http_Headers::reload(unset_GET_params: ['action']);
		}
		
		return 'default';
	}
	
	public function default_Action() : void
	{
		$this->view->setVar('server_config', $this->server_config);
		$this->output('main');
	}
	
	
	public function server_Action(): void
	{
		$data = Http_Request::rawPostData();
		$data = json_decode($data, true);
		
		if(
			!is_array($data) ||
			!isset($data['action']) ||
			!isset($data['params'])
		) {
			RESTServer::responseError(RESTServer::ERR_CODE_COMMON, [
				'error_message' => 'unknown request'
			]);
		}
		
		try {
			$params = $data['params'];
			switch($data['action']) {
				case 'test':
				case 'get_diff':
				case 'add':
				case 'update':
				case 'delete':
					$this->{$data['action']}( $params );
					break;
				default:
					RESTServer::responseError(RESTServer::ERR_CODE_COMMON, [
						'error_message' => 'unknown action'
					]);
			}
		} catch(Error|Exception $e) {
			RESTServer::responseError(RESTServer::ERR_CODE_COMMON, [
				'error_message' => $e->getMessage()
			]);
		}
	}
	
	public function test() : void
	{
		RESTServer::responseOK();
	}
	
	public function add( array $params ): void
	{
		$class = $params['class'];
		$id = $params['id'];
		/**
		 * @var DataModel_LoadedData $item
		 */
		$item = unserialize( base64_decode( $params['item'] ) );
		
		try {
			/**
			 * @var DataModel $class
			 */
			$item = $class::initByData( $item->getMainData(), $item->getRelatedData());
			$item->setIsNew(true);
			$item->save();
		} catch( Exception|Error $e ) {
			RESTServer::responseError(RESTServer::ERR_CODE_COMMON, $e->getMessage());
		}
		RESTServer::responseOK();
		
	}
	
	public function update( array $params ): void
	{
		$class = $params['class'];
		$id = $params['id'];
		/**
		 * @var DataModel_LoadedData $item
		 */
		$item = unserialize( base64_decode( $params['item'] ) );
		
		try {
			/**
			 * @var DataModel $class
			 */
			
			$item = $class::initByData( $item->getMainData(), $item->getRelatedData());
			$item->save();
		} catch( Exception|Error $e ) {
			RESTServer::responseError(RESTServer::ERR_CODE_COMMON, $e->getMessage());
		}
		RESTServer::responseOK();
	}
	
	public function delete( array $params ): void
	{
		$class = $params['class'];
		$id = $params['id'];
		
		try {
			/**
			 * @var DataModel $class
			 */
			$item = $class::load( $class::getEmptyIDController()->fromString( $id ) );
			$item?->delete();
		} catch( Exception|Error $e ) {
			RESTServer::responseError(RESTServer::ERR_CODE_COMMON, $e->getMessage());
		}
		RESTServer::responseOK();
	}
	
	public function get_diff( array $params ): void
	{
		$class = $params['class'];
		$client_map = $params['map'];
		
		/**
		 * @var DataModel $class
		 */
		
		$ids = $class::fetchIDs();
		$diff = [
			'add' => [],
			'update' => [],
			'delete' => [],
		];
		
		$definition = $class::getDataModelDefinition();
		$server_map = [];
		
		foreach($ids as $id) {
			$data = $class::load( $id );
			
			if($data) {
				$server_map[$id->toString()] = $data->getCheckSum();
			}
			unset($data);
		}
		
		foreach($client_map as $id=>$ch_s) {
			if(!isset($server_map[$id])) {
				$diff['add'][] = $id;
				continue;
			}
			
			if($server_map[$id]!=$client_map[$id]) {
				$diff['update'][] = $id;
			}
		}
		
		foreach($server_map as $id=>$ch_s) {
			if(!isset($client_map[$id])) {
				$diff['delete'][] = $id;
			}
		}

		
		RESTServer::responseData( $diff );
	}
	

}