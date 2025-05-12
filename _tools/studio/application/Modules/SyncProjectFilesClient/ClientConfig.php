<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudioModule\SyncProjectFilesClient;

use Jet\BaseObject;
use Jet\Form;
use Jet\Form_Definition;
use Jet\Form_Definition_Interface;
use Jet\Form_Definition_Trait;
use Jet\Form_Field;
use Jet\Session;
use JetStudio\JetStudio;

class ClientConfig extends BaseObject implements Form_Definition_Interface {
	use Form_Definition_Trait;
	
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		label: 'Server URL:',
	)]
	protected string $server_URL = '';
	
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		label: 'Server key:',
	)]
	protected string $server_key = '';
	
	#[Form_Definition(
		type: Form_Field::TYPE_TEXTAREA,
		label: 'Allowed file extensions:',
		is_required: false,
		error_messages: [
		]
	)]
	protected string $allowed_extensions = 'php
phtml
js
css';
	
	#[Form_Definition(
		type: Form_Field::TYPE_TEXTAREA,
		label: 'Blacklist:',
		is_required: false,
		error_messages: [
		]
	)]
	protected string $blacklist = '_backup
_installer
_profiler
_tools
_var_dump
application/config
application/data
js/packages
css/packages
images
cache
logs
tmp';
	
	
	
	public static function getClassesList() : array
	{
		$data_model_classes = JetStudio::getModule_DataModel()?->getDataModelClasses()??[];
		
		$options = [];
		foreach($data_model_classes as $class_name => $class) {
			$options[$class_name] = $class->getFullClassName();
		}
		
		return $options;
	}
	
	protected static function getSession() : Session
	{
		return new Session('SyncProjectFilesClient');
	}
	
	public static function get() : ClientConfig
	{
		$session = static::getSession();
		
		$config = $session->getValue('cfg');
		if(!$config) {
			$config = new ClientConfig();
			$config->save();
		} else {
			$config = unserialize($config);
		}
		
		return $config;
	}
	
	public function save() : void
	{
		static::getSession()->setValue( 'cfg', serialize($this) );
	}
	
	public function getForm() : Form
	{
		return $this->createForm('client_config_form');
	}
	
	public function getServerURL(): string
	{
		return $this->server_URL;
	}
	
	public function getServerKey(): string
	{
		return $this->server_key;
	}
	
	public function getAllowedExtensions( bool $as_array=false ): string|array
	{
		if($as_array) {
			$_value = explode("\n", $this->allowed_extensions);
			$value = [];
			foreach($_value as $v) {
				$v = trim($v);
				if($v) {
					$value[] = $v;
				}
			}
			
			return $value;
		}
		
		return $this->allowed_extensions;
	}
	
	public function getBlacklist( bool $as_array=false ): string|array
	{
		if($as_array) {
			$_value = explode("\n", $this->blacklist);
			$value = [];
			foreach($_value as $v) {
				$v = trim($v);
				if($v) {
					$value[] = rtrim($v, '/').'/';
				}
			}
			
			return $value;
		}
		
		return $this->blacklist;
	}
	
	public function setServerURL( string $server_URL ): void
	{
		$this->server_URL = $server_URL;
	}
	
	public function setServerKey( string $server_key ): void
	{
		$this->server_key = $server_key;
	}
	
	public function setAllowedExtensions( string $allowed_extensions ): void
	{
		$this->allowed_extensions = $allowed_extensions;
	}
	
	public function setBlacklist( string $blacklist ): void
	{
		$this->blacklist = $blacklist;
	}
	
}