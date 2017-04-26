<?php
/**
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package Application
 * @subpackage Application_Modules
 */
namespace Jet;



class Application_ModulesTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var Application_Modules_Handler
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->object = new Application_Modules_Handler(
			JET_TESTS_DATA.'Application/Modules/TestModules/',
			JET_TESTS_TMP.'modules_list.php',
			'JetApplicationModule',
			'Jet\Application_Modules_Module_Manifest'
		);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@unlink( JET_TESTS_TMP.'modules_list.php' );
		//@unlink( JET_TESTS_TMP.'module-install-test' );
	}



	/**
	 * @covers \Jet\Application_Modules_Handler::checkModuleNameFormat
	 */
	public function testCheckModuleNameFormat() {
		$this->assertTrue( $this->object->checkModuleNameFormat('Vendor.ValidModuleName123') );

		$this->assertFalse( $this->object->checkModuleNameFormat('.Vendor.ValidModuleName123') );
		$this->assertFalse( $this->object->checkModuleNameFormat('Vendor.ValidModuleName123.') );
		$this->assertFalse( $this->object->checkModuleNameFormat('Vendor..ValidModuleName123') );
		$this->assertFalse( $this->object->checkModuleNameFormat('Sh') );
		$this->assertFalse( $this->object->checkModuleNameFormat('LongLongLongLongLongLongLongLongLongLongLongLongLon') );
		$this->assertFalse( $this->object->checkModuleNameFormat('%^&*(%#.') );
	}


	/**
	 * @covers \Jet\Application_Modules_Handler::getAllModulesList
	 */
	public function testGetAllModulesList() {
		$valid_data = [
			'Vendor.Package.TestModule' =>
			Application_Modules_Module_Manifest::__set_state([
				'name' => 'Vendor.Package.TestModule',
				'vendor' => 'Vendor',
				'label' => 'Test Module 1',
				'description' => 'Test module 1...',
				'API_version' => 201401,
				'types' =>
				[
					0 => 'general',
				],
				'require' =>
				[
				],
				'signals_callbacks' =>
				[
					'/test/ack' => 'testAck',
				],
				'module_dir' => '',
				'is_installed' => false,
				'is_activated' => false,
			]),
			'Vendor.Package.TestModule2' =>
			Application_Modules_Module_Manifest::__set_state([
				'name' => 'Vendor.Package.TestModule2',
				'vendor' => 'Vendor',
				'label' => 'Test Module 2',
				'description' => 'Test module 2...',
				'API_version' => 201401,
				'types' =>
				[
					0 => 'general',
				],
				'require' =>
				[
					0 => 'Vendor.Package.TestModule',
				],
				'signals_callbacks' =>
				[
				],
				'module_dir' => '',
				'is_installed' => false,
				'is_activated' => false,
			]),
			'ValidModule' =>
			Application_Modules_Module_Manifest::__set_state([
				'name' => 'ValidModule',
				'label' => 'Test Module',
				'description' => 'Unit test module',
				'API_version' => 201401,
				'types' =>
				[
					0 => 'general',
				],
				'require' =>
				[
					0 => 'RequireModule1',
					1 => 'RequireModule2',
				],
				'signals_callbacks' =>
				[
					'/test/signal1' => 'CallbackModuleMethodName1',
					'/test/signal2' => 'CallbackModuleMethodName2',
				],
				'module_dir' => '',
				'is_installed' => false,
				'is_activated' => false,
			]),
		];

		$this->assertEquals( $valid_data, $this->object->getAllModulesList( true ) );
	}

	/**
	 * @covers \Jet\Application_Modules_Handler::getModuleExists
	 */
	public function testGetModuleExists() {
		$this->assertTrue( $this->object->getModuleExists('ValidModule') );
		$this->assertFalse( $this->object->getModuleExists('ImaginaryModule') );
	}

	/**
	 * @covers \Jet\Application_Modules_Handler::getModuleManifest
	 */
	public function testGetModuleInfo() {
		$this->assertNull( $this->object->getModuleManifest('ImaginaryModule') );

		$valid_data = Application_Modules_Module_Manifest::__set_state([
			'name' => 'ValidModule',
			'label' => 'Test Module',
			'description' => 'Unit test module',
			'API_version' => 201401,
			'types' =>
			[
				0 => 'general',
			],
			'require' =>
			[
				0 => 'RequireModule1',
				1 => 'RequireModule2',
			],
			'signals_callbacks' =>
			[
				'/test/signal1' => 'CallbackModuleMethodName1',
				'/test/signal2' => 'CallbackModuleMethodName2',
			],
			'module_dir' => '',
			'is_installed' => false,
			'is_activated' => false,
		]);

		$this->assertEquals($valid_data, $this->object->getModuleManifest('ValidModule'));
	}

	/**
	 * @covers \Jet\Application_Modules_Handler::installModule
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_DEPENDENCIES_ERROR
	 */
	public function testInstallModuleFailedRequire() {
		$this->object->installModule('ValidModule');
	}

	/**
	 * @covers \Jet\Application_Modules_Handler::getModuleIsInstalled
	 * @covers \Jet\Application_Modules_Handler::installModule
	 * @covers \Jet\Application_Modules_Handler::getInstalledModulesList
	 * @covers \Jet\Application_Modules_Handler::getModuleIsActivated
	 * @covers \Jet\Application_Modules_Handler::activateModule
	 * @covers \Jet\Application_Modules_Handler::deactivateModule
	 * @covers \Jet\Application_Modules_Handler::getActivatedModulesList
	 * @covers \Jet\Application_Modules_Handler::uninstallModule
	 * @covers \Jet\Application_Modules_Handler::getInstallationInProgress
	 *
	 */
	public function testInstallUninstall() {
		$this->assertFalse( $this->object->getModuleIsInstalled('Vendor.Package.TestModule') );
		$this->assertFalse( $this->object->getModuleIsInstalled('Vendor.Package.TestModule2') );

		$this->object->installModule('Vendor.Package.TestModule');
		$this->object->installModule('Vendor.Package.TestModule2');

		$this->assertTrue( $this->object->getModuleIsInstalled('Vendor.Package.TestModule') );
		$this->assertTrue( $this->object->getModuleIsInstalled('Vendor.Package.TestModule2') );

		$this->assertTrue( file_exists(JET_TESTS_TMP.'module-install-test') );

		$valid_data = [
			'Vendor.Package.TestModule' =>
			Application_Modules_Module_Manifest::__set_state([
				'name' => 'Vendor.Package.TestModule',
				'vendor' => 'Vendor',
				'label' => 'Test Module 1',
				'description' => 'Test module 1...',
				'API_version' => 201401,
				'types' =>
				[
					0 => 'general',
				],
				'require' =>
				[
				],
				'signals_callbacks' =>
				[
					'/test/ack' => 'testAck',
				],
				'module_dir' => '',
				'is_installed' => true,
				'is_activated' => false,
			]),
			'Vendor.Package.TestModule2' =>
			Application_Modules_Module_Manifest::__set_state([
				'name' => 'Vendor.Package.TestModule2',
				'vendor' => 'Vendor',
				'label' => 'Test Module 2',
				'description' => 'Test module 2...',
				'API_version' => 201401,
				'types' =>
				[
					0 => 'general',
				],
				'require' =>
				[
					0 => 'Vendor.Package.TestModule',
				],
				'signals_callbacks' =>
				[
				],
				'module_dir' => '',
				'is_installed' => true,
				'is_activated' => false,
			]),
		];
		$this->assertEquals( $valid_data, $this->object->getInstalledModulesList() );

		$this->assertFalse( $this->object->getModuleIsActivated('Vendor.Package.TestModule') );
		$this->assertFalse( $this->object->getModuleIsActivated('Vendor.Package.TestModule2') );

		$this->object->activateModule('Vendor.Package.TestModule');
		$this->object->activateModule('Vendor.Package.TestModule2');


		$valid_data = [
			'Vendor.Package.TestModule' =>
			Application_Modules_Module_Manifest::__set_state([
				'name' => 'Vendor.Package.TestModule',
				'vendor' => 'Vendor',
				'label' => 'Test Module 1',
				'description' => 'Test module 1...',
				'API_version' => 201401,
				'types' =>
				[
					0 => 'general',
				],
				'require' =>
				[
				],
				'signals_callbacks' =>
				[
					'/test/ack' => 'testAck',
				],
				'module_dir' => '',
				'is_installed' => true,
				'is_activated' => true,
			]),
			'Vendor.Package.TestModule2' =>
			Application_Modules_Module_Manifest::__set_state([
				'name' => 'Vendor.Package.TestModule2',
				'vendor' => 'Vendor',
				'label' => 'Test Module 2',
				'description' => 'Test module 2...',
				'API_version' => 201401,
				'types' =>
				[
					0 => 'general',
				],
				'require' =>
				[
					0 => 'Vendor.Package.TestModule',
				],
				'signals_callbacks' =>
				[
				],
				'module_dir' => '',
				'is_installed' => true,
				'is_activated' => true,
			]),
		];
		$this->assertEquals( $valid_data, $this->object->getActivatedModulesList() );

		$this->assertTrue( $this->object->getModuleIsActivated('Vendor.Package.TestModule') );
		$this->assertTrue( $this->object->getModuleIsActivated('Vendor.Package.TestModule2') );

		$this->object->uninstallModule('Vendor.Package.TestModule2');
		$this->object->uninstallModule('Vendor.Package.TestModule');

		$this->assertFalse( file_exists(JET_TESTS_TMP.'module-install-test') );

		$this->assertEquals( [], $this->object->getActivatedModulesList() );
		$this->assertEquals( [], $this->object->getInstalledModulesList() );

	}


	/**
	 * @covers \Jet\Application_Modules_Handler::reloadModuleManifest
	 */
	public function testReloadModuleManifest() {
		$this->object->installModule('Vendor.Package.TestModule');
		$this->object->reloadModuleManifest( 'Vendor.Package.TestModule' );
		$this->object->uninstallModule('Vendor.Package.TestModule');
	}

	/**
	 * @covers \Jet\Application_Modules_Handler::getModuleInstance
	 * @covers \Jet\Application_Modules_Handler::getInstallationInProgress
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_UNKNOWN_MODULE
	 */
	public function testGetModuleInstanceFailedNotInstalled() {

		$this->object->getModuleInstance('Vendor.Package.TestModule');

	}

	/**
	 * @covers \Jet\Application_Modules_Handler::getModuleInstance
	 * @covers \Jet\Application_Modules_Handler::getInstallationInProgress
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_UNKNOWN_MODULE
	 */
	public function testGetModuleInstanceFailedNotActivated() {

		$this->object->getModuleInstance('Vendor.Package.TestModule');

	}

	/**
	 * @covers \Jet\Application_Modules_Handler::getModuleInstance
	 */
	public function testGetModuleInstance() {
		$this->object->installModule('Vendor.Package.TestModule');
		$this->object->activateModule('Vendor.Package.TestModule');

		$this->object->getModuleInstance('Vendor.Package.TestModule');

		$this->object->uninstallModule('Vendor.Package.TestModule');
	}

}
