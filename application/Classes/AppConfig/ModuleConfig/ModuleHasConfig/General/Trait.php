<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;


/** @phpstan-ignore trait.unused */
trait AppConfig_ModuleConfig_ModuleHasConfig_General_Trait
{
	/**
	 * @var AppConfig_ModuleConfig_General|null
	 */
	protected ?AppConfig_ModuleConfig_General $general_config = null;
	
	public function getGeneralConfig() : AppConfig_ModuleConfig_General
	{
		if(!$this->general_config ) {
			$class_name = $this->module_manifest->getNamespace().'Config_General';
			/**
			 * @var AppConfig_ModuleConfig_General $cfg
			 */
			$cfg = new $class_name( $this->module_manifest );
			$this->general_config = $cfg;
			
		}
		
		return $this->general_config;
	}
}