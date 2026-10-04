<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Exports\Manager;


use Jet\Config_Definition;
use Jet\Form_Definition;
use Jet\Form_Definition_Interface;
use Jet\Form_Definition_Trait;
use Jet\Form_Field;
use JetApplication\AppConfig_ModuleConfig_General;
use Jet\Config;
use Jet\Application_Module_Manifest;

#[Config_Definition(
	name: 'Exports'
)]
class Config_General extends AppConfig_ModuleConfig_General implements Form_Definition_Interface {
	use Form_Definition_Trait;
	
	#[Config_Definition(
		type: Config::TYPE_STRING,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		label: 'Key: ',
		is_required: true,
	)]
	protected string $key = '';
	
	
	#[Config_Definition(
		type: Config::TYPE_STRING,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		label: 'MVC Base ID: ',
		is_required: true,
	)]
	protected string $base_id = 'exports';
	
	
	public function __construct( Application_Module_Manifest $module, ?array $data = null )
	{
		parent::__construct( $module, $data );
		if(!$this->key) {
			$this->generateKey();
		}
	}
	
	public function generateKey() : void
	{
		$this->key = sha1( uniqid().uniqid().uniqid().uniqid() );
		
		$this->saveConfigFile();
	}
	
	public function getKey(): string
	{
		if(!$this->key) {
			$this->generateKey();
		}
		
		return $this->key;
	}
	
	public function setKey( string $key ): void
	{
		$this->key = $key;
	}
	

	public function getBaseId(): string
	{
		return $this->base_id;
	}
	

	public function setBaseId( string $base_id ): void
	{
		$this->base_id = $base_id;
	}
	
	

}