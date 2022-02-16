<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\IO_File;
use Jet\SysConf_Path;


$installer_path = SysConf_Path::getBase() . '_installer/install.php';
$install_symptom_file = SysConf_Path::getData() . 'installed.txt';
if(
	IO_File::exists( $installer_path ) &&
	!IO_File::exists( $install_symptom_file )
) {
	require $installer_path;
	die();
}
