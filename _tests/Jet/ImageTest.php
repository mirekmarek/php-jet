<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

define('IMAGE_TEST_BASEDIR', JET_TESTS_DATA . 'Image/');

class ImageTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var Data_Image
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
	 * @covers \Jet\Data_Image::__construct
	 *
	 * @expectedException \Jet\Data_Image_Exception
	 * @expectedExceptionCode \Jet\Data_Image_Exception::CODE_IMAGE_FILE_DOES_NOT_EXIST
	 */
	public function testFailedFileDoesNotExist() {
		new Data_Image( IMAGE_TEST_BASEDIR.'imaginary_file' );
	}

	/**
	 * @covers \Jet\Data_Image::__construct
	 *
	 * @expectedException \Jet\Data_Image_Exception
	 * @expectedExceptionCode \Jet\Data_Image_Exception::CODE_UNSUPPORTED_IMAGE_TYPE
	 */
	public function testFailedUnsupportedImageType() {
		new Data_Image( IMAGE_TEST_BASEDIR.'TestImage.sgi' );
	}

	/**
	 * @covers \Jet\Data_Image::__construct
	 * @covers \Jet\Data_Image::getPath
	 * @covers \Jet\Data_Image::getDirectory
	 * @covers \Jet\Data_Image::getFileName
	 * @covers \Jet\Data_Image::getWidth
	 * @covers \Jet\Data_Image::getHeight
	 * @covers \Jet\Data_Image::getImgType
	 * @covers \Jet\Data_Image::getMimeType
	 */
	public function testGetPath() {

		$image = new Data_Image( IMAGE_TEST_BASEDIR.'TestImage1.gif' );
		$this->assertEquals('TestImage1.gif', $image->getFileName());
		$this->assertEquals( IMAGE_TEST_BASEDIR.'TestImage1.gif', $image->getPath() );
		$this->assertEquals( IMAGE_TEST_BASEDIR, $image->getDirectory() );
		$this->assertSame( 500, $image->getWidth() );
		$this->assertSame( 300, $image->getHeight() );
		$this->assertSame( Data_Image::TYPE_GIF, $image->getImgType());
		$this->assertEquals( 'image/gif', $image->getMimeType() );

		$image = new Data_Image( IMAGE_TEST_BASEDIR.'TestImage1.png' );
		$this->assertEquals('TestImage1.png', $image->getFileName());
		$this->assertEquals( IMAGE_TEST_BASEDIR.'TestImage1.png', $image->getPath() );
		$this->assertEquals( IMAGE_TEST_BASEDIR, $image->getDirectory() );
		$this->assertSame( 500, $image->getWidth() );
		$this->assertSame( 300, $image->getHeight() );
		$this->assertSame( Data_Image::TYPE_PNG, $image->getImgType());
		$this->assertEquals( 'image/png', $image->getMimeType() );

		$image = new Data_Image( IMAGE_TEST_BASEDIR.'TestImage1.jpg' );
		$this->assertEquals('TestImage1.jpg', $image->getFileName());
		$this->assertEquals( IMAGE_TEST_BASEDIR.'TestImage1.jpg', $image->getPath() );
		$this->assertEquals( IMAGE_TEST_BASEDIR, $image->getDirectory() );
		$this->assertSame( 500, $image->getWidth() );
		$this->assertSame( 300, $image->getHeight() );
		$this->assertSame( Data_Image::TYPE_JPG, $image->getImgType());
		$this->assertEquals( 'image/jpeg', $image->getMimeType() );



		$image = new Data_Image( IMAGE_TEST_BASEDIR.'TestImage2.gif' );
		$this->assertEquals('TestImage2.gif', $image->getFileName());
		$this->assertEquals( IMAGE_TEST_BASEDIR.'TestImage2.gif', $image->getPath() );
		$this->assertEquals( IMAGE_TEST_BASEDIR, $image->getDirectory() );
		$this->assertSame( 300, $image->getWidth() );
		$this->assertSame( 500, $image->getHeight() );
		$this->assertSame( Data_Image::TYPE_GIF, $image->getImgType());
		$this->assertEquals( 'image/gif', $image->getMimeType() );

		$image = new Data_Image( IMAGE_TEST_BASEDIR.'TestImage2.png' );
		$this->assertEquals('TestImage2.png', $image->getFileName());
		$this->assertEquals( IMAGE_TEST_BASEDIR.'TestImage2.png', $image->getPath() );
		$this->assertEquals( IMAGE_TEST_BASEDIR, $image->getDirectory() );
		$this->assertSame( 300, $image->getWidth() );
		$this->assertSame( 500, $image->getHeight() );
		$this->assertSame( Data_Image::TYPE_PNG, $image->getImgType());
		$this->assertEquals( 'image/png', $image->getMimeType() );

		$image = new Data_Image( IMAGE_TEST_BASEDIR.'TestImage2.jpg' );
		$this->assertEquals('TestImage2.jpg', $image->getFileName());
		$this->assertEquals( IMAGE_TEST_BASEDIR.'TestImage2.jpg', $image->getPath() );
		$this->assertEquals( IMAGE_TEST_BASEDIR, $image->getDirectory() );
		$this->assertSame( 300, $image->getWidth() );
		$this->assertSame( 500, $image->getHeight() );
		$this->assertSame( Data_Image::TYPE_JPG, $image->getImgType());
		$this->assertEquals( 'image/jpeg', $image->getMimeType() );

	}




	/**
	 * @covers \Jet\Data_Image::setImageQuality
	 * @covers \Jet\Data_Image::getImageQuality
	 */
	public function testSetGetImageQuality() {
		$image = new Data_Image( IMAGE_TEST_BASEDIR.'TestImage1.jpg' );

		$this->assertEquals(85, $image->getImageQuality());
		$image->setImageQuality(90);
		$this->assertEquals(90, $image->getImageQuality());
	}

	/**
	 * @covers \Jet\Data_Image::createThumbnail
	 * @covers \Jet\Data_Image::saveAs
	 */
	public function testCreateThumbnailAndSaveAs() {
		$image = new Data_Image( IMAGE_TEST_BASEDIR.'TestImage1.gif' );
		$thumbnail = $image->createThumbnail(JET_TESTS_TMP.'thumbnail.gif', 30, 30);
		$this->assertEquals(30, $thumbnail->getWidth());
		$this->assertEquals(18, $thumbnail->getHeight());

		$image = new Data_Image( IMAGE_TEST_BASEDIR.'TestImage1.jpg' );
		$thumbnail = $image->createThumbnail(JET_TESTS_TMP.'thumbnail.jpg', 30, 30);
		$this->assertEquals(30, $thumbnail->getWidth());
		$this->assertEquals(18, $thumbnail->getHeight());

		$image = new Data_Image( IMAGE_TEST_BASEDIR.'TestImage1.png' );
		$thumbnail = $image->createThumbnail(JET_TESTS_TMP.'thumbnail.png', 30, 30);
		$this->assertEquals(30, $thumbnail->getWidth());
		$this->assertEquals(18, $thumbnail->getHeight());

		$image = new Data_Image( IMAGE_TEST_BASEDIR.'TestImage2.gif' );
		$thumbnail = $image->createThumbnail(JET_TESTS_TMP.'thumbnail2.gif', 30, 30);
		$this->assertEquals(18, $thumbnail->getWidth());
		$this->assertEquals(30, $thumbnail->getHeight());

		$image = new Data_Image( IMAGE_TEST_BASEDIR.'TestImage2.jpg' );
		$thumbnail = $image->createThumbnail(JET_TESTS_TMP.'thumbnail2.jpg', 30, 30);
		$this->assertEquals(18, $thumbnail->getWidth());
		$this->assertEquals(30, $thumbnail->getHeight());

		$image = new Data_Image( IMAGE_TEST_BASEDIR.'TestImage2.png' );
		$thumbnail = $image->createThumbnail(JET_TESTS_TMP.'thumbnail2.png', 30, 30);
		$this->assertEquals(18, $thumbnail->getWidth());
		$this->assertEquals(30, $thumbnail->getHeight());

	}

}
