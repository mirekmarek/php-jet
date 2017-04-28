<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Vendor\Package\TestModule;
use Jet\Application_Modules_Module_Abstract;

class Main extends Application_Modules_Module_Abstract {
	public function getMyValue(){
		return 'My value';
	}


	public function testInstall() {
		//echo 'Hello! This is TestModule install script!\n';
		file_put_contents(
			JET_TESTS_TMP.'module-install-test',
			'Hello! This is TestModule install script!\n'
		);
	}

	public function testUninstall() {
		//echo 'Hello! This is TestModule uninstall script!\n';
		unlink(JET_TESTS_TMP.'module-install-test');
	}

}