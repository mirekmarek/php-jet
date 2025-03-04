<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudioModule\SyncProjectDBServer;

use Jet\Config;
use Jet\Config_Definition;
use Jet\Data_DateTime;
use Jet\Http_Request;
use Jet\IO_File;
use Jet\SysConf_Path;

#[Config_Definition(name: 'db')]
class ServerConfig extends Config {
	
	protected static string $ttl = '120 minutes';
	
	#[Config_Definition(
		type: Config::TYPE_STRING
	)]
	protected string $server_url_path = '';
	
	#[Config_Definition(
		type: Config::TYPE_STRING
	)]
	protected string $server_key = '';
	
	protected static ?ServerConfig $config = null;
	
	public function getConfigFilePath() : string
	{
		return SysConf_Path::getTmp().'/sync_project_db_server_config.php';
	}
	
	public static function get() : static
	{
		if(!static::$config) {
			static::$config = new static();
			
			$file_path = static::$config->getConfigFilePath();
			
			$deadline = strtotime('-'.static::$ttl);
			
			if(
				IO_File::exists($file_path) &&
				filectime($file_path)<$deadline
			) {
				IO_File::delete($file_path);
			}
			
			if(
				IO_File::exists($file_path)
			) {
				$data = require $file_path;
				static::$config->setData($data);
			}
			
		}
		
		return static::$config;
	}
	
	public function generate() : void
	{
		$this->server_url_path = sha1( uniqid().uniqid() );
		
		$this->server_key = md5( uniqid() );
		
		$this->saveConfigFile();
		
	}
	
	
	
	public function getServerUrlPath(): string
	{
		return $this->server_url_path;
	}
	
	public function getServerURL(): string
	{
		if(
			!$this->getServerUrlPath() ||
			!$this->getServerKey()
		) {
			return '';
		}
		
		return rtrim(Http_Request::currentURL( unset_GET_params: array_keys(Http_Request::GET()->getRawData()) ), '/').'/'.$this->getServerUrlPath();
	}
	
	public function getServerKey(): string
	{
		return $this->server_key;
	}
	
	
	public function getValidTill() : ?Data_DateTime
	{
		if(!IO_File::exists($this->getConfigFilePath())) {
			return null;
		}
		
		$valid_till = date('Y-m-d H:i:s', strtotime(
			'+'.static::$ttl,
			filectime( $this->getConfigFilePath() )
		));
		
		return new Data_DateTime( $valid_till );
	}
	
}