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

class Application_Modules_Module_InfoTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var Application_Modules_Module_Info
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
		Application_Modules_Module_Info::setModuleTypesList(array(
			Application_Modules_Module_Info::MODULE_TYPE_GENERAL => 'General module',
			Application_Modules_Module_Info::MODULE_TYPE_SITE_UI_MANAGER => 'Site UI manager module',
			Application_Modules_Module_Info::MODULE_TYPE_ADMIN_UI_MANAGER => 'Administration UI manager module',
			Application_Modules_Module_Info::MODULE_TYPE_AUTH_MANAGER => 'Authentication and authorization manager module',
			Application_Modules_Module_Info::MODULE_TYPE_OUTPUT_FILTER => 'Output filter module',
			Application_Modules_Module_Info::MODULE_TYPE_SYSTEM => 'System module',
		));
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::readManifestData
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_MODULE_DOES_NOT_EXIST
	 */
	public function testReadManifestDataFailedModuleDoesNotExist() {
		new Application_Modules_Module_Info('ImaginaryModule');
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::readManifestData
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_MANIFEST_IS_NOT_READABLE
	 */
	public function testReadManifestDataFailedManifestIsNotReadable() {
		new Application_Modules_Module_Info('ModuleWOManifest');
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::checkManifestData
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_MANIFEST_NONSENSE
	 */
	public function testcheckManifestDataInvalidNotArray() {
		new Application_Modules_Module_Info('InvalidManifestNotArray');
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::checkManifestData
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_MANIFEST_NONSENSE
	 */
	public function testcheckManifestDataInvalidMissingAPIVersion() {
		new Application_Modules_Module_Info('InvalidMissingAPIVersion');
	}


	/**
	 * @covers Jet\Application_Modules_Module_Info::checkManifestData
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_MANIFEST_NONSENSE
	 */
	public function testcheckManifestDataInvalidMissingLabel() {
		new Application_Modules_Module_Info('InvalidMissingLabel');
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::checkManifestData
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_MANIFEST_NONSENSE
	 */
	public function testcheckManifestDataInvalidMissingTypes() {
		new Application_Modules_Module_Info('InvalidMissingTypes');
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::checkManifestData
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_MANIFEST_NONSENSE
	 */
	public function testcheckManifestDataInvalidUnknownTypes() {
		new Application_Modules_Module_Info('InvalidUnknownTypes');
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::readManifestData
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_MANIFEST_NONSENSE
	 */
	public function testReadManifestDataInvalidRequireIsNotArray() {
		new Application_Modules_Module_Info('InvalidRequireIsNotArray');
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::readManifestData
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_MANIFEST_NONSENSE
	 */
	public function testReadManifestDataInvalidFactoryOverloadMapIsNotArray() {
		new Application_Modules_Module_Info('InvalidFactoryOverloadMapIsNotArray');
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::readManifestData
	 *
	 * @expectedException \Jet\Application_Modules_Exception
	 * @expectedExceptionCode \Jet\Application_Modules_Exception::CODE_MANIFEST_NONSENSE
	 */
	public function testReadManifestDataInvalidSignalsCallbacksIsNotArray() {
		new Application_Modules_Module_Info('InvalidSignalsCallbacksIsNotArray');
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::readManifestData
	 */
	public function testReadManifestData() {
		new Application_Modules_Module_Info('ValidModule');
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::getModuleDir
	 */
	public function testGetModuleDir() {
		$module_info = new Application_Modules_Module_Info('ValidModule');

		$this->assertEquals( JET_MODULES_PATH. 'ValidModule/', $module_info->getModuleDir() );
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::getViewDir
	 */
	public function testGetViewDir() {
		$module_info = new Application_Modules_Module_Info('ValidModule');

		$this->assertEquals( JET_MODULES_PATH. 'ValidModule/'.Application_Modules::MODULE_VIEWS_DIR, $module_info->getViewsDir() );
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::getName
	 */
	public function testGetName() {
		$module_info = new Application_Modules_Module_Info('ValidModule');
		$this->assertEquals( 'ValidModule', $module_info->getName() );
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::getLabel
	 */
	public function testGetLabel() {
		$module_info = new Application_Modules_Module_Info('ValidModule');
		$this->assertEquals( 'Test Module', $module_info->getLabel() );
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::getDescription
	 */
	public function testGetDescription() {
		$module_info = new Application_Modules_Module_Info('ValidModule');
		$this->assertEquals( 'Unit test module', $module_info->getDescription() );
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::getAPIVersion
	 */
	public function testGetAPIVersion() {
		$module_info = new Application_Modules_Module_Info('ValidModule');
		$this->assertEquals( 201208, $module_info->getAPIVersion() );
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::getTypes
	 */
	public function testGetTypes() {
		$module_info = new Application_Modules_Module_Info('ValidModule');
		$this->assertEquals( array( Application_Modules_Module_Info::MODULE_TYPE_GENERAL ), $module_info->getTypes() );
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::getHasType
	 */
	public function testGetHasType() {
		$module_info = new Application_Modules_Module_Info('ValidModule');

		$this->assertTrue( $module_info->getHasType(Application_Modules_Module_Info::MODULE_TYPE_GENERAL) );
		$this->assertFalse( $module_info->getHasType(Application_Modules_Module_Info::MODULE_TYPE_SYSTEM) );
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::getIsSiteUIManagerModule
	 */
	public function testGetIssiteUIManagerModule() {
		$module_info = new Application_Modules_Module_Info('ValidModule');

		$this->assertFalse( $module_info->getIsSiteUIManagerModule() );
	}


	/**
	 * @covers Jet\Application_Modules_Module_Info::getIsAdminUIManagerModule
	 */
	public function testGetIsAdminUIManagerModule() {
		$module_info = new Application_Modules_Module_Info('ValidModule');

		$this->assertFalse( $module_info->getIsAdminUIManagerModule() );
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::getIsAuthManagerModule
	 */
	public function testGetIsAuthManagerModule() {
		$module_info = new Application_Modules_Module_Info('ValidModule');

		$this->assertFalse( $module_info->getIsAuthManagerModule() );
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::getIsOutputFilter
	 */
	public function testGetIsOutputFilter() {
		$module_info = new Application_Modules_Module_Info('ValidModule');

		$this->assertFalse( $module_info->getIsOutputFilter() );
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::getRequire
	 */
	public function testGetRequire() {
		$module_info = new Application_Modules_Module_Info('ValidModule');
		$this->assertEquals( array(
			'RequireModule1',
			'RequireModule2'
		), $module_info->getRequire() );
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::getFactoryOverloadMap
	 */
	public function testGetFactoryOverloadMap() {
		$module_info = new Application_Modules_Module_Info('ValidModule');
		$this->assertEquals( array(
			'OldClass1' => 'MyClass1',
			'OldClass2' => 'MyClass2',
			'OldClass3' => 'MyClass3',
		), $module_info->getFactoryOverloadMap() );
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::getSignalCallbacks
	 */
	public function testGetSignalCallbacks() {
		$module_info = new Application_Modules_Module_Info('ValidModule');
		$this->assertEquals( array(
			'/test/signal1' => 'CallbackMoeduleMethodName1',
			'/test/signal2' => 'CallbackMoeduleMethodName2',
		), $module_info->getSignalCallbacks() );
	}


	/**
	 * @covers Jet\Application_Modules_Module_Info::setIsInstalled
	 * @covers Jet\Application_Modules_Module_Info::getIsInstalled
	 */
	public function testSetGetIsInstalled() {
		$module_info = new Application_Modules_Module_Info('ValidModule');
		$this->assertFalse( $module_info->getIsInstalled() );
		$module_info->setIsInstalled(true);
		$this->assertTrue( $module_info->getIsInstalled() );
	}

	/**
	 * @covers Jet\Application_Modules_Module_Info::setIsActivated
	 * @covers Jet\Application_Modules_Module_Info::getIsActivated
	 */
	public function testSetGetIsActivated() {
		$module_info = new Application_Modules_Module_Info('ValidModule');
		$this->assertFalse( $module_info->getIsActivated() );
		$module_info->setIsActivated(true);
		$this->assertTrue( $module_info->getIsActivated() );
	}


	/**
	 * @covers Jet\Application_Modules_Module_Info::setModuleTypesList
	 * @covers Jet\Application_Modules_Module_Info::getModuleTypesList
	 */
	public function testSetGetModuleTypesList() {
		$data = array(
			'MyType1' => 'MyModuleType1',
			'MyType2' => 'MyModuleType2',
			'MyType3' => 'MyModuleType3',
		);

		Application_Modules_Module_Info::setModuleTypesList($data);
		$this->assertEquals($data, Application_Modules_Module_Info::getModuleTypesList());
	}

}
