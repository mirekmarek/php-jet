<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudioModule\SyncProjectFilesClient;

use Jet\BaseObject;
use Jet\DataModel;
use Jet\Debug;
use Jet\IO_Dir;
use Jet\IO_File;
use Jet\SysConf_Path;
use JetStudio\JetStudio_Conf_Path;

class Client extends BaseObject
{
	public const HTTP_STATUS_OK = 200;
	
	protected ClientConfig $config;
	
	protected string $request = '';
	protected ?array $request_data = null;
	protected string|array $request_body = '';
	protected int $response_status = 0;
	protected string $response_header = '';
	protected string $response_body = '';
	protected array|null $response_data = null;
	protected string $error_message = '';
	
	protected ?array $diff = null;
	
	public function __construct( ClientConfig $config )
	{
		$this->config = $config;
	}
	
	public function testConnection(): bool
	{
		return $this->do( 'test', ['test'=>'test'] );
	}
	
	public function do( string $action, array $params ) : bool
	{
		$this->error_message = '';
		$this->request = '';
		$this->request_data = null;
		$this->request_body = '';
		$this->response_status = 0;
		$this->response_header = '';
		$this->response_body = '';
		$this->response_data = null;
		$this->error_message = '';

		$headers = [];
		
		$headers[] = 'X-J-S-Sync-Files-Key: '.$this->config->getServerKey();
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Accept: application/json';
		
		$curl_handle = curl_init();
		curl_setopt( $curl_handle, CURLOPT_URL, $this->config->getServerURL());
		curl_setopt( $curl_handle, CURLOPT_POST, true );
		curl_setopt( $curl_handle, CURLOPT_POSTFIELDS, json_encode([
			'action' => $action,
			'params' => $params,
		] ));
		curl_setopt( $curl_handle, CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $curl_handle, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl_handle, CURLOPT_VERBOSE, true );
		curl_setopt( $curl_handle, CURLOPT_HEADER, true );
		curl_setopt( $curl_handle, CURLINFO_HEADER_OUT, true );
		
		$this->response_body = curl_exec( $curl_handle );

		$this->request = curl_getinfo( $curl_handle, CURLINFO_HEADER_OUT );
		$this->response_status = curl_getinfo( $curl_handle, CURLINFO_HTTP_CODE );
		
		
		$header_size = curl_getinfo( $curl_handle, CURLINFO_HEADER_SIZE );
		$this->response_header = substr( $this->response_body, 0, $header_size );
		$this->response_body = substr( $this->response_body, $header_size );
		
		$result = false;
		
		if($this->response_data===false) {
			$this->error_message = 'CURL_ERR:' . curl_errno( $curl_handle ) . ' - ' . curl_error( $curl_handle );
			
			curl_close( $curl_handle );
			return false;
		}
		
		switch( $this->response_status ) {
			case self::HTTP_STATUS_OK:
				$this->response_data = json_decode( $this->response_body, true );
				
				if( !is_array( $this->response_data ) ) {
					$this->error_message = 'JSON parse error';
				} else {
					$result = true;
				}
				break;
			case 404:
				$this->error_message = 'Incorrect URL';
				break;
			case 401:
				$this->error_message = 'Incorrect key';
				break;
			default:
				$this->error_message = 'Unknown error';
				break;
		}
		
		curl_close( $curl_handle );
		
		return $result;
	}
	
	public function getRequest(): string
	{
		return $this->request;
	}
	
	public function getRequestData(): ?array
	{
		return $this->request_data;
	}
	
	public function getRequestBody(): array|string
	{
		return $this->request_body;
	}
	
	public function getResponseStatus(): int
	{
		return $this->response_status;
	}
	
	public function getResponseHeader(): string
	{
		return $this->response_header;
	}
	
	public function getResponseBody(): string
	{
		return $this->response_body;
	}
	
	public function getResponseData(): ?array
	{
		return $this->response_data;
	}
	
	public function getErrorMessage(): string
	{
		return $this->error_message;
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
	
	public function getDiff() : array
	{
		if($this->diff===null) {
			$this->diff = [];
			
			/**
			 * @var DataModel|string $class
			 */
			
			$this->map = [];
			
			$allowed_extensions = $this->config->getAllowedExtensions( true );
			$black_list = $this->config->getBlacklist( true );
			
			$dirs = IO_Dir::getSubdirectoriesList( JetStudio_Conf_Path::getRoot() );
			foreach($dirs as $path=>$name) {
				$this->getDiff_readDir(
					$path,
					$allowed_extensions,
					$black_list
				);
			}
			
			if($this->do(
				'get_diff',
				[
					'action' => 'get_diff',
					'allowed_extensions' => $allowed_extensions,
					'blacklist' => $black_list,
					'map' => $this->map,
				]
			)) {
				$this->diff = $this->response_data;
			}
		}
		
		return $this->diff;
	}
	
	public function sync( array $add, array $update, array $delete ) : bool
	{
		$backup_dir = SysConf_Path::getTmp().'sync_files_backup_'.date('Ymd_His').'/';
		IO_Dir::create( $backup_dir );
		
		foreach($update as $file_path) {
			if(!in_array($file_path, $this->diff['update'])) {
				continue;
			}
			
			$backup = base64_decode($this->diff['backup'][$file_path]);
			IO_File::write( $backup_dir.$file_path, $backup );
		}
		
		foreach($delete as $file_path) {
			if(!in_array($file_path, $this->diff['delete'])) {
				continue;
			}
			
			$backup = base64_decode($this->diff['backup'][$file_path]);
			IO_File::write( $backup_dir.$file_path, $backup );
		}
		
		
		foreach($add as $file_path) {
			if(!in_array($file_path, $this->diff['add'])) {
				continue;
			}
			
			if(!$this->do('add', [
				'file_path' => $file_path,
				'file' => base64_encode( IO_File::read( JetStudio_Conf_Path::getRoot().$file_path ) ),
			])) {
				return false;
			}
			
		}
		
		foreach($update as $file_path) {
			if(!in_array($file_path, $this->diff['update'])) {
				continue;
			}
			
			if(!$this->do('update', [
				'file_path' => $file_path,
				'file' => base64_encode( IO_File::read( JetStudio_Conf_Path::getRoot().$file_path ) ),
			])) {
				return false;
			}
		}
		
		foreach($delete as $file_path) {
			if(!in_array($file_path, $this->diff['delete'])) {
				continue;
			}
			
			if(!$this->do('delete', [
				'file_path' => $file_path,
			])) {
				return false;
			}
		}
		
		return true;
	}
	
}