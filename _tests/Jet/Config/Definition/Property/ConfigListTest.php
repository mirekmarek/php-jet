<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Config
 * @subpackage Config_Definition
 */
namespace Jet;

/** @noinspection PhpIncludeInspection */
require_once '_mock/Jet/Config/ConfigListTestMainMock.php';
//require_once '_mock/Jet/Config/ConfigTestDescendantMock.php';

if(!defined('CONFIG_TEST_BASEDIR')) {
	define('CONFIG_TEST_BASEDIR', JET_TESTS_DATA.'Config/');
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
		$this->object->testInit( CONFIG_TEST_BASEDIR.'valid-config-list.php', true );
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}


}
