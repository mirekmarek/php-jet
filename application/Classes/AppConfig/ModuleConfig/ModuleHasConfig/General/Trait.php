<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;


use Jet\Application_Module;
/** @phpstan-ignore trait.unused */
trait AppConfig_ModuleConfig_ModuleHasConfig_General_Trait
{
	/**
	 * @var AppConfig_ModuleConfig_General|null
	 */
	protected ?AppConfig_ModuleConfig_General $general_config = null;
	
	public function getGeneralConfig() : AppConfig_ModuleConfig_General
	{
		/**
		 * @var Application_Module $this
		 */
		if(!$this->general_config ) {
			$class_name = $this->module_manifest->getNamespace().'Config_General';
			$this->general_config = new $class_name( $this->module_manifest );
			
		}
		
		return $this->general_config;
	}
}