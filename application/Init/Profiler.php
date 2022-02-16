<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */


use Jet\SysConf_Path;
use Jet\SysConf_Jet_Debug;

require SysConf_Path::getLibrary() . 'Jet/Debug/Profiler.php';

if( SysConf_Jet_Debug::getProfilerEnabled() ) {
	$profiler_controller_path = SysConf_Path::getBase() . '_profiler/Controller.php';
	if(file_exists($profiler_controller_path)) {
		require $profiler_controller_path;
	}
}
