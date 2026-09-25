<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Application_Module_Manifest;
use Jet\IO_File;

abstract class AppConfig_ModuleConfig_Specific extends AppConfig_ModuleConfig_General {
	
	protected string $specification_id;
	
	public function __construct( Application_Module_Manifest $module, string $specification_id, ?array $data = null )
	{
		$this->module = $module;
		$this->specification_id = $specification_id;
		
		if($data===null) {
			$this->_config_file_path = AppConfig::getRootDir().$module->getName().'/' . $specification_id . '.php';
			
			if(!IO_File::exists($this->_config_file_path)) {
				$this->initNewConfigFile();
			}
		}
		
		if( $data === null ) {
			$data = $this->readConfigFileData();
		}
		
		$this->setData( $data );
		
	}
	
	public function getSpecificationId(): string
	{
		return $this->specification_id;
	}
	
	
	
	protected function initNewConfigFile() : void
	{
		$this->saveConfigFile();
	}
}