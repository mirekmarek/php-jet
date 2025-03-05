<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudioModule\SyncProjectDBClient;

use Jet\BaseObject;
use Jet\DataModel;

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
	
	protected ?array $diffs = null;
	
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
		
		$headers[] = 'X-J-S-Sync-DB-Key: '.$this->config->getServerKey();
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
	
	public function getDiffs() : array
	{
		if($this->diffs===null) {
			$this->diffs = [];
			foreach( $this->config->getSelectedClasses() as $class ) {
				
				/**
				 * @var DataModel|string $class
				 */
				
				$map = [];
				try {
					$ids = $class::fetchIDs();
				
					$definition = $class::getDataModelDefinition();
				
					foreach($ids as $id) {
						$data = $class::load( $id );
						
						if($data) {
							$map[$id->toString()] = $data->getCheckSum();
						}
						unset($data);
					}
				} catch( \Error|\Exception $e ) {}
				
				if($this->do(
					'get_diff',
					[
						'action' => 'get_diff',
						'class' => $class,
						'map' => $map,
					]
				)) {
					$this->diffs[$class] = $this->response_data;
				}
			}
			
		}
		
		return $this->diffs;
	}
	
	public function sync() : bool
	{
		$diffs = $this->getDiffs();
		
		foreach($diffs as $class => $diff) {
			/**
			 * @var DataModel $class
			 */
			
			if($this->config->getPerformAdd()) {
				foreach($diff['add'] as $id) {
					
					$item = $class::loadData( $class::getEmptyIDController()->fromString( $id ) );
					if(!$item) {
						continue;
					}
					
					if(!$this->do('add', [
						'class' => $class,
						'id' => $id,
						'item' => base64_encode(serialize($item)),
					])) {
						return false;
					}
				}
			}
			
			if($this->config->getPerformUpdate()) {
				foreach($diff['update'] as $id) {
					
					$item = $class::loadData( $class::getEmptyIDController()->fromString( $id ) );
					if(!$item) {
						continue;
					}
					
					if(!$this->do('update', [
						'class' => $class,
						'id' => $id,
						'item' => base64_encode(serialize($item)),
					])) {
						return false;
					}
				}
			}

			if($this->config->getPerformDelete()) {
				foreach($diff['delete'] as $id) {
					if(!$this->do('delete', [
						'class' => $class,
						'id' => $id
					])) {
						return false;
					}
				}
			}
			
		}
		
		return true;
	}
	
}