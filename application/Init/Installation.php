<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\IO_File;


$installer_path = JET_PATH_BASE.'_installer/install.php';
$install_symptom_file = JET_PATH_DATA.'installed.txt';
if(
	IO_File::exists( $installer_path ) &&
	!IO_File::exists( $install_symptom_file )
) {
	/** @noinspection PhpIncludeInspection */
	require( $installer_path );
	die();
}
