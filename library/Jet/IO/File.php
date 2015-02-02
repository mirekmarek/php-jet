<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package IO
 * @subpackage IO_File
 */
namespace Jet;

require JET_LIBRARY_PATH.'Jet/IO/File/Exception.php';

class IO_File {

	/**
	 * Chmod mask for new file
	 * 
	 * @var int
	 */
	protected static $default_chmod_mask = null;


	/**
	 * @return array
	 */
	protected static function _getLastError() {
		if(class_exists('Jet\Debug_ErrorHandler', false)) {
			return Debug_ErrorHandler::getLastError();
		} else {
			return error_get_last();
		}
	}

	/**
	 * @param int $default_chmod_mask
	 */
	public static function setDefaultChmodMask( $default_chmod_mask ) {
		self::$default_chmod_mask = $default_chmod_mask;
	}

	/**
	 * Gets default chmod mask for new files
	 * @return int
	 */
	public static function getDefaultChmodMask(){
		if(self::$default_chmod_mask===null){
			self::$default_chmod_mask = JET_IO_CHMOD_MASK_FILE;
		}
		return self::$default_chmod_mask;
	}

	/**
	 *
	 * @param string $file_path
	 * @return bool
	 */
	public static function exists($file_path){
		return is_file($file_path);
	}

	/**
	 *
	 * @static
	 *
	 * @param string $file_path
	 *
	 * @return bool
	 */
	public static function isReadable($file_path){
		return (is_file($file_path) && is_readable($file_path));
	}

	/**
	 *
	 * @static
	 *
	 * @param string $file_path
	 *
	 * @return bool
	 */
	public static function isWritable($file_path){
		return (is_file($file_path) && is_writable($file_path));
	}

	/**
	 * File chmod
	 *
	 * @param string $file_path
	 * @param int $chmod_mask (optional, default: by application configuration)
	 *
	 * @throws IO_File_Exception
	 */
	public static function chmod($file_path, $chmod_mask=null){
		$chmod_mask = ($chmod_mask===null) ? self::getDefaultChmodMask() : $chmod_mask;

		if(!chmod($file_path, $chmod_mask)) {
			$error = static::_getLastError();

			throw new IO_File_Exception(
				'Unable to chmod \''.$file_path.'\'. Error message: '.$error['message'],
				IO_File_Exception::CODE_CHMOD_FAILED
			);
		}

	}


	
	/**
	 * Writes data into file. Creates file if does not exist.
	 * 
	 * @param string $file_path - path to file
	 * @param string $data - data to be written
	 *
	 * @throws IO_File_Exception
	 *
	 */
	public static function write($file_path, $data){
		self::_write($file_path, $data, false);
	}

	/**
	 * Appends data to the end of file. Creates file if does not exist.
	 * 
	 * @param string $file_path
	 * @param mixed $data
	 *
	 * @throws IO_File_Exception
	 *
	 */
	public static function append($file_path, $data){
		self::_write($file_path, $data, true );
	}

	/**
	 * Writes data to file including file locking
	 *
	 * @param string $file_path
	 * @param string $data
	 * @param bool $append

	 * @throws IO_File_Exception
	 */
	protected static function _write($file_path, $data, $append){

		$is_new = false;
		if(!file_exists($file_path)){
			$target_dir = dirname($file_path);
			if(!IO_Dir::exists($target_dir)){
				IO_Dir::create($target_dir);
			}
			$is_new = true;
		}

		$flags = $append ? FILE_APPEND : null;

		if(!file_put_contents($file_path, $data, $flags)) {
			$error = static::_getLastError();
			if($data && $error) {
				throw new IO_File_Exception(
					'Unable to write file \''.$file_path.'\'. Error message: '.$error['message'],
					IO_File_Exception::CODE_WRITE_FAILED
				);
			}

		}

		if($is_new) {
			self::chmod($file_path);
		}
	}
	
	/**
	 * Reads file data
	 * 
	 * @param string $file_path
	 *
	 * @throws IO_File_Exception
	 *
	 * @return string
	 */
	public static function read($file_path){
		$data = file_get_contents($file_path);

		if($data===false) {
			$error = static::_getLastError();
			throw new IO_File_Exception(
				'Unable to read file \''.$file_path.'\'. Error message: '.$error['message'],
				IO_File_Exception::CODE_READ_FAILED
			);
		}

		return $data;
	}

	/**
	 * Deletes file.
	 *
	 * @param string $file_path
	 *
	 * @throws IO_File_Exception
	 */
	public static function delete($file_path){
		if(!unlink($file_path)) {
			$error = static::_getLastError();
			throw new IO_File_Exception(
				'Unable to delete file \''.$file_path.'\'. Error message: '.$error['message'],
				IO_File_Exception::CODE_DELETE_FAILED
			);
		}
	}

	/**
	 * Copies file from $source_path to $target_path
	 * 
	 * @param string $source_path
	 * @param string $target_path
	 * @param bool $overwrite_if_exists (optional, default: true)
	 *
	 * @throws IO_File_Exception
	 */
	public static function copy($source_path, $target_path, $overwrite_if_exists=true){

		if(file_exists($target_path)){
			if($overwrite_if_exists){
				self::delete($target_path);
			} else {
				throw new IO_File_Exception(
					'Unable to copy file \''.$source_path.'\' -> \''.$target_path.'\'. Target already exists.',
					IO_File_Exception::CODE_COPY_FAILED
				);
			}
		}

		if(!copy($source_path, $target_path)) {
			$error = static::_getLastError();
			throw new IO_File_Exception(
				'Unable to copy file \''.$source_path.'\' -> \''.$target_path.'\'. Error message: '.$error['message'],
				IO_File_Exception::CODE_COPY_FAILED
			);
		}

		self::chmod($target_path);
	}
	
