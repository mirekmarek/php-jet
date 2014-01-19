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
 * @subpackage IO_Dir
 */
namespace Jet;

define('IO_DIR_TEST_BASEDIR', JET_TESTS_DATA.'IO/Dir/');


class IO_DirTest extends \PHPUnit_Framework_TestCase {

	protected $imaginary_dir_path = '/path/to/imaginary/directory/';

	protected $create_test_path = '';
	protected $remove_test_path = '';
	protected $copy_test_target_path = '';
	protected $copy_test_source_path = '';

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->create_test_path = JET_TESTS_TMP.'IO_Dir_create_test/';
		$this->remove_test_path = JET_TESTS_TMP.'IO_Dir_remove_test/';
		$this->copy_test_target_path = JET_TESTS_TMP.'IO_Dir_copy_test_target/';
		$this->copy_test_source_path = IO_DIR_TEST_BASEDIR.'readable/';
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
		if(is_dir($this->create_test_path)) {
			rmdir( $this->create_test_path );
		}
		if(is_dir($this->remove_test_path)) {
			rmdir( $this->remove_test_path );
		}
		if(is_dir($this->copy_test_target_path)) {
			unlink($this->copy_test_target_path.'subdir/subdir/file.txt');
			rmdir($this->copy_test_target_path.'subdir/subdir/');
			rmdir($this->copy_test_target_path.'subdir/');
			rmdir($this->copy_test_target_path);
		}
	}


	/**
	 * @covers Jet\IO_Dir::setDefaultChmodMask
	 * @covers Jet\IO_Dir::getDefaultChmodMask
	 */
	public function testSetGetDefaultChmodMask() {
		$this->assertEquals(0777, IO_Dir::getDefaultChmodMask());
		IO_Dir::setDefaultChmodMask(0700);
		$this->assertEquals(0700, IO_Dir::getDefaultChmodMask());
	}


	/**
	 * @covers Jet\IO_Dir::exists
	 */
	public function testExists() {
		$this->assertFalse( IO_Dir::exists( IO_DIR_TEST_BASEDIR.'imaginary/' ) );
		$this->assertTrue( IO_Dir::exists( IO_DIR_TEST_BASEDIR.'not-readable/' ) );
		$this->assertTrue( IO_Dir::exists( IO_DIR_TEST_BASEDIR.'readable/' ) );
	}

	/**
	 * @covers Jet\IO_Dir::isReadable
	 */
	public function testIsReadable() {
		$this->assertFalse( IO_Dir::isReadable( IO_DIR_TEST_BASEDIR.'imaginary/' ) );
		$this->assertFalse( IO_Dir::isReadable( IO_DIR_TEST_BASEDIR.'not-readable/' ) );
		$this->assertTrue( IO_Dir::isReadable( IO_DIR_TEST_BASEDIR.'readable/' ) );
	}

	/**
	 * @covers Jet\IO_Dir::isWritable
	 */
	public function testIsWritable() {
		$this->assertFalse( IO_Dir::isWritable( IO_DIR_TEST_BASEDIR.'imaginary/' ) );
		$this->assertFalse( IO_Dir::isWritable( IO_DIR_TEST_BASEDIR.'not-writeable/' ) );
		$this->assertTrue( IO_Dir::isWritable( IO_DIR_TEST_BASEDIR.'writeable/' ) );
	}

	/**
	 * @covers Jet\IO_Dir::create
	 *
	 * @expectedException \Jet\IO_Dir_Exception
	 * @expectedExceptionCode \Jet\IO_Dir_Exception::CODE_CREATE_FAILED
	 */
	public function testCreateImaginaryDirectory() {
		@IO_Dir::create( $this->imaginary_dir_path);
	}


	/**
	 * @covers Jet\IO_Dir::create
	 */
	public function testCreate() {
		IO_Dir::create( $this->create_test_path );
		$this->assertTrue( is_dir($this->create_test_path) );
	}

	/**
	 * @covers Jet\IO_Dir::create
	 *
	 * @expectedException \Jet\IO_Dir_Exception
	 * @expectedExceptionCode \Jet\IO_Dir_Exception::CODE_REMOVE_FAILED
	 */
	public function testRemoveImaginaryDirectory() {
		@IO_Dir::remove( $this->imaginary_dir_path);
	}


	/**
	 * @covers Jet\IO_Dir::remove
	 * @covers Jet\IO_Dir::rename
	 * @covers Jet\IO_Dir::move
	 */
	public function testRemove() {
		IO_Dir::create( $this->remove_test_path );
		IO_Dir::remove( $this->remove_test_path );
		$this->assertFalse( is_dir($this->remove_test_path) );
	}


	/**
	 * @covers Jet\IO_Dir::copy
	 * @covers Jet\IO_Dir::rename
	 * @covers Jet\IO_Dir::move
	 *
	 * @expectedException \Jet\IO_Dir_Exception
	 * @expectedExceptionCode \Jet\IO_Dir_Exception::CODE_COPY_FAILED
	 */
	public function testCopyFaildImaginarySource() {
		@IO_Dir::copy($this->imaginary_dir_path, $this->copy_test_target_path);
	}

	/**
	 * @covers Jet\IO_Dir::copy
	 * @covers Jet\IO_Dir::rename
	 * @covers Jet\IO_Dir::move
	 *
	 * @expectedException \Jet\IO_Dir_Exception
	 * @expectedExceptionCode \Jet\IO_Dir_Exception::CODE_CREATE_FAILED
	 */
	public function testCopyFaildImaginaryTarget() {
		@IO_Dir::copy($this->copy_test_source_path, $this->imaginary_dir_path);
	}


	/**
	 * @covers Jet\IO_Dir::copy
	 * @covers Jet\IO_Dir::rename
	 * @covers Jet\IO_Dir::move
	 *
	 * @expectedException \Jet\IO_Dir_Exception
	 * @expectedExceptionCode \Jet\IO_Dir_Exception::CODE_COPY_FAILED
	 */
	public function testCopyFaildExists() {
		@IO_Dir::copy($this->copy_test_source_path, $this->copy_test_source_path, false);
	}

	/**
	 * @covers Jet\IO_Dir::copy
	 * @covers Jet\IO_Dir::rename
	 * @covers Jet\IO_Dir::move
	 *
	 */
	public function testCopy() {
		IO_Dir::copy($this->copy_test_source_path, $this->copy_test_target_path);

		$this->assertTrue( file_exists($this->copy_test_target_path.'subdir/subdir/file.txt') );
		$this->assertEquals('IO_Dir::copy test', file_get_contents($this->copy_test_target_path.'subdir/subdir/file.txt'));
	}

	protected function getValidDataGetListTest( $get_dirs=true, $get_files=true, $filter=false ) {
		$files_list = array (
			'SubDir1',
			'SubDir2',
			'SubDirN',
			'file1.txt',
			'file2.txt',
			'fileN.txt',
		);


		$valid_data = array();
		foreach( $files_list as $f ) {
			$path = IO_DIR_TEST_BASEDIR.'getlist'.DIRECTORY_SEPARATOR.$f;

			$is_dir = $f[0]=='S';

			if($is_dir && !$get_dirs) {
				continue;
			}
			if(!$is_dir && !$get_files) {
				continue;
			}

			if($filter && strpos($f, 'N')===false) {
				continue;
			}

			if($is_dir) {
				$path .= DIRECTORY_SEPARATOR;
			}

			$valid_data[$path] = $f;

		}

		return $valid_data;
	}

	/**
	 * @covers Jet\IO_Dir::getList
	 */
	public function testGetList() {

		$this->assertEquals( $this->getValidDataGetListTest(), IO_Dir::getList( IO_DIR_TEST_BASEDIR.'getlist/' ));
		$this->assertEquals( $this->getValidDataGetListTest(), IO_Dir::getList( IO_DIR_TEST_BASEDIR.'getlist' ));
		$this->assertEquals( $this->getValidDataGetListTest(true, true, true), IO_Dir::getList( IO_DIR_TEST_BASEDIR.'getlist/', '*N*' ));
		$this->assertEquals( $this->getValidDataGetListTest(true, true, true), IO_Dir::getList( IO_DIR_TEST_BASEDIR.'getlist', '*N*' ));

	}


	/**
	 * @covers Jet\IO_Dir::getFilesList
	 */
	public function testGetFilesList() {
		$this->assertEquals( $this->getValidDataGetListTest(false), IO_Dir::getFilesList( IO_DIR_TEST_BASEDIR.'getlist/' ));
		$this->assertEquals( $this->getValidDataGetListTest(false), IO_Dir::getFilesList( IO_DIR_TEST_BASEDIR.'getlist' ));
		$this->assertEquals( $this->getValidDataGetListTest(false, true, true), IO_Dir::getFilesList( IO_DIR_TEST_BASEDIR.'getlist/', '*N*' ));
		$this->assertEquals( $this->getValidDataGetListTest(false, true, true), IO_Dir::getFilesList( IO_DIR_TEST_BASEDIR.'getlist', '*N*' ));
	}

	/**
	 * @covers Jet\IO_Dir::getSubdirectoriesList
	 */
	public function testGetSubdirectoriesList() {
		$this->assertEquals( $this->getValidDataGetListTest(true, false), IO_Dir::getSubdirectoriesList( IO_DIR_TEST_BASEDIR.'getlist/' ));
		$this->assertEquals( $this->getValidDataGetListTest(true, false), IO_Dir::getSubdirectoriesList( IO_DIR_TEST_BASEDIR.'getlist' ));
		$this->assertEquals( $this->getValidDataGetListTest(true, false, true), IO_Dir::getSubdirectoriesList( IO_DIR_TEST_BASEDIR.'getlist/', '*N*' ));
		$this->assertEquals( $this->getValidDataGetListTest(true, false, true), IO_Dir::getSubdirectoriesList( IO_DIR_TEST_BASEDIR.'getlist', '*N*' ));
	}

}
