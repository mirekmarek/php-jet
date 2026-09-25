<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;


interface AppConfig_ModuleConfig_ModuleHasConfig_General_Interface
{
	public function getGeneralConfig() : AppConfig_ModuleConfig_General;
}