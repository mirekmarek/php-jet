<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace JetExampleApp;


/** @noinspection PhpIncludeInspection */
require JET_APP_INSTALLER_PATH.'classes/CompatibilityTester.php';
/** @noinspection PhpIncludeInspection */
require JET_APP_INSTALLER_PATH.'classes/CompatibilityTester/TestResult.php';

use Jet\Http_Request;
use Jet\Mvc_Site;

class Installer_Step_SystemCheck_Controller extends Installer_Step_Controller {

	/**
	 * @var string
	 */
	protected $label = 'Check compatibility';

	/**
	 * @return bool
	 */
	public function getIsAvailable()
	{
		return count(Mvc_Site::getList() )==0;
	}

	/**
	 *
	 */
	public function main() {
		if(Http_Request::POST()->exists('go')) {
			Installer::goToNext();
		}

		$tester = new CompatibilityTester();

		$tester->testSystem([
			'test_PHPVersion',
			'test_PDOExtension',
			'test_RequestUriVar',

			'check_INTLExtension',
			'check_GDExtension',
			'check_MaxUploadFileSize',
			'check_PHPConfigPaths',

		]);

		$this->view->setVar('tester', $tester);

		$this->render('default');
	}

}
