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

if(!defined('JET_MODULES_PATH'))
define('JET_MODULES_PATH', JET_TESTS_DATA.'Application/Modules/TestModules/');


class Application_ModulesTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		Application_Modules::setModulesListFilePath( JET_TESTS_TMP.'modules_list_test.php' );
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
		@unlink( JET_TESTS_TMP.'modules_list_test.php' );
		Application_Modules::_resetInternalState();
		@unlink( JET_TESTS_TMP.'module-install-test' );
	}

	/**
	 * @covers Jet\Application_Modules::setModulesListFilePath
	 * @covers Jet\Application_Modules::getModulesListFilePath
	 */
	public function testGetSetModulesListFilePath() {
		$path = JET_TESTS_TMP.'test_set_list_path';
		Application_Modules::setModulesListFilePath( $path );
		$this->assertEquals( $path, Application_Modules::getModulesListFilePath() );
	}


	/**
	 * @covers Jet\Application_Modules::checkModuleNameFormat
	 */
	public function testCheckModuleNameFormat() {
		$this->assertTrue( Application_Modules::checkModuleNameFormat('Vendor\\ValidModuleName123') );

		$this->assertFalse( Application_Modules::checkModuleNameFormat('\\Vendor\\ValidModuleName123') );
		$this->assertFalse( Application_Modules::checkModuleNameFormat('Vendor\\ValidModuleName123\\') );
		$this->assertFalse( Application_Modules::checkModuleNameFormat('Vendor\\\\ValidModuleName123') );
		$this->assertFalse( Application_Modules::checkModuleNameFormat('Sh') );
		$this->assertFalse( Application_Modules::checkModuleNameFormat('LongLongLongLongLongLongLongLongLongLongLongLongLon') );
		$this->assertFalse( Application_Modules::checkModuleNameFormat('%^&*(%#.') );
	}


	/**
	 * @covers Jet\Application_Modules::getAllModulesList
	 */
	public function testGetAllModulesList() {
		$valid_data = array (
			'Vendor\\Package\\TestModule' =>
			Application_Modules_Module_Info::__set_state(array(
				'name' => 'Vendor\Package\TestModule',
				'vendor' => 'Vendor',
				'label' => 'Test Module 1',
				'description' => 'Test module 1...',
				'API_version' => 201208,
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
				'signals' =>
				array (
					'/test/received' => 'Test signal 1',
					'/test/multiple' => 'Test signal 2',
				),
				'module_dir' => '',
				'is_installed' => false,
				'is_activated' => false,
			)),
			'Vendor\\Package\\TestModule2' =>
			Application_Modules_Module_Info::__set_state(array(
				'name' => 'Vendor\Package\TestModule2',
				'vendor' => 'Vendor',
				'label' => 'Test Module 2',
				'description' => 'Test module 2...',
				'API_version' => 201208,
				'types' =>
				array (
					0 => 'general',
				),
				'require' =>
				array (
					0 => 'Vendor\\Package\\TestModule',
				),
				'factory_overload_map' =>
				array (
				),
				'signals_callbacks' =>
				array (
				),
				'signals' =>
				array (
				),
				'module_dir' => '',
				'is_installed' => false,
				'is_activated' => false,
			)),
			'ValidModule' =>
			Application_Modules_Module_Info::__set_state(array(
				'name' => 'ValidModule',
				'label' => 'Test Module',
				'description' => 'Unit test module',
				'API_version' => 201208,
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
				'signals' =>
				array (
					'/test/signal1' => 'Test signal 1',
					'/test/signal2' => 'Test signal 2',
				),
				'module_dir' => '',
				'is_installed' => false,
				'is_activated' => false,
			)),
		);

		$this->assertEquals( $valid_data, Application_Modules::getAllModulesList( true ) );
	}

	/**
	 * @covers Jet\Application_Modules::getModuleExists
	 */
	public function testGetModuleExists() {
		$this->assertTrue( Application_Modules::getModuleExists('ValidModule') );
		$this->assertFalse( Application_Modules::getModuleExists('ImaginaryModule') );
	}

	/**
	 * @covers Jet\Application_Modules::getModuleInfo
	 */
	public function testGetModuleInfo() {
		$this->assertNull( Application_Modules::getModuleInfo('ImaginaryModule') );

		$valid_data = Application_Modules_Module_Info::__set_state(array(
			'name' => 'ValidModule',
			'label' => 'Test Module',
			'description' => 'Unit test module',
			'API_version' => 201208,
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
			'signals' =>
			array (
				'/test/signal1' => 'Test signal 1',
				'/test/signal2' => 'Test signal 2',
			),
			'module_dir' => '',
			'is_installed' => false,
			'is_activated' => false,
		));

		$this->assertEquals($valid_data, Application_Modules::getModuleInfo('ValidModule'));
	}

	/**
	 * @covers Jet\Application_Modules::installModule
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_DEPENDENCIES_ERROR
	 */
	public function testInstallModuleFailedRequire() {
		Application_Modules::installModule('ValidModule');
	}

	/**
	 * @covers Jet\Application_Modules::getModuleIsInstalled
	 * @covers Jet\Application_Modules::installModule
	 * @covers Jet\Application_Modules::getInstalledModulesList
	 * @covers Jet\Application_Modules::getModuleIsActivated
	 * @covers Jet\Application_Modules::activateModule
	 * @covers Jet\Application_Modules::deactivateModule
	 * @covers Jet\Application_Modules::getActivatedModulesList
	 * @covers Jet\Application_Modules::uninstallModule
	 * @covers Jet\Application_Modules::getInstallationInProgress
	 *
	 */
	public function testInstallUninstall() {
		$this->assertFalse( Application_Modules::getModuleIsInstalled('Vendor\\Package\\TestModule') );
		$this->assertFalse( Application_Modules::getModuleIsInstalled('Vendor\\Package\\TestModule2') );

		Application_Modules::installModule('Vendor\\Package\\TestModule');
		Application_Modules::installModule('Vendor\\Package\\TestModule2');

		$this->assertTrue( Application_Modules::getModuleIsInstalled('Vendor\\Package\\TestModule') );
		$this->assertTrue( Application_Modules::getModuleIsInstalled('Vendor\\Package\\TestModule2') );

		$this->assertTrue( file_exists(JET_TESTS_TMP.'module-install-test') );

		$valid_data = array (
			'Vendor\\Package\\TestModule' =>
			Application_Modules_Module_Info::__set_state(array(
				'name' => 'Vendor\\Package\\TestModule',
				'vendor' => 'Vendor',
				'label' => 'Test Module 1',
				'description' => 'Test module 1...',
				'API_version' => 201208,
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
				'signals' =>
				array (
					'/test/received' => 'Test signal 1',
					'/test/multiple' => 'Test signal 2',
				),
				'module_dir' => '',
				'is_installed' => true,
				'is_activated' => false,
			)),
			'Vendor\\Package\\TestModule2' =>
			Application_Modules_Module_Info::__set_state(array(
				'name' => 'Vendor\\Package\\TestModule2',
				'vendor' => 'Vendor',
				'label' => 'Test Module 2',
				'description' => 'Test module 2...',
				'API_version' => 201208,
				'types' =>
				array (
					0 => 'general',
				),
				'require' =>
				array (
					0 => 'Vendor\\Package\\TestModule',
				),
				'factory_overload_map' =>
				array (
				),
				'signals_callbacks' =>
				array (
				),
				'signals' =>
				array (
				),
				'module_dir' => '',
				'is_installed' => true,
				'is_activated' => false,
			)),
		);
		$this->assertEquals( $valid_data, Application_Modules::getInstalledModulesList() );

		$this->assertFalse( Application_Modules::getModuleIsActivated('Vendor\\Package\\TestModule') );
		$this->assertFalse( Application_Modules::getModuleIsActivated('Vendor\\Package\\TestModule2') );

		Application_Modules::activateModule('Vendor\\Package\\TestModule');
		Application_Modules::activateModule('Vendor\\Package\\TestModule2');


		$valid_data = array (
			'Vendor\\Package\\TestModule' =>
			Application_Modules_Module_Info::__set_state(array(
				'name' => 'Vendor\\Package\\TestModule',
				'vendor' => 'Vendor',
				'label' => 'Test Module 1',
				'description' => 'Test module 1...',
				'API_version' => 201208,
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
				'signals' =>
				array (
					'/test/received' => 'Test signal 1',
					'/test/multiple' => 'Test signal 2',
				),
				'module_dir' => '',
				'is_installed' => true,
				'is_activated' => true,
			)),
			'Vendor\\Package\\TestModule2' =>
			Application_Modules_Module_Info::__set_state(array(
				'name' => 'Vendor\\Package\\TestModule2',
				'vendor' => 'Vendor',
				'label' => 'Test Module 2',
				'description' => 'Test module 2...',
				'API_version' => 201208,
				'types' =>
				array (
					0 => 'general',
				),
				'require' =>
				array (
					0 => 'Vendor\\Package\\TestModule',
				),
				'factory_overload_map' =>
				array (
				),
				'signals_callbacks' =>
				array (
				),
				'signals' =>
				array (
				),
				'module_dir' => '',
				'is_installed' => true,
				'is_activated' => true,
			)),
		);
		$this->assertEquals( $valid_data, Application_Modules::getActivatedModulesList() );

		$this->assertTrue( Application_Modules::getModuleIsActivated('Vendor\\Package\\TestModule') );
		$this->assertTrue( Application_Modules::getModuleIsActivated('Vendor\\Package\\TestModule2') );

		Application_Modules::uninstallModule('Vendor\\Package\\TestModule2');
		Application_Modules::uninstallModule('Vendor\\Package\\TestModule');

		$this->assertFalse( file_exists(JET_TESTS_TMP.'module-install-test') );

		$this->assertEquals( array(), Application_Modules::getActivatedModulesList() );
		$this->assertEquals( array(), Application_Modules::getInstalledModulesList() );

	}


	/**
	 * @covers Jet\Application_Modules::reloadModuleManifest
	 */
	public function testReloadModuleManifest() {
		Application_Modules::installModule('Vendor\\Package\\TestModule');
		Application_Modules::reloadModuleManifest( 'Vendor\\Package\\TestModule' );
		Application_Modules::uninstallModule('Vendor\\Package\\TestModule');
	}

	/**
	 * @covers Jet\Application_Modules::getModuleInstance
	 * @covers Jet\Application_Modules::getInstallationInProgress
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_UNKNOWN_MODULE
	 */
	public function testGetModuleInstanceFailedNotInstalled() {

		Application_Modules::getModuleInstance('Vendor\\Package\\TestModule');

	}

	/**
	 * @covers Jet\Application_Modules::getModuleInstance
	 * @covers Jet\Application_Modules::getInstallationInProgress
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_UNKNOWN_MODULE
	 */
	public function testGetModuleInstanceFailedNotActivated() {

		Application_Modules::getModuleInstance('Vendor\\Package\\TestModule');

	}

	/**
	 * @covers Jet\Application_Modules::getModuleInstance
	 */
	public function testGetModuleInstance() {
		Application_Modules::installModule('Vendor\\Package\\TestModule');
		Application_Modules::activateModule('Vendor\\Package\\TestModule');

		Application_Modules::getModuleInstance('Vendor\\Package\\TestModule');

		Application_Modules::uninstallModule('Vendor\\Package\\TestModule');
	}

}
