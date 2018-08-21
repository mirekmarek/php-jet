<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

require 'CompatibilityTester.php';

/**
 *
 */
class Installer_Step_SystemCheck_Controller extends Installer_Step_Controller
{

	/**
	 * @var string
	 */
	protected $label = 'Check compatibility';

	/**
	 * @return bool
	 */
	public function getIsAvailable()
	{
		return !Installer_Step_CreateSite_Controller::sitesCreated();
	}

	/**
	 *
	 */
	public function main()
	{
		$this->catchContinue();

		$tester = new Installer_CompatibilityTester();

		$tester->testSystem(
			[
				'test_PHPVersion',
				'test_PDOExtension',
				'test_RequestUriVar',
				'test_INTLExtension',

				'check_GDExtension',
				'check_MaxUploadFileSize'

			]
		);

		$this->view->setVar( 'tester', $tester );

		$this->render( 'default' );
	}

}