	/**
	 * Moves (or renames) file from $source_path to $target_path
	 * 
	 * @param string $source_path
	 * @param string $target_path
	 * @param bool $overwrite_if_exists (optional, default: true)
	 *
	 * @throws IO_File_Exception
	 *
	 */
	public static function rename($source_path, $target_path, $overwrite_if_exists=true){
		static::copy($source_path, $target_path, $overwrite_if_exists );
		static::delete($source_path);
	}

	/**
	 * Alias of rename
	 *
	 * @param string $source_path
	 * @param string $target_path
	 * @param bool $overwrite_if_exists (optional, default: true)
	 *
	 * @throws IO_File_Exception
	 *
	 */
	public static function move($source_path, $target_path, $overwrite_if_exists=true){
		static::rename($source_path,  $target_path, $overwrite_if_exists);
	}

	/**
	 * Moves/renames uploaded file from $source_path to $target_path
	 *
	 * @param string $source_path
	 * @param string $target_path
	 * @param bool $overwrite_if_exists (optional, default: true)
	 *
	 * @throws IO_File_Exception

	 */
	public static function moveUploadedFile($source_path, $target_path, $overwrite_if_exists=true){

		if(!is_uploaded_file($source_path)){
			throw new IO_File_Exception(
				'File \''.$source_path.'\' is not uploaded file',
				IO_File_Exception::CODE_IS_NOT_UPLOADED_FILE
			);
		}

		self::move($source_path, $target_path, $overwrite_if_exists);
	}


	/**
	 * Gets file size
	 *
	 * @param string $file_path
	 *
	 * @throws IO_File_Exception
	 *
	 * @return int
	 *
	 */
	public static function getSize($file_path){

		$size = filesize($file_path);

		if( $size===false ){
			$error = static::_getLastError();
			throw new IO_File_Exception(
				'Unable to get size of file \''.$file_path.'\'. Error message: '.$error['message'],
				IO_File_Exception::CODE_GET_FILE_SIZE_FAILED
			);
		}

		return $size;
	}

	/**
	 * Gets mime type of file by given file path.
	 *
	 * @param string $file_path
	 * @param null|string $extensions_mimes_map_file_path (optional, default, JET_CONFIG_PATH/file_mime_types/map.php )
	 * @param bool $without_charset (optional)
	 *
	 * @return string
	 */
	public static function getMimeType($file_path, $extensions_mimes_map_file_path=null, $without_charset=true){
		if(
			!$extensions_mimes_map_file_path &&
			defined('JET_CONFIG_PATH')
		) {
			$extensions_mimes_map_file_path = JET_CONFIG_PATH.'file_mime_types/map.php';
		}

		$mime_type = null;

		if(
			$extensions_mimes_map_file_path &&
			is_readable($extensions_mimes_map_file_path)
		) {
			/** @noinspection PhpIncludeInspection */
			$map = require $extensions_mimes_map_file_path;

			if(is_array($map)) {
				$extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

				if(isset($map[$extension])) {
					$mime_type = $map[$extension];
				}
			}
		}

		if(!$mime_type) {
			$file_info = new \finfo(FILEINFO_MIME);
			$mime_type = $file_info->file($file_path);
			unset($file_info);
		}

		if(
			$without_charset &&
			($pos = strpos($mime_type, ';'))!==false
		) {
			$mime_type = substr($mime_type, 0, $pos);
		}


		return $mime_type;
	}
	

	/**
	 * Gets max allowed file size for upload
	 *
	 * @return int
	 */
	public static function getMaxUploadSize() {
		
		$max_upload = ini_get('upload_max_filesize');
		$max_post = ini_get('post_max_size');

		$units = array('' => 1, 'K'=>1024, 'M'=>1024*1024, 'G'=>1024*1024*1024);

		$max_post_unit = substr($max_post, -1);
		$max_upload_unit = substr($max_upload, -1);


		$max_post = $max_post*$units[$max_post_unit];
		$max_upload = $max_upload*$units[$max_upload_unit];

		return min($max_upload, $max_post );
	}


	/**
	 * Download file
	 *
	 * @param string $file_path
	 * @param string $file_name (optional, custom file name header value; default: autodetect)
	 * @param string $file_mime (optional, mime type header value; default: autodetect )
	 * @param int $file_size (optional, file size header value; default: autodetect )
	 * @param bool $force_download (optional, force download header, default: false)
	 *
	 * @throws IO_File_Exception
	 */
	public static function download(
					$file_path,
					$file_name = null,
					$file_mime = null,
					$file_size = null,
					$force_download = false
				) {

		if(!static::isReadable($file_path)) {
			throw new IO_File_Exception(
				'File \''.$file_path.'\' is not readable',
				IO_File_Exception::CODE_READ_FAILED
			);

		}

		if(!$file_name) {
			$file_name = basename($file_path);
		}

		if(!$file_size) {
			$file_size = static::getSize($file_path);
		}

		if(!$file_mime) {
			$file_mime = static::getMimeType($file_path);
		}

		Http_Headers::sendDownloadFileHeaders(
			$file_name,
			$file_mime,
			$file_size,
			$force_download
		);

		$fp = fopen($file_path, 'r');
		fpassthru($fp);
		Application::end();
	}

}