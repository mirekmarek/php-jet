<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @var Application_Modules_Handler_Default
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->object = new Application_Modules_Handler_Default(
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
		@unlink( JET_TESTS_TMP.'modules_list.php' );
		//@unlink( JET_TESTS_TMP.'module-install-test' );
	}



	/**
	 * @covers Jet\Application_Modules_Handler_Default::checkModuleNameFormat
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
	 * @covers Jet\Application_Modules_Handler_Default::getAllModulesList
	 */
	public function testGetAllModulesList() {
		$valid_data = array (
			'Vendor.Package.TestModule' =>
			Application_Modules_Module_Manifest::__set_state(array(
				'name' => 'Vendor.Package.TestModule',
				'vendor' => 'Vendor',
				'label' => 'Test Module 1',
				'description' => 'Test module 1...',
				'API_version' => 201401,
				'types' =>
				array (
					0 => 'general',
				),
				'require' =>
				array (
				),
				'factory_overload_map' =>
				array (
				),
				'signals_callbacks' =>
				array (
					'/test/ack' => 'testAck',
				),
				'module_dir' => '',
				'is_installed' => false,
				'is_activated' => false,
			)),
			'Vendor.Package.TestModule2' =>
			Application_Modules_Module_Manifest::__set_state(array(
				'name' => 'Vendor.Package.TestModule2',
				'vendor' => 'Vendor',
				'label' => 'Test Module 2',
				'description' => 'Test module 2...',
				'API_version' => 201401,
				'types' =>
				array (
					0 => 'general',
				),
				'require' =>
				array (
					0 => 'Vendor.Package.TestModule',
				),
				'factory_overload_map' =>
				array (
				),
				'signals_callbacks' =>
				array (
				),
				'module_dir' => '',
				'is_installed' => false,
				'is_activated' => false,
			)),
			'ValidModule' =>
			Application_Modules_Module_Manifest::__set_state(array(
				'name' => 'ValidModule',
				'label' => 'Test Module',
				'description' => 'Unit test module',
				'API_version' => 201401,
				'types' =>
				array (
					0 => 'general',
				),
				'require' =>
				array (
					0 => 'RequireModule1',
					1 => 'RequireModule2',
				),
				'factory_overload_map' =>
				array (
					'OldClass1' => 'MyClass1',
					'OldClass2' => 'MyClass2',
					'OldClass3' => 'MyClass3',
				),
				'signals_callbacks' =>
				array (
					'/test/signal1' => 'CallbackMoeduleMethodName1',
					'/test/signal2' => 'CallbackMoeduleMethodName2',
				),
				'module_dir' => '',
				'is_installed' => false,
				'is_activated' => false,
			)),
		);

		$this->assertEquals( $valid_data, $this->object->getAllModulesList( true ) );
	}

	/**
	 * @covers Jet\Application_Modules_Handler_Default::getModuleExists
	 */
	public function testGetModuleExists() {
		$this->assertTrue( $this->object->getModuleExists('ValidModule') );
		$this->assertFalse( $this->object->getModuleExists('ImaginaryModule') );
	}

	/**
	 * @covers Jet\Application_Modules_Handler_Default::getModuleManifest
	 */
	public function testGetModuleInfo() {
		$this->assertNull( $this->object->getModuleManifest('ImaginaryModule') );

		$valid_data = Application_Modules_Module_Manifest::__set_state(array(
			'name' => 'ValidModule',
			'label' => 'Test Module',
			'description' => 'Unit test module',
			'API_version' => 201401,
			'types' =>
			array (
				0 => 'general',
			),
			'require' =>
			array (
				0 => 'RequireModule1',
				1 => 'RequireModule2',
			),
			'factory_overload_map' =>
			array (
				'OldClass1' => 'MyClass1',
				'OldClass2' => 'MyClass2',
				'OldClass3' => 'MyClass3',
			),
			'signals_callbacks' =>
			array (
				'/test/signal1' => 'CallbackMoeduleMethodName1',
				'/test/signal2' => 'CallbackMoeduleMethodName2',
			),
			'module_dir' => '',
			'is_installed' => false,
			'is_activated' => false,
		));

		$this->assertEquals($valid_data, $this->object->getModuleManifest('ValidModule'));
	}

	/**
	 * @covers Jet\Application_Modules_Handler_Default::installModule
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_DEPENDENCIES_ERROR
	 */
	public function testInstallModuleFailedRequire() {
		$this->object->installModule('ValidModule');
	}

	/**
	 * @covers Jet\Application_Modules_Handler_Default::getModuleIsInstalled
	 * @covers Jet\Application_Modules_Handler_Default::installModule
	 * @covers Jet\Application_Modules_Handler_Default::getInstalledModulesList
	 * @covers Jet\Application_Modules_Handler_Default::getModuleIsActivated
	 * @covers Jet\Application_Modules_Handler_Default::activateModule
	 * @covers Jet\Application_Modules_Handler_Default::deactivateModule
	 * @covers Jet\Application_Modules_Handler_Default::getActivatedModulesList
	 * @covers Jet\Application_Modules_Handler_Default::uninstallModule
	 * @covers Jet\Application_Modules_Handler_Default::getInstallationInProgress
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

		$valid_data = array (
			'Vendor.Package.TestModule' =>
			Application_Modules_Module_Manifest::__set_state(array(
				'name' => 'Vendor.Package.TestModule',
				'vendor' => 'Vendor',
				'label' => 'Test Module 1',
				'description' => 'Test module 1...',
				'API_version' => 201401,
				'types' =>
				array (
					0 => 'general',
				),
				'require' =>
				array (
				),
				'factory_overload_map' =>
				array (
				),
				'signals_callbacks' =>
				array (
					'/test/ack' => 'testAck',
				),
				'module_dir' => '',
				'is_installed' => true,
				'is_activated' => false,
			)),
			'Vendor.Package.TestModule2' =>
			Application_Modules_Module_Manifest::__set_state(array(
				'name' => 'Vendor.Package.TestModule2',
				'vendor' => 'Vendor',
				'label' => 'Test Module 2',
				'description' => 'Test module 2...',
				'API_version' => 201401,
				'types' =>
				array (
					0 => 'general',
				),
				'require' =>
				array (
					0 => 'Vendor.Package.TestModule',
				),
				'factory_overload_map' =>
				array (
				),
				'signals_callbacks' =>
				array (
				),
				'module_dir' => '',
				'is_installed' => true,
				'is_activated' => false,
			)),
		);
		$this->assertEquals( $valid_data, $this->object->getInstalledModulesList() );

		$this->assertFalse( $this->object->getModuleIsActivated('Vendor.Package.TestModule') );
		$this->assertFalse( $this->object->getModuleIsActivated('Vendor.Package.TestModule2') );

		$this->object->activateModule('Vendor.Package.TestModule');
		$this->object->activateModule('Vendor.Package.TestModule2');


		$valid_data = array (
			'Vendor.Package.TestModule' =>
			Application_Modules_Module_Manifest::__set_state(array(
				'name' => 'Vendor.Package.TestModule',
				'vendor' => 'Vendor',
				'label' => 'Test Module 1',
				'description' => 'Test module 1...',
				'API_version' => 201401,
				'types' =>
				array (
					0 => 'general',
				),
				'require' =>
				array (
				),
				'factory_overload_map' =>
				array (
				),
				'signals_callbacks' =>
				array (
					'/test/ack' => 'testAck',
				),
				'module_dir' => '',
				'is_installed' => true,
				'is_activated' => true,
			)),
			'Vendor.Package.TestModule2' =>
			Application_Modules_Module_Manifest::__set_state(array(
				'name' => 'Vendor.Package.TestModule2',
				'vendor' => 'Vendor',
				'label' => 'Test Module 2',
				'description' => 'Test module 2...',
				'API_version' => 201401,
				'types' =>
				array (
					0 => 'general',
				),
				'require' =>
				array (
					0 => 'Vendor.Package.TestModule',
				),
				'factory_overload_map' =>
				array (
				),
				'signals_callbacks' =>
				array (
				),
				'module_dir' => '',
				'is_installed' => true,
				'is_activated' => true,
			)),
		);
		$this->assertEquals( $valid_data, $this->object->getActivatedModulesList() );

		$this->assertTrue( $this->object->getModuleIsActivated('Vendor.Package.TestModule') );
		$this->assertTrue( $this->object->getModuleIsActivated('Vendor.Package.TestModule2') );

		$this->object->uninstallModule('Vendor.Package.TestModule2');
		$this->object->uninstallModule('Vendor.Package.TestModule');

		$this->assertFalse( file_exists(JET_TESTS_TMP.'module-install-test') );

		$this->assertEquals( array(), $this->object->getActivatedModulesList() );
		$this->assertEquals( array(), $this->object->getInstalledModulesList() );

	}


	/**
	 * @covers Jet\Application_Modules_Handler_Default::reloadModuleManifest
	 */
	public function testReloadModuleManifest() {
		$this->object->installModule('Vendor.Package.TestModule');
		$this->object->reloadModuleManifest( 'Vendor.Package.TestModule' );
		$this->object->uninstallModule('Vendor.Package.TestModule');
	}

	/**
	 * @covers Jet\Application_Modules_Handler_Default::getModuleInstance
	 * @covers Jet\Application_Modules_Handler_Default::getInstallationInProgress
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_UNKNOWN_MODULE
	 */
	public function testGetModuleInstanceFailedNotInstalled() {

		$this->object->getModuleInstance('Vendor.Package.TestModule');

	}

	/**
	 * @covers Jet\Application_Modules_Handler_Default::getModuleInstance
	 * @covers Jet\Application_Modules_Handler_Default::getInstallationInProgress
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_UNKNOWN_MODULE
	 */
	public function testGetModuleInstanceFailedNotActivated() {

		$this->object->getModuleInstance('Vendor.Package.TestModule');

	}

	/**
	 * @covers Jet\Application_Modules_Handler_Default::getModuleInstance
	 */
	public function testGetModuleInstance() {
		$this->object->installModule('Vendor.Package.TestModule');
		$this->object->activateModule('Vendor.Package.TestModule');

		$this->object->getModuleInstance('Vendor.Package.TestModule');

		$this->object->uninstallModule('Vendor.Package.TestModule');
	}

}
