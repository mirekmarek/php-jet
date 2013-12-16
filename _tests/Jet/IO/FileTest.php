<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package IO
 * @subpackage IO_File
 */
namespace Jet;

define("IO_FILE_TEST_BASEDIR", JET_TESTS_DATA . "IO/File/");


class IO_FileTest extends \PHPUnit_Framework_TestCase {

	protected $imaginary_file_path = "/path/to/imaginary/directory/file.txt";

	protected $write_test_path = "";
	protected $chmod_test_path = "";
	protected $append_test_path = "";
	protected $delete_test_path = "";
	protected $copy_test_target_path = "";
	protected $copy_test_source_path = "";
	protected $rename_test_target_path = "";
	protected $rename_test_source_path = "";

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->write_test_path = JET_TESTS_TMP . "IO_File_write_test.txt";
		$this->chmod_test_path = JET_TESTS_TMP . "IO_File_chmod_test";
		$this->append_test_path = JET_TESTS_TMP . "IO_File_append_test.txt";
		$this->delete_test_path = JET_TESTS_TMP . "IO_File_delete_test.txt";
		$this->copy_test_target_path = JET_TESTS_TMP . "IO_File_copy_test_target.txt";
		$this->copy_test_source_path = IO_FILE_TEST_BASEDIR . "readable.txt";
		$this->rename_test_target_path = JET_TESTS_TMP . "IO_File_rename_test_target.txt";
		$this->rename_test_source_path = JET_TESTS_TMP . "IO_File_rename_test_source.txt";
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
		if(file_exists($this->write_test_path)) {
			unlink($this->write_test_path);
		}
		if(file_exists($this->chmod_test_path)) {
			unlink($this->chmod_test_path);
		}
		if(file_exists($this->append_test_path)) {
			unlink($this->append_test_path);
		}
		if(file_exists($this->delete_test_path)) {
			unlink($this->delete_test_path);
		}
		if(file_exists($this->copy_test_target_path)) {
			unlink($this->copy_test_target_path);
		}
		if(file_exists($this->rename_test_target_path)) {
			unlink($this->rename_test_target_path);
		}
		if(file_exists($this->rename_test_source_path)) {
			unlink($this->rename_test_source_path);
		}
	}

	/**
	 * @covers Jet\IO_File::setDefaultChmodMask
	 * @covers Jet\IO_File::getDefaultChmodMask
	 */
	public function testSetGetDefaultChmodMask() {
		$this->assertEquals(0666, IO_File::getDefaultChmodMask());
		IO_File::setDefaultChmodMask(0600);
		$this->assertEquals(0600, IO_File::getDefaultChmodMask());
	}

	/**
	 * @covers Jet\IO_File::exists
	 */
	public function testExists() {
		$this->assertFalse(IO_File::exists(IO_FILE_TEST_BASEDIR . "imaginary.file"));
		$this->assertTrue(IO_File::exists(IO_FILE_TEST_BASEDIR . "not-readable.txt"));
		$this->assertTrue(IO_File::exists(IO_FILE_TEST_BASEDIR . "readable.txt"));
	}

	/**
	 * @covers Jet\IO_File::isReadable
	 */
	public function testIsReadable() {
		$this->assertFalse(IO_File::isReadable(IO_FILE_TEST_BASEDIR . "imaginary.file"));
		$this->assertFalse(IO_File::isReadable(IO_FILE_TEST_BASEDIR . "not-readable.txt"));
		$this->assertTrue(IO_File::isReadable(IO_FILE_TEST_BASEDIR . "readable.txt"));
	}

	/**
	 * @covers Jet\IO_File::isWritable
	 */
	public function testIsWritable() {
		$this->assertFalse(IO_File::isWritable(IO_FILE_TEST_BASEDIR . "imaginary.file"));
		$this->assertFalse(IO_File::isWritable(IO_FILE_TEST_BASEDIR . "not-writeable.txt"));
		$this->assertTrue(IO_File::isWritable(IO_FILE_TEST_BASEDIR . "writeable.txt"));
	}

	/**
	 * @covers Jet\IO_File::write
	 *
	 * @expectedException \Jet\IO_Dir_Exception
	 * @expectedExceptionCode \Jet\IO_Dir_Exception::CODE_CREATE_FAILED
	 */
	public function testWriteImaginaryDirectory() {
		@IO_File::write($this->imaginary_file_path, "data");
	}

	/**
	 * @covers Jet\IO_File::write
	 *
	 * @expectedException \Jet\IO_File_Exception
	 * @expectedExceptionCode \Jet\IO_File_Exception::CODE_WRITE_FAILED
	 */
	public function testWriteFailed() {
		@IO_File::write(IO_FILE_TEST_BASEDIR . "/not-writable-dir/file.txt", "data");
	}

	/**
	 * @covers Jet\IO_File::write
	 */
	public function testWrite() {
		IO_File::write($this->write_test_path, "IO_File::write test");
		$this->assertTrue(file_exists($this->write_test_path));
	}

	/**
	 * @covers Jet\IO_File::chmod
	 *
	 * @expectedException \Jet\IO_File_Exception
	 * @expectedExceptionCode \Jet\IO_File_Exception::CODE_CHMOD_FAILED
	 */
	public function testChmodFailed() {
		@IO_File::chmod($this->imaginary_file_path);
	}


	/**
	 * @covers Jet\IO_File::chmod
	 *
	 */
	public function testChmod() {

		file_put_contents($this->chmod_test_path, "IO_File::chmod test");

		IO_File::setDefaultChmodMask(0500);
		IO_File::chmod($this->chmod_test_path);

		clearstatcache();
		$file_stat = stat($this->chmod_test_path);
		$this->assertTrue((0500 & $file_stat["mode"]) == 0500);

		IO_File::chmod($this->chmod_test_path, 0666);
		clearstatcache();
		$file_stat = stat($this->chmod_test_path);
		$this->assertTrue((0666 & $file_stat["mode"]) == 0666);

	}

	/**
	 * @covers Jet\IO_File::append
	 *
	 * @expectedException \Jet\IO_Dir_Exception
	 * @expectedExceptionCode \Jet\IO_Dir_Exception::CODE_CREATE_FAILED
	 */
	public function testAppendFailed() {
		@IO_File::append($this->imaginary_file_path, "data");
	}

	/**
	 * @covers Jet\IO_File::append
	 */
	public function testAppend() {
		file_put_contents($this->append_test_path, "old data");

		IO_File::append($this->append_test_path, "/new data");

		$this->assertEquals("old data/new data", file_get_contents($this->append_test_path));
	}

	/**
	 * @covers Jet\IO_File::read
	 *
	 * @expectedException \Jet\IO_File_Exception
	 * @expectedExceptionCode \Jet\IO_File_Exception::CODE_READ_FAILED
	 */
	public function testReadFailed() {
		@IO_File::read($this->imaginary_file_path);
	}

	/**
	 * @covers Jet\IO_File::read
	 */
	public function testRead() {
		$this->assertEquals("read test", IO_File::read(IO_FILE_TEST_BASEDIR . "readable.txt"));
	}

	/**
	 * @covers Jet\IO_File::delete
	 *
	 * @expectedException \Jet\IO_File_Exception
	 * @expectedExceptionCode \Jet\IO_File_Exception::CODE_DELETE_FAILED
	 */
	public function testDeleteFailed() {
		@IO_File::delete($this->imaginary_file_path);
	}


	/**
	 * @covers Jet\IO_File::delete
	 */
	public function testDelete() {
		file_put_contents($this->delete_test_path, "delete data");

		IO_File::delete($this->delete_test_path);
		$this->assertFalse(file_exists($this->delete_test_path));
	}


	/**
	 * @covers Jet\IO_File::copy
	 *
	 * @expectedException \Jet\IO_File_Exception
	 * @expectedExceptionCode \Jet\IO_File_Exception::CODE_COPY_FAILED
	 */
	public function testCopyFailedImaginarySource() {
		@IO_File::copy($this->imaginary_file_path, $this->copy_test_target_path);
	}

	/**
	 * @covers Jet\IO_File::copy
	 *
	 * @expectedException \Jet\IO_File_Exception
	 * @expectedExceptionCode \Jet\IO_File_Exception::CODE_COPY_FAILED
	 */
	public function testCopyFailedImaginaryTarget() {
		@IO_File::copy($this->copy_test_source_path, $this->imaginary_file_path);
	}


	/**
	 * @covers Jet\IO_File::copy
	 *
	 * @expectedException \Jet\IO_File_Exception
	 * @expectedExceptionCode \Jet\IO_File_Exception::CODE_COPY_FAILED
	 */
	public function testCopyFailedExists() {
		@IO_File::copy($this->copy_test_target_path, $this->copy_test_target_path, false);
	}

	/**
	 * @covers Jet\IO_File::copy
	 *
	 */
	public function testCopy() {
		IO_File::copy($this->copy_test_source_path, $this->copy_test_target_path);

		$this->assertTrue(file_exists($this->copy_test_target_path));
		$this->assertEquals("read test", file_get_contents($this->copy_test_target_path));
	}

	/**
	 * @covers Jet\IO_File::rename
	 * @covers Jet\IO_File::move
	 *
	 * @expectedException \Jet\IO_File_Exception
	 * @expectedExceptionCode \Jet\IO_File_Exception::CODE_COPY_FAILED
	 */
	public function testRenameFailedImaginarySource() {
		@IO_File::rename($this->imaginary_file_path, $this->rename_test_target_path);
	}

	/**
	 * @covers Jet\IO_File::rename
	 * @covers Jet\IO_File::move
	 *
	 * @expectedException \Jet\IO_File_Exception
	 * @expectedExceptionCode \Jet\IO_File_Exception::CODE_COPY_FAILED
	 */
	public function testRenameFailedImaginaryTarget() {
		@IO_File::rename($this->rename_test_source_path, $this->imaginary_file_path);
	}


	/**
	 * @covers Jet\IO_File::rename
	 * @covers Jet\IO_File::move
	 *
	 * @expectedException \Jet\IO_File_Exception
	 * @expectedExceptionCode \Jet\IO_File_Exception::CODE_COPY_FAILED
	 */
	public function testRenameFailedExists() {
		@IO_File::rename($this->rename_test_target_path, $this->rename_test_target_path, false);
	}

	/**
	 * @covers Jet\IO_File::rename
	 * @covers Jet\IO_File::move
	 *
	 */
	public function testRename() {
		file_put_contents($this->rename_test_source_path, "rename test");

		IO_File::rename($this->rename_test_source_path, $this->rename_test_target_path);

		$this->assertFalse(file_exists($this->rename_test_source_path));
		$this->assertTrue(file_exists($this->rename_test_target_path));
		$this->assertEquals("rename test", file_get_contents($this->rename_test_target_path));
	}

	/**
	 * @covers Jet\IO_File::getSize
	 */
	public function testGetSize() {
		$this->assertEquals(9, IO_File::getSize(IO_FILE_TEST_BASEDIR . "readable.txt"));
	}

	/**
	 * @covers Jet\IO_File::moveUploadedFile
	 */
	public function testMoveUploadedFile() {
		//TODO: how to do it?
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @covers Jet\IO_File::getMimeType
	 */
	public function testGetMimeType() {
		$this->assertEquals( "application/msword-test", IO_File::getMimeType(IO_FILE_TEST_BASEDIR."mime/doc.docx", IO_FILE_TEST_BASEDIR."mime/map.php") );
		$this->assertEquals( "application/msexcel-test", IO_File::getMimeType(IO_FILE_TEST_BASEDIR."mime/sheet.xlsx", IO_FILE_TEST_BASEDIR."mime/map.php") );
		$this->assertEquals( "application/msword", IO_File::getMimeType(IO_FILE_TEST_BASEDIR."mime/doc.doc") );
		$this->assertEquals( "application/vnd.ms-excel", IO_File::getMimeType(IO_FILE_TEST_BASEDIR."mime/sheet.xls" ) );

	}

	/**
	 * @covers Jet\IO_File::getMaxUploadSize
	 */
	public function testGetMaxUploadSize() {
		$max_upload = ini_get("upload_max_filesize");
		$max_post = ini_get("post_max_size");

		$units = array("" => 1, "K"=>1024, "M"=>1024*1024, "G"=>1024*1024*1024);

		$max_post_unit = substr($max_post, -1);
		$max_upload_unit = substr($max_upload, -1);


		$max_post = $max_post*$units[$max_post_unit];
		$max_upload = $max_upload*$units[$max_upload_unit];

		$valid_value = min($max_upload, $max_post );

		$this->assertEquals($valid_value, IO_File::getMaxUploadSize());
	}

	/**
	 * @covers Jet\IO_File::download
	 * @todo   Implement testDownload().
	 */
	public function testDownload() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @covers Jet\IO_File::sendDownloadFileHeaders
	 * @todo   Implement testSendDownloadFileHeaders().
	 */
	public function testSendDownloadFileHeaders() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

}
