<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

trait AppConfig_ModuleConfig_ModuleHasConfig_Specific_Trait
{
	/**
	 * @var array<string,AppConfig_ModuleConfig_Specific>
	 */
	protected array $configs = [];
	
	public function getSpecificConfig( string $specification_id ) : AppConfig_ModuleConfig_Specific
	{
		if(!isset( $this->configs[$specification_id])) {
			$class_name = $this->module_manifest->getNamespace().'Config_Specific';
			/** @var AppConfig_ModuleConfig_Specific $cfg */
			$cfg = new $class_name( $this->module_manifest, $specification_id );
			$this->configs[$specification_id] = $cfg;
		}
		
		return $this->configs[$specification_id];
	}
}