<?php
/**
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package DataModel
 */
namespace Jet;

/** @noinspection PhpIncludeInspection */
require_once '_mock/Jet/DataModel/Query/DataModelTestMock.php';


class DataModel_Query_OrderBy_ItemTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var DataModel_Query_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $properties = [];

	/**
	 * @var DataModel_Query_OrderBy_Item
	 */
	protected $object_int;

	/**
	 * @var DataModel_Query_OrderBy_Item
	 */
	protected $object_string;


	/**
	 * @var DataModel_Query_Select_Item
	 */
	protected $select_string_item;

	/**
	 * @var DataModel_Query_Select_Item
	 */
	protected $select_int_item;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {

		$this->data_model = new DataModel_Query_DataModelTestMock();
		$this->properties = $this->data_model->getDataModelDefinition()->getProperties();

		$this->select_string_item = new DataModel_Query_Select_Item($this->properties['string_property'], 'string_property_test');
		$this->select_int_item = new DataModel_Query_Select_Item($this->properties['int_property'], 'int_property_test');

		$this->object_string = new DataModel_Query_OrderBy_Item( $this->select_string_item, false );
		$this->object_int = new DataModel_Query_OrderBy_Item( $this->select_int_item, true );
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers DataModel_Query_OrderBy_Item::getItem
	 */
	public function testGetItem() {
		$this->assertSame($this->properties['string_property'], $this->object_string->getItem()->getItem() );
		$this->assertSame($this->properties['int_property'], $this->object_int->getItem()->getItem() );
	}

	/**
	 * @covers DataModel_Query_OrderBy_Item::getDesc
	 * @covers DataModel_Query_OrderBy_Item::setDesc
	 */
	public function testGetSetDesc() {
		$this->assertFalse($this->object_string->getDesc());
		$this->assertTrue($this->object_int->getDesc());

		$this->object_string->setDesc(true);
		$this->object_int->setDesc(false);

		$this->assertTrue($this->object_string->getDesc());
		$this->assertFalse($this->object_int->getDesc());
	}


}
