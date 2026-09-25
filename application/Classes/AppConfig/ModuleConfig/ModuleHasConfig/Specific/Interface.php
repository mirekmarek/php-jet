<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

interface AppConfig_ModuleConfig_ModuleHasConfig_Specific_Interface
{
	public function getSpecificConfig( string $specification_id ) : AppConfig_ModuleConfig_Specific;
}