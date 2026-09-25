<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Application_Module;

trait AppConfig_ModuleConfig_ModuleHasConfig_Specific_Trait
{
	/**
	 * @var AppConfig_ModuleConfig_Specific[]
	 */
	protected array $configs = [];
	
	public function getSpecificConfig( string $specification_id ) : AppConfig_ModuleConfig_Specific
	{
		/**
		 * @var Application_Module $this
		 */
		if(!isset( $this->configs[$specification_id])) {
			$class_name = $this->module_manifest->getNamespace().'Config_Specific';
			
			$this->configs[$specification_id] = new $class_name( $this->module_manifest, $specification_id );
		}
		
		return $this->configs[$specification_id];
	}
}