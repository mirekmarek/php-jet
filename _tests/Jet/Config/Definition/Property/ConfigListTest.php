<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Config
 * @subpackage Config_Definition
 */
namespace Jet;

require_once "_mock/Jet/Config/ConfigListTestMainMock.php";
//require_once "_mock/Jet/Config/ConfigTestDescendantMock.php";

if(!defined("CONFIG_TEST_BASEDIR")) {
	define("CONFIG_TEST_BASEDIR", JET_TESTS_DATA."Config/");
}


class Config_Definition_Property_ConfigListTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var ConfigListTestMainMock
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->object = new ConfigListTestMainMock();
		$this->object->testInit( CONFIG_TEST_BASEDIR."valid-config-list.php", true );
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}


	/**
	 * @covers Jet\Config_Definition_Property_AdapterConfig::checkValueType
	 */
	public function testCheckValueType() {
		//do nothing
	}

	/**
	 * @covers Jet\Config_Definition_Property_AdapterConfig::setUp
	 * @covers Jet\Config_Definition_Property_AdapterConfig::getConfigurationListItem
	 */
	public function testgetConfigurationListItem() {
		$connection = $this->object->getConfigurationListItem("connection_imaginary");
		$this->assertFalse( $connection );

		$connection_1 = $this->object->getConfigurationListItem("connection_1");
		$this->assertEquals("Connection 1 - config value", $connection_1->getAdapterConfigValue());
		$connection_2 = $this->object->getConfigurationListItem("connection_2");
		$this->assertEquals("Connection 2 - config value", $connection_2->getAdapterConfigValue());
		$connection_3 = $this->object->getConfigurationListItem("connection_3");
		$this->assertEquals("Connection 3 - config value", $connection_3->getAdapterConfigValue());
	}

	/**
	 * @covers Jet\Config_Definition_Property_AdapterConfig::setUp
	 * @covers Jet\Config_Definition_Property_AdapterConfig::getAllAdaptersConfiguration
	 */
	public function testGetAllAdaptersConfiguration() {
		$connections = $this->object->getConnections();
		$this->assertArrayHasKey("connection_1", $connections);
		$this->assertArrayHasKey("connection_2", $connections);
		$this->assertArrayHasKey("connection_3", $connections);

		$this->assertEquals("Connection 1 - config value", $connections["connection_1"]->getAdapterConfigValue());
		$this->assertEquals("Connection 2 - config value", $connections["connection_2"]->getAdapterConfigValue());
		$this->assertEquals("Connection 3 - config value", $connections["connection_3"]->getAdapterConfigValue());
	}

	/**
	 * @covers Jet\Config_Definition_Property_AdapterConfig::setUp
	 * @covers Jet\Config_Definition_Property_AdapterConfig::toArray
	 */
	public function testToArray() {
		$this->assertEquals(  array (
			'connection_1' =>
			array (
				'adapter' => 'AdapterA',
				'adapter_config_value' => 'Connection 1 - config value',
			),
			'connection_2' =>
			array (
				'adapter' => 'AdapterA',
				'adapter_config_value' => 'Connection 2 - config value',
			),
			'connection_3' =>
			array (
				'adapter' => 'AdapterB',
				'adapter_config_value' => 'Connection 3 - config value',
			),
		),  $this->object->toArray() );
	}


	/**
	 * @covers Jet\Config_Definition_Property_AdapterConfig::setUp
	 * @covers Jet\Config_Definition_Property_AdapterConfig::addAdapterConfiguration
	 */
	public function testAddAdapterConfiguration() {
		$new_connection = new ConfigListTestMainMock_AdapterB_Config(
			array(
				'adapter' => 'AdapterB',
				'adapter_config_value' => 'Connection N - config value',
			),
			$this->object
		);
		$this->object->addConnection("connection_n", $new_connection);

		$connections = $this->object->getConnections();
		$this->assertArrayHasKey("connection_n", $connections);

		$this->assertEquals("Connection N - config value", $connections["connection_n"]->getAdapterConfigValue());
	}

	/**
	 * @covers Jet\Config_Definition_Property_AdapterConfig::deleteAdapterConfiguration
	 */
	public function testDeleteAdapterConfiguration() {
		$this->object->deleteConnection("connection_2");
		$this->assertEquals(  array (
			'connection_1' =>
			array (
				'adapter' => 'AdapterA',
				'adapter_config_value' => 'Connection 1 - config value',
			),
			'connection_3' =>
			array (
				'adapter' => 'AdapterB',
				'adapter_config_value' => 'Connection 3 - config value',
			),
		),  $this->object->toArray() );
	}
}
