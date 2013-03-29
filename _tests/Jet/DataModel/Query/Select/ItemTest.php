<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package DataModel
 */
namespace Jet;

require_once "_mock/Jet/DataModel/Query/DataModelTestMock.php";

class DataModel_Query_Select_ItemTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DataModel_Query_Select_Item
	 */
	protected $object;

	/**
	 * @var DataModel_Query_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $properties = array();

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->data_model = new DataModel_Query_DataModelTestMock();
		$this->properties = $this->data_model->getDataModelDefinition()->getProperties();

		$this->object = new DataModel_Query_Select_Item( $this->properties["int_property"], "my_int_property" );
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers Jet\DataModel_Query_Select_Item::getItem
	 */
	public function testGetItem() {
		$this->assertEquals($this->properties["int_property"], $this->object->getItem());
	}

	/**
	 * @covers Jet\DataModel_Query_Select_Item::getSelectAs
	 */
	public function testGetSelectAs() {
		$this->assertEquals("my_int_property", $this->object->getSelectAs());
	}
}
