<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package Config
 */
namespace Jet;

require_once "_mock/Jet/Config/ConfigTestMock.php";
require_once "_mock/Jet/Config/ConfigTestDescendantMock.php";

if(!defined("CONFIG_TEST_BASEDIR")) {
	define("CONFIG_TEST_BASEDIR", JET_TESTS_DATA."Config/");
}


class ConfigTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var ConfigTestMock
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->object = new ConfigTestDescendantMock();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}



	/**
	 * @covers Jet\Config::setSoftMode
	 * @covers Jet\Config::getSoftMode
	 */
	public function testSetGetSoftMode() {
		$this->assertFalse( $this->object->getSoftMode() );
		$this->object->setSoftMode(true);
		$this->assertTrue( $this->object->getSoftMode() );
	}

	/**
	 * @covers Jet\Config::setApplicationConfigFilePath
	 * @covers Jet\Config::getApplicationConfigFilePath
	 */
	public function testSetGetMainConfigFilePath() {
		ConfigTestMock::setApplicationConfigFilePath("/test/path");
		$this->assertEquals("/test/path", ConfigTestMock::getApplicationConfigFilePath());
	}

	/**
	 * @covers Jet\Config::getAvailableHandlersList
	 */
	public function testGetAvailableHandlersList() {
		$valid_handlers_list = array("Handler1", "Handler2", "Handler3");
		$valid_handlers_list = array_combine($valid_handlers_list, $valid_handlers_list);

		$this->assertEquals(
			$valid_handlers_list,
			ConfigTestMock::getAvailableHandlersList("_data/Config/testGetHandlersList")
		);
	}



	/**
	 * @covers Jet\Config::getPropertiesDefinition
	 */
	public function testGetPropertiesDefinition() {

		$properties = $this->object->getPropertiesDefinition();

		$this->assertArrayHasKey("string_property", $properties);
		$this->assertArrayHasKey("int_property", $properties);
		$this->assertArrayHasKey("float_property", $properties);
		$this->assertArrayHasKey("bool_property", $properties);
		$this->assertArrayHasKey("next_string_property", $properties);

		$this->assertEquals("string_property", $properties["string_property"]->getName());
		$this->assertEquals("int_property", $properties["int_property"]->getName());
		$this->assertEquals("float_property", $properties["float_property"]->getName());
		$this->assertEquals("bool_property", $properties["bool_property"]->getName());
		$this->assertEquals("next_string_property", $properties["next_string_property"]->getName());

	}

	/**
	 * @covers Jet\Config::getConfigFilePath
	 */
	public function testGetConfigFilePath() {
		$path = "/some/path/config.php";
		$this->object->testSetConfigFilePath($path);
		$this->assertEquals( $path, $this->object->getConfigFilePath() );
	}


	/**
	 * @covers Jet\Config::setData
	 * @covers Jet\Config::getData
	 *
	 * @expectedException \Jet\Config_Exception
	 * @expectedExceptionCode \Jet\Config_Exception::CODE_CONFIG_FILE_IS_NOT_READABLE
	 */
	public function testSetDataFailedFileIsNotReadable() {
		$this->object->testInit("/imaginary/path/config.php");
	}

	/**
	 * @covers Jet\Config::setData
	 * @covers Jet\Config::getData
	 *
	 * @expectedException \Jet\Config_Exception
	 * @expectedExceptionCode \Jet\Config_Exception::CODE_CONFIG_FILE_IS_NOT_VALID
	 */
	public function testSetDataFailedInvalidConfigFile() {
		$this->object->testInit( CONFIG_TEST_BASEDIR."invalid-config.php" );
	}


	/**
	 * @covers Jet\Config::setData
	 * @covers Jet\Config::getData
	 *
	 * @expectedException \Jet\Config_Exception
	 * @expectedExceptionCode \Jet\Config_Exception::CODE_CONFIG_CHECK_ERROR
	 */
	public function testSetDataFailedMissingSection() {
		$this->object->testInit( CONFIG_TEST_BASEDIR."invalid-config-missing-section.php" );
	}



	/**
	 * @covers Jet\Config::setData
	 * @covers Jet\Config::getData
	 *
	 * @expectedException \Jet\Config_Exception
	 * @expectedExceptionCode \Jet\Config_Exception::CODE_CONFIG_CHECK_ERROR
	 */
	public function testSetDataFailedMissingOptions() {
		$this->object->testInit( CONFIG_TEST_BASEDIR."invalid-config-missing-options.php" );
	}


	/**
	 * @covers Jet\Config::setData
	 * @covers Jet\Config::getData
	 *
	 */
	public function testGetData() {
		$this->object->testInit( CONFIG_TEST_BASEDIR."valid-config.php" );

		$this->assertSame( "String config value", $this->object->getStringProperty() );
		$this->assertSame( 123, $this->object->getIntProperty() ); //default value
		$this->assertSame( 1.3, $this->object->getFloatProperty() );
		$this->assertSame( "Next string config value", $this->object->getNextStringProperty() );
	}


	/**
	 * @covers Jet\Config::getCommonForm
	 */
	public function testGetCommonForm() {
		$this->object->testInit( CONFIG_TEST_BASEDIR."valid-config.php" );

		$valid_form = new Form("ConfigTestDescendantMock", array(
			new Form_Field_Input("string_property", "String property:", "String config value", true),
			new Form_Field_Int("int_property", "Int property:", 123, false),
			new Form_Field_Float("float_property", "Float property:", 1.3, true),
			new Form_Field_Checkbox("bool_property", "Bool property:", false, false),
			new Form_Field_Input("next_string_property", "Next string property:", "Next string config value", true),
		));

		$this->assertEquals($valid_form,  $this->object->getCommonForm() );
	}

	/**
	 * @covers Jet\Config::catchForm
	 * @covers Jet\Config::save
	 */
	public function testCatchFormAndSave() {
		$this->object->testInit( CONFIG_TEST_BASEDIR."valid-config.php" );
		$form = $this->object->getCommonForm();

		$data = array (
			"string_property" => "String config value - updated",
			"float_property" => 1.4,
			"int_property" => 123456789,
			"bool_property" => true,
			"next_string_property" => "Next string config value - updated",
		);

		$this->object->catchForm($form, $data, true );

		$this->assertEquals($data, $this->object->getData()->getRawData());

		$save_path = JET_TESTS_TMP."config-save-test.php";

		$this->object->save( $save_path );

		$this->assertEquals(array (
			'section' =>
			array (
				'subsection' =>
				array (
					'string_property' => 'String config value - updated',
					'int_property' => 123456789,
					'float_property' => 1.4,
					'bool_property' => true,
					'next_string_property' => 'Next string config value - updated',
				),
			),
		), require $save_path );

		unlink($save_path);
	}

	/**
	 * @covers Jet\Config::toArray
	 */
	public function testToArray() {
		$this->object->testInit( CONFIG_TEST_BASEDIR."valid-config.php" );

		$this->assertEquals( array (
			'string_property' => 'String config value',
			'int_property' => 123,
			'float_property' => 1.3,
			'bool_property' => false,
			'next_string_property' => 'Next string config value',
		), $this->object->toArray());
	}
}
