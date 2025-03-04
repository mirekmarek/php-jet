<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudioModule\SyncProjectFilesServer;

use Error;
use Exception;
use Jet\Http_Headers;
use Jet\IO_Dir;
use Jet\IO_File;
use Jet\RESTServer;
use JetStudio\JetStudio_Conf_Path;
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
	}
	
	public function test() : void
	{
		RESTServer::responseOK();
	}
	
	public function add( array $params ): void
	{
		$file_path = $params['file_path'];
		$file = base64_decode( $params['file'] );
		
		try {
			IO_File::write( JetStudio_Conf_Path::getRoot().$file_path, $file );
		} catch( Exception|Error $e ) {
			RESTServer::responseError(RESTServer::ERR_CODE_COMMON, $e->getMessage());
		}
	}
	
	public function update( array $params ): void
	{
		$file_path = $params['file_path'];
		$file = base64_decode($params['file']);
		
		try {
			IO_File::write( JetStudio_Conf_Path::getRoot().$file_path, $file );
		} catch( Exception|Error $e ) {
			RESTServer::responseError(RESTServer::ERR_CODE_COMMON, $e->getMessage());
		}
	}
	
	public function delete( array $params ): void
	{
		$file_path = $params['file_path'];
		
		try {
			IO_File::delete( JetStudio_Conf_Path::getRoot().$file_path );
		} catch( Exception|Error $e ) {
			RESTServer::responseError(RESTServer::ERR_CODE_COMMON, $e->getMessage());
		}
	}
	
	
	protected array $map = [];
	
	protected function getDiff_readDir( string $dir, array $allowed_extensions, array $black_list ) : void
	{
		$dir_name = str_replace( JetStudio_Conf_Path::getRoot(), '', $dir );
		
		foreach($black_list as $bl_item) {
			if(str_starts_with($dir_name, $bl_item)) {
				return;
			}
		}
		
		foreach($allowed_extensions as $ext) {
			$files = IO_Dir::getFilesList( $dir, '*.'.$ext );
			
			
			foreach($files as $path=>$name) {
				$file = str_replace( JetStudio_Conf_Path::getRoot(), '', $path );
				
				if(in_array($file, $black_list)) {
					continue;
				}
				
				$this->map[$file] = md5_file( $path );
			}
		}
		
		foreach(IO_Dir::getSubdirectoriesList( $dir ) as $sub_dir_path=>$sub_dir) {
			$this->getDiff_readDir( $sub_dir_path, $allowed_extensions, $black_list );
		}
		
	}
	
	
	public function get_diff( array $params ): void
	{
		$allowed_extensions = $params['allowed_extensions'];
		$black_list = $params['blacklist'];
		$client_map = $params['map'];
		
		$dirs = IO_Dir::getSubdirectoriesList( JetStudio_Conf_Path::getRoot() );
		foreach($dirs as $path=>$name) {
			$this->getDiff_readDir(
				$path,
				$allowed_extensions,
				$black_list
			);
		}
		
		$server_map = $this->map;
		
		$diff = [
			'add' => [],
			'update' => [],
			'delete' => [],
			'backup' => [],
		];
		
		
		
		foreach($client_map as $file=>$ch_s) {
			if(!isset($server_map[$file])) {
				$diff['add'][] = $file;
				continue;
			}
			
			if($server_map[$file]!=$client_map[$file]) {
				$diff['update'][] = $file;
			}
		}
		
		foreach($server_map as $file=>$ch_s) {
			if(!isset($client_map[$file])) {
				$diff['delete'][] = $file;
			}
		}
		
		foreach( $diff['update'] as $file ) {
			$diff['backup'][$file] = base64_encode( IO_File::read($file) );
		}
		foreach( $diff['delete'] as $file ) {
			$diff['backup'][$file] = base64_encode( IO_File::read($file) );
		}

		
		RESTServer::responseData( $diff );
	}
	

}