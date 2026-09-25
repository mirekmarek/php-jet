<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\SysConf_Path;

class AppConfig {
	public static function getRootDir() : string
	{
		return SysConf_Path::getConfig() . 'application/';
	}
}