<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Application_Module_Manifest;
use Jet\Config;
use Jet\IO_File;


abstract class AppConfig_ModuleConfig_General extends Config {
	
	protected Application_Module_Manifest $module;
	
	public function __construct( Application_Module_Manifest $module, ?array $data = null )
	{
		$this->module = $module;
		
		if($data===null) {
			$this->_config_file_path = AppConfig::getRootDir().$module->getName() . '/general.php';
			
			if(!IO_File::exists($this->_config_file_path)) {
				$this->saveConfigFile();
			}
		}
		
		if( $data === null ) {
			$data = $this->readConfigFileData();
		}
		
		$this->setData( $data );
		
	}
}