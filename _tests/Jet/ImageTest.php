<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package Image
 */
namespace Jet;

define('IMAGE_TEST_BASEDIR', JET_TESTS_DATA . 'Image/');

class ImageTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var Image
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@unlink(JET_TESTS_TMP.'thumbnail.jpg');
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@unlink(JET_TESTS_TMP.'thumbnail.gif');
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@unlink(JET_TESTS_TMP.'thumbnail.png');
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@unlink(JET_TESTS_TMP.'thumbnail2.jpg');
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@unlink(JET_TESTS_TMP.'thumbnail2.gif');
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@unlink(JET_TESTS_TMP.'thumbnail2.png');
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@unlink(JET_TESTS_TMP.'thumbnail.jpg');
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@unlink(JET_TESTS_TMP.'thumbnail.gif');
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@unlink(JET_TESTS_TMP.'thumbnail.png');
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@unlink(JET_TESTS_TMP.'thumbnail2.jpg');
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@unlink(JET_TESTS_TMP.'thumbnail2.gif');
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@unlink(JET_TESTS_TMP.'thumbnail2.png');
	}

	/**
	 * @covers Jet\Image::__construct
	 *
	 * @expectedException \Jet\Image_Exception
	 * @expectedExceptionCode \Jet\Image_Exception::CODE_IMAGE_FILE_DOES_NOT_EXIST
	 */
	public function testFailedFileDoesNotExist() {
		new Image( IMAGE_TEST_BASEDIR.'imaginary_file' );
	}

	/**
	 * @covers Jet\Image::__construct
	 *
	 * @expectedException \Jet\Image_Exception
	 * @expectedExceptionCode \Jet\Image_Exception::CODE_UNSUPPORTED_IMAGE_TYPE
	 */
	public function testFailedUnsupportedImageType() {
		new Image( IMAGE_TEST_BASEDIR.'TestImage.sgi' );
	}

	/**
	 * @covers Jet\Image::__construct
	 * @covers Jet\Image::getPath
	 * @covers Jet\Image::getDirectory
	 * @covers Jet\Image::getFileName
	 * @covers Jet\Image::getWidth
	 * @covers Jet\Image::getHeight
	 * @covers Jet\Image::getImgType
	 * @covers Jet\Image::getMimeType
	 */
	public function testGetPath() {

		$image = new Image( IMAGE_TEST_BASEDIR.'TestImage1.gif' );
		$this->assertEquals('TestImage1.gif', $image->getFileName());
		$this->assertEquals( IMAGE_TEST_BASEDIR.'TestImage1.gif', $image->getPath() );
		$this->assertEquals( IMAGE_TEST_BASEDIR, $image->getDirectory() );
		$this->assertSame( 500, $image->getWidth() );
		$this->assertSame( 300, $image->getHeight() );
		$this->assertSame( Image::TYPE_GIF, $image->getImgType());
		$this->assertEquals( 'image/gif', $image->getMimeType() );

		$image = new Image( IMAGE_TEST_BASEDIR.'TestImage1.png' );
		$this->assertEquals('TestImage1.png', $image->getFileName());
		$this->assertEquals( IMAGE_TEST_BASEDIR.'TestImage1.png', $image->getPath() );
		$this->assertEquals( IMAGE_TEST_BASEDIR, $image->getDirectory() );
		$this->assertSame( 500, $image->getWidth() );
		$this->assertSame( 300, $image->getHeight() );
		$this->assertSame( Image::TYPE_PNG, $image->getImgType());
		$this->assertEquals( 'image/png', $image->getMimeType() );

		$image = new Image( IMAGE_TEST_BASEDIR.'TestImage1.jpg' );
		$this->assertEquals('TestImage1.jpg', $image->getFileName());
		$this->assertEquals( IMAGE_TEST_BASEDIR.'TestImage1.jpg', $image->getPath() );
		$this->assertEquals( IMAGE_TEST_BASEDIR, $image->getDirectory() );
		$this->assertSame( 500, $image->getWidth() );
		$this->assertSame( 300, $image->getHeight() );
		$this->assertSame( Image::TYPE_JPG, $image->getImgType());
		$this->assertEquals( 'image/jpeg', $image->getMimeType() );



		$image = new Image( IMAGE_TEST_BASEDIR.'TestImage2.gif' );
		$this->assertEquals('TestImage2.gif', $image->getFileName());
		$this->assertEquals( IMAGE_TEST_BASEDIR.'TestImage2.gif', $image->getPath() );
		$this->assertEquals( IMAGE_TEST_BASEDIR, $image->getDirectory() );
		$this->assertSame( 300, $image->getWidth() );
		$this->assertSame( 500, $image->getHeight() );
		$this->assertSame( Image::TYPE_GIF, $image->getImgType());
		$this->assertEquals( 'image/gif', $image->getMimeType() );

		$image = new Image( IMAGE_TEST_BASEDIR.'TestImage2.png' );
		$this->assertEquals('TestImage2.png', $image->getFileName());
		$this->assertEquals( IMAGE_TEST_BASEDIR.'TestImage2.png', $image->getPath() );
		$this->assertEquals( IMAGE_TEST_BASEDIR, $image->getDirectory() );
		$this->assertSame( 300, $image->getWidth() );
		$this->assertSame( 500, $image->getHeight() );
		$this->assertSame( Image::TYPE_PNG, $image->getImgType());
		$this->assertEquals( 'image/png', $image->getMimeType() );

		$image = new Image( IMAGE_TEST_BASEDIR.'TestImage2.jpg' );
		$this->assertEquals('TestImage2.jpg', $image->getFileName());
		$this->assertEquals( IMAGE_TEST_BASEDIR.'TestImage2.jpg', $image->getPath() );
		$this->assertEquals( IMAGE_TEST_BASEDIR, $image->getDirectory() );
		$this->assertSame( 300, $image->getWidth() );
		$this->assertSame( 500, $image->getHeight() );
		$this->assertSame( Image::TYPE_JPG, $image->getImgType());
		$this->assertEquals( 'image/jpeg', $image->getMimeType() );

	}




	/**
	 * @covers Jet\Image::setImageQuality
	 * @covers Jet\Image::getImageQuality
	 */
	public function testSetGetImageQuality() {
		$image = new Image( IMAGE_TEST_BASEDIR.'TestImage1.jpg' );

		$this->assertEquals(85, $image->getImageQuality());
		$image->setImageQuality(90);
		$this->assertEquals(90, $image->getImageQuality());
	}

	/**
	 * @covers Jet\Image::createThumbnail
	 * @covers Jet\Image::saveAs
	 */
	public function testCreateThumbnailAndSaveAs() {
		$image = new Image( IMAGE_TEST_BASEDIR.'TestImage1.gif' );
		$thumbnail = $image->createThumbnail(JET_TESTS_TMP.'thumbnail.gif', 30, 30);
		$this->assertEquals(30, $thumbnail->getWidth());
		$this->assertEquals(18, $thumbnail->getHeight());

		$image = new Image( IMAGE_TEST_BASEDIR.'TestImage1.jpg' );
		$thumbnail = $image->createThumbnail(JET_TESTS_TMP.'thumbnail.jpg', 30, 30);
		$this->assertEquals(30, $thumbnail->getWidth());
		$this->assertEquals(18, $thumbnail->getHeight());

		$image = new Image( IMAGE_TEST_BASEDIR.'TestImage1.png' );
		$thumbnail = $image->createThumbnail(JET_TESTS_TMP.'thumbnail.png', 30, 30);
		$this->assertEquals(30, $thumbnail->getWidth());
		$this->assertEquals(18, $thumbnail->getHeight());

		$image = new Image( IMAGE_TEST_BASEDIR.'TestImage2.gif' );
		$thumbnail = $image->createThumbnail(JET_TESTS_TMP.'thumbnail2.gif', 30, 30);
		$this->assertEquals(18, $thumbnail->getWidth());
		$this->assertEquals(30, $thumbnail->getHeight());

		$image = new Image( IMAGE_TEST_BASEDIR.'TestImage2.jpg' );
		$thumbnail = $image->createThumbnail(JET_TESTS_TMP.'thumbnail2.jpg', 30, 30);
		$this->assertEquals(18, $thumbnail->getWidth());
		$this->assertEquals(30, $thumbnail->getHeight());

		$image = new Image( IMAGE_TEST_BASEDIR.'TestImage2.png' );
		$thumbnail = $image->createThumbnail(JET_TESTS_TMP.'thumbnail2.png', 30, 30);
		$this->assertEquals(18, $thumbnail->getWidth());
		$this->assertEquals(30, $thumbnail->getHeight());

	}

}
