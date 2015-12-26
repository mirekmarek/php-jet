<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @package Data
 * @subpackage Data_Array
 */
namespace Jet;


/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class  Data_ArrayTest_testObject {
	public $v_int = 1;
	public $v_float = 3.14;
	public $v_string = '<script>alert("Shady!");</script>';
}

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class Data_ArrayTest_testObject2 {
	public $v_int = 1;
	protected $v_float = 3.14;
	private /** @noinspection PhpUnusedPrivateFieldInspection */
		$v_string = '<script>alert("Shady!");</script>';
}

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class Data_ArrayTest_testObject3 implements \JsonSerializable {
	public $v_int = 1;
	protected $v_float = 3.14;
	private /** @noinspection PhpUnusedPrivateFieldInspection */
		$v_string = '<script>alert("Shady!");</script>';

	/**
	 * @return array
	 */
	public function jsonSerialize() {
		return get_object_vars($this);
	}

}

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class Data_ArrayTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var Data_Array
	 */
	protected $object;

	protected $data = [
		'int' => 1,
		'float' => 3.14,
		'string' => '<script>alert("Shady!");</script>',
		'bool' => true,
		'sub1' => [
			'int' => 2,
			'float' => 6.28,
			'string' => '<script>alert("Shady!!");</script>',
			'bool' => false,

			'sub2' => [
				'int' => 4,
				'float' => 12.56,
				'string' => '<script>alert("Shady!!!");</script>',
				'bool' => true,

			],
			'sub_ai' => [
				1,
				'string',
				123.456
			]
		]
	];

	protected $comments = [
		'/int' => '/int comment',
		'/float' => '/float comment',
		'/string' => '/string comment',
		'/sub1' => '/sub1 comment',
		'/sub1/int' => '/sub1/int comment',
		'/sub1/float' => '/sub1/float comment',
		'/sub1/string' => '/sub1/string comment',
		'/sub1/sub2' => '/sub1/sub2 comment',
		'/sub1/sub2/int' => '/sub1/sub2/int comment',
		'/sub1/sub2/float' => '/sub1/sub2/float comment',
		'/sub1/sub2/string' => '/sub1/sub2/string comment',
		'/sub1/sub2/test_object' => '/sub1/sub2/test_object comment',
	];

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->data['sub1']['sub2']['test_object'] = new Data_ArrayTest_testObject();
		$this->data['sub1']['sub2']['test_object2'] = new Data_ArrayTest_testObject2();
		$this->data['sub1']['sub2']['test_object3'] = new Data_ArrayTest_testObject3();

		$this->object = new Data_Array( $this->data );
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers Jet\Data_Array::getRawData
	 */
	public function testGetRawData() {
		$this->assertEquals( $this->data, $this->object->getRawData() );
	}

	/**
	 * @covers Jet\Data_Array::appendData
	 */
	public function testAppendData() {
		$new_data = ['merge_test'=>'test'];

		$this->object->appendData( $new_data );
		$this->assertEquals(
			array_merge($this->data, $new_data ),
			$this->object->getRawData()
		);
	}

	/**
	* @covers Jet\Data_Array::setData
	*/
	public function testSetData() {
		$new_data = ['merge_test'=>'test'];

		$this->object->setData( $new_data );
		$this->assertEquals(
			$new_data,
			$this->object->getRawData()
		);
	}

	/**
	* @covers Jet\Data_Array::clearData
	*/
	public function testClearData() {
		$this->object->clearData();
		$this->assertEquals(
			[],
			$this->object->getRawData()
		);
	}

	/**
	* @covers Jet\Data_Array::exists
	*/
	public function testExists() {
		$this->assertTrue( $this->object->exists('int') );
		$this->assertTrue( $this->object->exists('float') );
		$this->assertTrue( $this->object->exists('string') );
		$this->assertTrue( $this->object->exists('sub1') );
		$this->assertTrue( $this->object->exists('/int') );
		$this->assertTrue( $this->object->exists('/float') );
		$this->assertTrue( $this->object->exists('/string') );
		$this->assertTrue( $this->object->exists('/sub1') );
		$this->assertTrue( $this->object->exists('/sub1/int') );
		$this->assertTrue( $this->object->exists('/sub1/float') );
		$this->assertTrue( $this->object->exists('/sub1/string') );
		$this->assertTrue( $this->object->exists('/sub1/sub2') );
		$this->assertTrue( $this->object->exists('/sub1/sub2/int') );
		$this->assertTrue( $this->object->exists('/sub1/sub2/float') );
		$this->assertTrue( $this->object->exists('/sub1/sub2/string') );

		$this->assertFalse( $this->object->exists('int-na') );
		$this->assertFalse( $this->object->exists('float-na') );
		$this->assertFalse( $this->object->exists('string-na') );
		$this->assertFalse( $this->object->exists('sub1-na') );
		$this->assertFalse( $this->object->exists('/int-na') );
		$this->assertFalse( $this->object->exists('/float-na') );
		$this->assertFalse( $this->object->exists('/string-na') );
		$this->assertFalse( $this->object->exists('/sub1-na') );
		$this->assertFalse( $this->object->exists('/sub1/int-na') );
		$this->assertFalse( $this->object->exists('/sub1/float-na') );
		$this->assertFalse( $this->object->exists('/sub1/string-na') );
		$this->assertFalse( $this->object->exists('/sub1/sub2-na') );
		$this->assertFalse( $this->object->exists('/sub1/sub2/int-na') );
		$this->assertFalse( $this->object->exists('/sub1/sub2/float-na') );
		$this->assertFalse( $this->object->exists('/sub1/sub2/string-na') );

	}

	/**
	 * @covers Jet\Data_Array::set
	 * @covers Jet\Data_Array::getRaw
	 */
	public function testSet() {
		$this->object->set('int', 54321);
		$this->object->set('/sub1/sub2/int', 12345);

		$this->assertEquals(54321, $this->object->getRaw('int') );
		$this->assertEquals(12345, $this->object->getRaw('/sub1/sub2/int') );
	}

	/**
	 * @covers Jet\Data_Array::remove
	 */
	public function testRemove() {
		$this->assertTrue($this->object->exists('/sub1/sub2/int'));

		$this->object->remove('/sub1/sub2/int');

		$this->assertFalse($this->object->exists('/sub1/sub2/int'));
	}

	/**
	 * @covers Jet\Data_Array::getRaw
	 */
	public function testGetRaw() {
		$this->assertEquals( $this->data['string'], $this->object->getRaw('string') );
		$this->assertEquals( $this->data['string'], $this->object->getRaw('/string') );
		$this->assertEquals( $this->data['sub1']['sub2']['string'], $this->object->getRaw('/sub1/sub2/string') );
	}

	/**
	* @covers Jet\Data_Array::getInt
	*/
	public function testGetInt() {
		$this->assertEquals( $this->data['int'], $this->object->getRaw('int') );
		$this->assertEquals( $this->data['int'], $this->object->getRaw('/int') );
		$this->assertEquals( $this->data['sub1']['sub2']['int'], $this->object->getRaw('/sub1/sub2/int') );
	}

	/**
	 * @covers Jet\Data_Array::getFloat
	 */
	public function testGetFloat() {
		$this->assertEquals( $this->data['float'], $this->object->getRaw('float') );
		$this->assertEquals( $this->data['float'], $this->object->getRaw('/float') );
		$this->assertEquals( $this->data['sub1']['sub2']['float'], $this->object->getRaw('/sub1/sub2/float') );
	}

	/**
	 * @covers Jet\Data_Array::getBool
	 */
	public function testGetBool() {
		$this->assertEquals( $this->data['bool'], $this->object->getRaw('bool') );
		$this->assertEquals( $this->data['bool'], $this->object->getRaw('/bool') );
		$this->assertEquals( $this->data['sub1']['sub2']['bool'], $this->object->getRaw('/sub1/sub2/bool') );
	}

	/**
	 * @covers Jet\Data_Array::getString
	 */
	public function testGetString() {
		$this->assertEquals( Data_Text::htmlSpecialChars( $this->data['string'] ), $this->object->getString('string') );
		$this->assertEquals( Data_Text::htmlSpecialChars( $this->data['string'] ), $this->object->getString('/string') );
		$this->assertEquals( Data_Text::htmlSpecialChars( $this->data['sub1']['sub2']['string'] ), $this->object->getString('/sub1/sub2/string') );
	}

	/**
	 * @covers Jet\Data_Array::export
	 */
	public function testExport() {
		$valid_result =
		'array('.JET_EOL
			.JET_TAB.'\'int\' => 1'.JET_TAB.'/* /int comment */,'.JET_EOL
			.JET_TAB.'\'float\' => 3.14'.JET_TAB.'/* /float comment */,'.JET_EOL
			.JET_TAB.'\'string\' => \'<script>alert("Shady!");</script>\''.JET_TAB.'/* /string comment */,'.JET_EOL
			.JET_TAB.'\'bool\' => true,'.JET_EOL
			.JET_TAB.'\'sub1\' => array('.JET_TAB.'/* /sub1 comment */'.JET_EOL
				.JET_TAB.JET_TAB.'\'int\' => 2'.JET_TAB.'/* /sub1/int comment */,'.JET_EOL
				.JET_TAB.JET_TAB.'\'float\' => 6.28'.JET_TAB.'/* /sub1/float comment */,'.JET_EOL
				.JET_TAB.JET_TAB.'\'string\' => \'<script>alert("Shady!!");</script>\''.JET_TAB.'/* /sub1/string comment */,'.JET_EOL
				.JET_TAB.JET_TAB.'\'bool\' => false,'.JET_EOL
				.JET_TAB.JET_TAB.'\'sub2\' => array('.JET_TAB.'/* /sub1/sub2 comment */'.JET_EOL
					.JET_TAB.JET_TAB.JET_TAB.'\'int\' => 4'.JET_TAB.'/* /sub1/sub2/int comment */,'.JET_EOL
					.JET_TAB.JET_TAB.JET_TAB.'\'float\' => 12.56'.JET_TAB.'/* /sub1/sub2/float comment */,'.JET_EOL
					.JET_TAB.JET_TAB.JET_TAB.'\'string\' => \'<script>alert("Shady!!!");</script>\''.JET_TAB.'/* /sub1/sub2/string comment */,'.JET_EOL
					.JET_TAB.JET_TAB.JET_TAB.'\'bool\' => true,'.JET_EOL
					.JET_TAB.JET_TAB.JET_TAB.'\'test_object\' => Jet\Data_ArrayTest_testObject::__set_state( array('.JET_TAB.'/* /sub1/sub2/test_object comment */'.JET_EOL
						.JET_TAB.JET_TAB.JET_TAB.JET_TAB.'\'v_int\' => 1,'.JET_EOL
						.JET_TAB.JET_TAB.JET_TAB.JET_TAB.'\'v_float\' => 3.14,'.JET_EOL
						.JET_TAB.JET_TAB.JET_TAB.JET_TAB.'\'v_string\' => \'<script>alert("Shady!");</script>\','.JET_EOL
					.JET_TAB.JET_TAB.JET_TAB.') ),'.JET_EOL
					.JET_TAB.JET_TAB.JET_TAB.'\'test_object2\' => Jet\Data_ArrayTest_testObject2::__set_state( array('.JET_EOL
						.JET_TAB.JET_TAB.JET_TAB.JET_TAB.'\'v_int\' => 1,'.JET_EOL
					.JET_TAB.JET_TAB.JET_TAB.') ),'.JET_EOL
					.JET_TAB.JET_TAB.JET_TAB.'\'test_object3\' => Jet\Data_ArrayTest_testObject3::__set_state( array('.JET_EOL
						.JET_TAB.JET_TAB.JET_TAB.JET_TAB.'\'v_int\' => 1,'.JET_EOL
						.JET_TAB.JET_TAB.JET_TAB.JET_TAB.'\'v_float\' => 3.14,'.JET_EOL
						.JET_TAB.JET_TAB.JET_TAB.JET_TAB.'\'v_string\' => \'<script>alert("Shady!");</script>\','.JET_EOL
					.JET_TAB.JET_TAB.JET_TAB.') ),'.JET_EOL
				.JET_TAB.JET_TAB.'),'.JET_EOL
				.JET_TAB.JET_TAB.'\'sub_ai\' => array('.JET_EOL
				.JET_TAB.JET_TAB.JET_TAB.'1,'.JET_EOL
				.JET_TAB.JET_TAB.JET_TAB.'\'string\','.JET_EOL
				.JET_TAB.JET_TAB.JET_TAB.'123.456,'.JET_EOL
				.JET_TAB.JET_TAB.'),'.JET_EOL
			.JET_TAB.'),'.JET_EOL
		.');'.JET_EOL
		;
		$this->assertEquals( $valid_result, $this->object->export( $this->comments ) );

		//var_export( $this->object->export( $this->comments ) );

	}
}
