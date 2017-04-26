<?php
/**
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package Data
 * @subpackage Data_Paginator
 */
namespace Jet;


class Data_PaginatorTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var Data_Paginator
	 */
	protected $object;



	protected function setUp() {
	}


	/**
	 * @covers \Jet\Data_Paginator::__construct
	 *
	 * @expectedException \Jet\Data_Paginator_Exception
	 * @expectedExceptionCode \Jet\Data_Paginator_Exception::CODE_INCORRECT_URL_TEMPLATE_STRING
	 *
	 */
	public function testConstructor() {
		$this->object = new Data_Paginator( 1, 1000, 'invalid' );
	}

	/**
	 * @covers \Jet\Data_Paginator::__construct
	 * @covers \Jet\Data_Paginator::setData
	 * @covers \Jet\Data_Paginator::setDataSource
	 * @covers \Jet\Data_Paginator::getDataItemsCount
	 * @covers \Jet\Data_Paginator::getPagesCount
	 * @covers \Jet\Data_Paginator::getPrevPageNo
	 * @covers \Jet\Data_Paginator::getCurrentPageNo
	 * @covers \Jet\Data_Paginator::getNextPageNo
	 * @covers \Jet\Data_Paginator::getDataIndexStart
	 * @covers \Jet\Data_Paginator::getDataIndexEnd
	 * @covers \Jet\Data_Paginator::getPrevPageURL
	 * @covers \Jet\Data_Paginator::getNextPageURL
	 * @covers \Jet\Data_Paginator::getPagesURL
	 * @covers \Jet\Data_Paginator::getShowFrom
	 * @covers \Jet\Data_Paginator::getShowTo
	 */
	public function testSetData() {
		$data = [];
		for( $i=0; $i<123; $i++ ) {
			$data[$i] = 'Item '.$i;
		}

		$this->object = new Data_Paginator( 0, 10, 'test:'.Data_Paginator::URL_PAGE_NO_KEY );

		$this->assertEquals(1, $this->object->getCurrentPageNo());
		$this->assertEquals(0, $this->object->getDataItemsCount());
		$this->assertEquals(0, $this->object->getShowFrom());
		$this->assertEquals(0, $this->object->getShowTo());

		$this->object->setData( $data );



		$this->assertEquals(123, $this->object->getDataItemsCount());
		$this->assertEquals(13, $this->object->getPagesCount());



		$this->assertNull($this->object->getPrevPageNo());
		$this->assertEquals(1, $this->object->getCurrentPageNo());
		$this->assertEquals(2, $this->object->getNextPageNo());
		$this->assertEquals(0, $this->object->getDataIndexStart());
		$this->assertEquals(9, $this->object->getDataIndexEnd());
		$this->assertEquals(1, $this->object->getShowFrom());
		$this->assertEquals(10, $this->object->getShowTo());
		$this->assertEquals( [
			0 => 'Item 0',
			1 => 'Item 1',
			2 => 'Item 2',
			3 => 'Item 3',
			4 => 'Item 4',
			5 => 'Item 5',
			6 => 'Item 6',
			7 => 'Item 7',
			8 => 'Item 8',
			9 => 'Item 9',
		], $this->object->getData());
		$this->assertFalse( $this->object->getCurrentPageNoIsInRange() );
		$this->assertNull( $this->object->getPrevPageURL() );
		$this->assertEquals('test:2', $this->object->getNextPageURL());




		$this->object = new Data_Paginator( 5, 10, 'test:'.Data_Paginator::URL_PAGE_NO_KEY );
		$this->object->setData( $data );
		$this->assertEquals(4, $this->object->getPrevPageNo());
		$this->assertEquals(5, $this->object->getCurrentPageNo());
		$this->assertEquals(6, $this->object->getNextPageNo());
		$this->assertEquals(40, $this->object->getDataIndexStart());
		$this->assertEquals(49, $this->object->getDataIndexEnd());
		$this->assertEquals(41, $this->object->getShowFrom());
		$this->assertEquals(50, $this->object->getShowTo());
		$this->assertEquals( [
			40 => 'Item 40',
			41 => 'Item 41',
			42 => 'Item 42',
			43 => 'Item 43',
			44 => 'Item 44',
			45 => 'Item 45',
			46 => 'Item 46',
			47 => 'Item 47',
			48 => 'Item 48',
			49 => 'Item 49',
		], $this->object->getData());
		$this->assertTrue( $this->object->getCurrentPageNoIsInRange() );
		$this->assertEquals('test:4', $this->object->getPrevPageURL());
		$this->assertEquals('test:6', $this->object->getNextPageURL());



		$this->object = new Data_Paginator( 50, 10, 'test:'.Data_Paginator::URL_PAGE_NO_KEY );
		$this->object->setData( $data );
		$this->assertEquals(13, $this->object->getCurrentPageNo());
		$this->assertEquals(12, $this->object->getPrevPageNo());
		$this->assertNull( $this->object->getNextPageNo());
		$this->assertEquals(120, $this->object->getDataIndexStart());
		$this->assertEquals(122, $this->object->getDataIndexEnd());
		$this->assertEquals(121, $this->object->getShowFrom());
		$this->assertEquals(123, $this->object->getShowTo());
		$this->assertEquals( [
			120 => 'Item 120',
			121 => 'Item 121',
			122 => 'Item 122'
		], $this->object->getData());
		$this->assertFalse( $this->object->getCurrentPageNoIsInRange() );
		$this->assertEquals('test:12', $this->object->getPrevPageURL());
		$this->assertNull($this->object->getNextPageURL());

		$this->assertEquals([
					1 => 'test:1',
					2 => 'test:2',
					3 => 'test:3',
					4 => 'test:4',
					5 => 'test:5',
					6 => 'test:6',
					7 => 'test:7',
					8 => 'test:8',
					9 => 'test:9',
					10 => 'test:10',
					11 => 'test:11',
					12 => 'test:12',
					13 => 'test:13',
		], $this->object->getPagesURL() );




		$data = [];
		for( $i=0; $i<3; $i++ ) {
			$data[$i] = 'Item '.$i;
		}


		$this->object = new Data_Paginator( 2, 2, 'test:'.Data_Paginator::URL_PAGE_NO_KEY );
		$this->object->setData( $data );
		$this->assertEquals(2, $this->object->getCurrentPageNo());
		$this->assertEquals(1, $this->object->getPrevPageNo());
		$this->assertNull( $this->object->getNextPageNo());
		$this->assertEquals(2, $this->object->getDataIndexStart());
		$this->assertEquals(2, $this->object->getDataIndexEnd());
		$this->assertEquals(3, $this->object->getShowFrom());
		$this->assertEquals(3, $this->object->getShowTo());

		$this->assertEquals( [
			2 => 'Item 2'
		], $this->object->getData());

	}



}
