<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace Jet;

require 'init/init.php';

if(php_sapi_name()!='cli') {
	die('CLI only');
}

$username = '';
$password = '';


echo PHP_EOL. 'Enter username: ';
$username = readline('');

echo 'Password: ';
$password = readline('');


if($username && $password) {
	IO_File::writeDataAsPhp( SysConf_Path::getData() . '_jet_studio_access.php', [
		'username' => $username,
		'password' => password_hash( $password, PASSWORD_DEFAULT )
	] );

	echo PHP_EOL.PHP_EOL. 'Done - JetStudio Access Reset' .PHP_EOL.PHP_EOL;
}
