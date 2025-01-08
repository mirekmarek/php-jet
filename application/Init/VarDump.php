<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */


use Jet\SysConf_Path;

require SysConf_Path::getLibrary() . 'Jet/Debug/VarDump.php';


$var_dump_controller_path = SysConf_Path::getBase() . '_var_dump/Controller.php';
if(file_exists($var_dump_controller_path)) {
	require $var_dump_controller_path;
}