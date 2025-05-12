<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudioModule\SyncProjectDBClient;

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
		type: Form_Field::TYPE_MULTI_SELECT,
		label: 'Classes:',
		select_options_creator: [
			self::class,
			'getClassesList'
		],
	
	)]
	protected array $selected_classes = [];
	
	#[Form_Definition(
		type: Form_Field::TYPE_CHECKBOX,
		label: 'Add',
	)]
	protected bool $perform_add = true;
	
	#[Form_Definition(
		type: Form_Field::TYPE_CHECKBOX,
		label: 'Update',
	)]
	protected bool $perform_update = true;
	
	#[Form_Definition(
		type: Form_Field::TYPE_CHECKBOX,
		label: 'Delete',
	)]
	protected bool $perform_delete = true;
	
	
	
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
		return new Session('SyncProjectDBClient');
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
	
	public function getSelectedClasses(): array
	{
		return $this->selected_classes;
	}
	
	public function getPerformAdd(): bool
	{
		return $this->perform_add;
	}
	
	public function getPerformUpdate(): bool
	{
		return $this->perform_update;
	}
	
	public function getPerformDelete(): bool
	{
		return $this->perform_delete;
	}
	
	public function setServerURL( string $server_URL ): void
	{
		$this->server_URL = $server_URL;
	}
	
	public function setServerKey( string $server_key ): void
	{
		$this->server_key = $server_key;
	}
	
	public function setSelectedClasses( array $selected_classes ): void
	{
		$this->selected_classes = $selected_classes;
	}
	
	public function setPerformAdd( bool $perform_add ): void
	{
		$this->perform_add = $perform_add;
	}
	
	public function setPerformUpdate( bool $perform_update ): void
	{
		$this->perform_update = $perform_update;
	}
	
	public function setPerformDelete( bool $perform_delete ): void
	{
		$this->perform_delete = $perform_delete;
	}
	
	
}