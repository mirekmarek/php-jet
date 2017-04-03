<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace JetExampleApp;

require JET_EXAMPLE_APP_INSTALLER_PATH.'classes/CompatibilityTester.php';
require JET_EXAMPLE_APP_INSTALLER_PATH.'classes/CompatibilityTester/TestResult.php';

use Jet\Http_Request;
use Jet\Tr;

class Installer_Step_SystemCheck_Controller extends Installer_Step_Controller {


	/**
	 *
	 */
	public function main() {
		if(Http_Request::POST()->exists('go')) {
			Installer::goNext();
		}

		$tester = new CompatibilityTester();

		$tester->testSystem([
			'test_PHPVersion',
			'test_PDOExtension',
			'test_RequestUriVar',

			'check_INTLExtension',
			'check_Redis',
			'check_GDExtension',
			'check_MaxUploadFileSize',
			'check_PHPConfigPaths',

		]);

		$this->view->setVar('tester', $tester);

		$this->render('default');
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		return Tr::_('Check compatibility ', [], 'SystemCheck');
	}
}
