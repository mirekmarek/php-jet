<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

if(!defined('JET_MODULES_PATH'))
define('JET_MODULES_PATH', JET_TESTS_DATA.'Application/Modules/TestModules/');

class Application_Modules_Module_ManifestTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var Application_Modules_Module_Manifest
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
		Application_Modules_Module_Manifest::setModuleTypesList([
			Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL => 'General module',
			Application_Modules_Module_Manifest::MODULE_TYPE_AUTH_CONTROLLER => 'Authentication and Authorization Controller module',
			Application_Modules_Module_Manifest::MODULE_TYPE_SYSTEM => 'System module',
		]);
	}

	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::readManifestData
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_MODULE_DOES_NOT_EXIST
	 */
	public function testReadManifestDataFailedModuleDoesNotExist() {
		new Application_Modules_Module_Manifest('ImaginaryModule');
	}

	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::readManifestData
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_MANIFEST_IS_NOT_READABLE
	 */
	public function testReadManifestDataFailedManifestIsNotReadable() {
		new Application_Modules_Module_Manifest('ModuleWOManifest');
	}

	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::checkManifestData
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_MANIFEST_NONSENSE
	 */
	public function testCheckManifestDataInvalidNotArray() {
		new Application_Modules_Module_Manifest('InvalidManifestNotArray');
	}

	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::checkManifestData
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_MANIFEST_NONSENSE
	 */
	public function testCheckManifestDataInvalidMissingAPIVersion() {
		new Application_Modules_Module_Manifest('InvalidMissingAPIVersion');
	}


	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::checkManifestData
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_MANIFEST_NONSENSE
	 */
	public function testCheckManifestDataInvalidMissingLabel() {
		new Application_Modules_Module_Manifest('InvalidMissingLabel');
	}

	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::checkManifestData
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_MANIFEST_NONSENSE
	 */
	public function testCheckManifestDataInvalidMissingTypes() {
		new Application_Modules_Module_Manifest('InvalidMissingTypes');
	}

	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::checkManifestData
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_MANIFEST_NONSENSE
	 */
	public function testCheckManifestDataInvalidUnknownTypes() {
		new Application_Modules_Module_Manifest('InvalidUnknownTypes');
	}

	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::readManifestData
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_MANIFEST_NONSENSE
	 */
	public function testReadManifestDataInvalidRequireIsNotArray() {
		new Application_Modules_Module_Manifest('InvalidRequireIsNotArray');
	}

	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::readManifestData
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_MANIFEST_NONSENSE
	 */
	public function testReadManifestDataInvalidSignalsCallbacksIsNotArray() {
		new Application_Modules_Module_Manifest('InvalidSignalsCallbacksIsNotArray');
	}

	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::readManifestData
	 */
	public function testReadManifestData() {
		new Application_Modules_Module_Manifest('ValidModule');
	}

	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::getModuleDir
	 */
	public function testGetModuleDir() {
		$module_info = new Application_Modules_Module_Manifest('ValidModule');

		$this->assertEquals( JET_MODULES_PATH. 'ValidModule/', $module_info->getModuleDir() );
	}


	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::getName
	 */
	public function testGetName() {
		$module_info = new Application_Modules_Module_Manifest('ValidModule');
		$this->assertEquals( 'ValidModule', $module_info->getName() );
	}

	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::getLabel
	 */
	public function testGetLabel() {
		$module_info = new Application_Modules_Module_Manifest('ValidModule');
		$this->assertEquals( 'Test Module', $module_info->getLabel() );
	}

	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::getDescription
	 */
	public function testGetDescription() {
		$module_info = new Application_Modules_Module_Manifest('ValidModule');
		$this->assertEquals( 'Unit test module', $module_info->getDescription() );
	}

	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::getAPIVersion
	 */
	public function testGetAPIVersion() {
		$module_info = new Application_Modules_Module_Manifest('ValidModule');
		$this->assertEquals( 201401, $module_info->getAPIVersion() );
	}

	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::getTypes
	 */
	public function testGetTypes() {
		$module_info = new Application_Modules_Module_Manifest('ValidModule');
		$this->assertEquals( [Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL], $module_info->getTypes() );
	}

	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::getHasType
	 */
	public function testGetHasType() {
		$module_info = new Application_Modules_Module_Manifest('ValidModule');

		$this->assertTrue( $module_info->getHasType(Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL) );
		$this->assertFalse( $module_info->getHasType(Application_Modules_Module_Manifest::MODULE_TYPE_SYSTEM) );
	}

	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::getIsAuthController
	 */
	public function testGetIsAuthController() {
		$module_info = new Application_Modules_Module_Manifest('ValidModule');

		$this->assertFalse( $module_info->getIsAuthController() );
	}

	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::getRequire
	 */
	public function testGetRequire() {
		$module_info = new Application_Modules_Module_Manifest('ValidModule');
		$this->assertEquals( [
			'RequireModule1',
			'RequireModule2'
		], $module_info->getRequire() );
	}

	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::getSignalCallbacks
	 */
	public function testGetSignalCallbacks() {
		$module_info = new Application_Modules_Module_Manifest('ValidModule');
		$this->assertEquals( [
			'/test/signal1' => 'CallbackModuleMethodName1',
			'/test/signal2' => 'CallbackModuleMethodName2',
		], $module_info->getSignalCallbacks() );
	}


	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::setIsInstalled
	 * @covers \Jet\Application_Modules_Module_Manifest::getIsInstalled
	 */
	public function testSetGetIsInstalled() {
		$module_info = new Application_Modules_Module_Manifest('ValidModule');
		$this->assertFalse( $module_info->getIsInstalled() );
		$module_info->setIsInstalled(true);
		$this->assertTrue( $module_info->getIsInstalled() );
	}

	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::setIsActivated
	 * @covers \Jet\Application_Modules_Module_Manifest::getIsActivated
	 */
	public function testSetGetIsActivated() {
		$module_info = new Application_Modules_Module_Manifest('ValidModule');
		$this->assertFalse( $module_info->getIsActivated() );
		$module_info->setIsActivated(true);
		$this->assertTrue( $module_info->getIsActivated() );
	}


	/**
	 * @covers \Jet\Application_Modules_Module_Manifest::setModuleTypesList
	 * @covers \Jet\Application_Modules_Module_Manifest::getModuleTypesList
	 */
	public function testSetGetModuleTypesList() {
		$data = [
			'MyType1' => 'MyModuleType1',
			'MyType2' => 'MyModuleType2',
			'MyType3' => 'MyModuleType3',
		];

		Application_Modules_Module_Manifest::setModuleTypesList($data);
		$this->assertEquals($data, Application_Modules_Module_Manifest::getModuleTypesList());
	}

}
