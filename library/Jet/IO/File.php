<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

use finfo;

require_once 'File/Exception.php';

/**
 *
 */
class IO_File
{

	/**
	 * Chmod mask for new file
	 *
	 * @var int
	 */
	protected static $default_chmod_mask;

	/**
	 * @var array
	 */
	protected static $http_response_header;

	/**
	 * @var array
	 */
	protected static $extensions_mimes_map;

	/**
	 * @return int
	 */
	public static function getDefaultChmodMask()
	{
		if( static::$default_chmod_mask===null ) {
			static::$default_chmod_mask = SysConf_Jet::IO_CHMOD_MASK_FILE();
		}

		return static::$default_chmod_mask;
	}

	/**
	 * @param int $default_chmod_mask
	 */
	public static function setDefaultChmodMask( $default_chmod_mask )
	{
		static::$default_chmod_mask = $default_chmod_mask;
	}

	/**
	 * @return array
	 */
	public static function getExtensionsMimesMap()
	{
		return static::$extensions_mimes_map;
	}

	/**
	 * @param array $extensions_mimes_map
	 */
	public static function setExtensionsMimesMap( array $extensions_mimes_map )
	{
		static::$extensions_mimes_map = $extensions_mimes_map;
	}

	/**
	 *
	 * @param string $file_path
	 *
	 * @return bool
	 */
	public static function exists( $file_path )
	{
		return is_file( $file_path );
	}

	/**
	 *
	 * @param string $file_path
	 *
	 * @return bool
	 */
	public static function isWritable( $file_path )
	{
		return ( is_file( $file_path )&&is_writable( $file_path ) );
	}

	/**
	 *
	 * @param string $file_path
	 *
	 * @return bool
	 */
	public static function isReadable( $file_path )
	{
		return ( is_file( $file_path ) && is_readable( $file_path ) );
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
	public static function getSize( $file_path )
	{

		$size = filesize( $file_path );

		if( $size===false ) {
			$error = static::_getLastError();
			throw new IO_File_Exception(
				'Unable to get size of file \''.$file_path.'\'. Error message: '.$error['message'],
				IO_File_Exception::CODE_GET_FILE_SIZE_FAILED
			);
		}

		return $size;
	}

	/**
	 *
	 * @param string      $file_path
	 * @param bool        $without_charset (optional)
	 *
	 * @return string
	 */
	public static function getMimeType( $file_path, $without_charset = true )
	{

		$mime_type = null;

		if( is_array( static::$extensions_mimes_map ) ) {
			$extension = strtolower( pathinfo( $file_path, PATHINFO_EXTENSION ) );

			if( isset( static::$extensions_mimes_map[$extension] ) ) {
				$mime_type = static::$extensions_mimes_map[$extension];
			}
		}

		if( !$mime_type ) {
			$file_info = new finfo( FILEINFO_MIME );
			$mime_type = $file_info->file( $file_path );
			unset( $file_info );
		}

		if(
			$without_charset &&
			( $pos = strpos( $mime_type, ';' ) )!==false
		) {
			$mime_type = substr( $mime_type, 0, $pos );
		}


		return $mime_type;
	}


	/**
	 *
	 * @param string $file_path - path to file
	 * @param string $data - data to be written
	 *
	 * @throws IO_File_Exception
	 *
	 */
	public static function write( $file_path, $data )
	{
		static::_write( $file_path, $data, false );
	}

	/**
	 * Writes data to file including file locking
	 *
	 * @param string $file_path
	 * @param string $data
	 * @param bool   $append
	 *
	 * @throws IO_File_Exception
	 */
	protected static function _write( $file_path, $data, $append )
	{

		$is_new = false;
		if( !file_exists( $file_path ) ) {
			$target_dir = dirname( $file_path );
			if( !IO_Dir::exists( $target_dir ) ) {
				IO_Dir::create( $target_dir );
			}
			$is_new = true;
		}

		$flags = $append ? FILE_APPEND : null;

		if( !file_put_contents( $file_path, $data, $flags ) ) {
			$error = static::_getLastError();
			if(
				$data &&
				$error
			) {
				throw new IO_File_Exception(
					'Unable to write file \''.$file_path.'\'. Error message: '.$error['message'],
					IO_File_Exception::CODE_WRITE_FAILED
				);
			}

		}

		if( $is_new ) {
			static::chmod( $file_path );
		}
	}

	/**
	 *
	 * @param string $file_path
	 * @param int|null    $chmod_mask (optional, default: by application configuration)
	 *
	 * @throws IO_File_Exception
	 */
	public static function chmod( $file_path, $chmod_mask = null )
	{
		$chmod_mask = ( $chmod_mask===null ) ? static::getDefaultChmodMask() : $chmod_mask;

		if( !chmod( $file_path, $chmod_mask ) ) {
			$error = static::_getLastError();

			throw new IO_File_Exception(
				'Unable to chmod \''.$file_path.'\'. Error message: '.$error['message'],
				IO_File_Exception::CODE_CHMOD_FAILED
			);
		}

	}


	/**
	 *
	 * @param string $file_path
	 * @param mixed  $data
	 *
	 * @throws IO_File_Exception
	 *
	 */
	public static function append( $file_path, $data )
	{
		static::_write( $file_path, $data, true );
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
	public static function read( $file_path )
	{
		static::$http_response_header = null;
		$data = file_get_contents( $file_path );

		if( isset( $http_response_header ) ) {
			static::$http_response_header = $http_response_header;

			foreach( $http_response_header as $header ) {
				if(
					stristr( $header, 'content-encoding' ) &&
					stristr( $header, 'gzip' )
				) {
					$data = gzdecode( $data );
				}
			}

		}

		if( $data===false ) {
			$error = static::_getLastError();
			throw new IO_File_Exception(
				'Unable to read file \''.$file_path.'\'. Error message: '.$error['message'],
				IO_File_Exception::CODE_READ_FAILED
			);
		}

		return $data;
	}

	/**
	 * @return array
	 */
	public static function getHttpResponseHeader()
	{
		return static::$http_response_header;
	}

	/**
	 * Moves/renames uploaded file from $source_path to $target_path
	 *
	 * @param string $source_path
	 * @param string $target_path
	 * @param bool   $overwrite_if_exists (optional, default: true)
	 *
	 * @throws IO_File_Exception
	 */
	public static function moveUploadedFile( $source_path, $target_path, $overwrite_if_exists = true )
	{

		if( !is_uploaded_file( $source_path ) ) {
			throw new IO_File_Exception(
				'File \''.$source_path.'\' is not uploaded file', IO_File_Exception::CODE_IS_NOT_UPLOADED_FILE
			);
		}

		static::move( $source_path, $target_path, $overwrite_if_exists );
	}

	/**
	 *
	 * @param string $source_path
	 * @param string $target_path
	 * @param bool   $overwrite_if_exists (optional, default: true)
	 *
	 * @throws IO_File_Exception
	 *
	 */
	public static function move( $source_path, $target_path, $overwrite_if_exists = true )
	{
		static::rename( $source_path, $target_path, $overwrite_if_exists );
	}

	/**
	 *
	 * @param string $source_path
	 * @param string $target_path
	 * @param bool   $overwrite_if_exists (optional, default: true)
	 *
	 * @throws IO_File_Exception
	 *
	 */
	public static function rename( $source_path, $target_path, $overwrite_if_exists = true )
	{
		static::copy( $source_path, $target_path, $overwrite_if_exists );
		static::delete( $source_path );
	}

	/**
	 *
	 * @param string $source_path
	 * @param string $target_path
	 * @param bool   $overwrite_if_exists (optional, default: true)
	 *
	 * @throws IO_File_Exception
	 */
	public static function copy( $source_path, $target_path, $overwrite_if_exists = true )
	{

		if( file_exists( $target_path ) ) {
			if( $overwrite_if_exists ) {
				static::delete( $target_path );
			} else {
				throw new IO_File_Exception(
					'Unable to copy file \''.$source_path.'\' -> \''.$target_path.'\'. Target already exists.',
					IO_File_Exception::CODE_COPY_FAILED
				);
			}
		}

		if( !copy( $source_path, $target_path ) ) {
			$error = static::_getLastError();
			throw new IO_File_Exception(
				'Unable to copy file \''.$source_path.'\' -> \''.$target_path.'\'. Error message: '.$error['message'],
				IO_File_Exception::CODE_COPY_FAILED
			);
		}

		static::chmod( $target_path );
	}

	/**
	 * Deletes file.
	 *
	 * @param string $file_path
	 *
	 * @throws IO_File_Exception
	 */
	public static function delete( $file_path )
	{
		if( !unlink( $file_path ) ) {
			$error = static::_getLastError();
			throw new IO_File_Exception(
				'Unable to delete file \''.$file_path.'\'. Error message: '.$error['message'],
				IO_File_Exception::CODE_DELETE_FAILED
			);
		}
	}

	/**
	 *
	 * @return int
	 */
	public static function getMaxUploadSize()
	{

		$max_upload = ini_get( 'upload_max_filesize' );
		$max_post = ini_get( 'post_max_size' );

		$units = [ '' => 1, 'K' => 1024, 'M' => 1024*1024, 'G' => 1024*1024*1024 ];

		$max_post_unit = substr( $max_post, -1 );
		$max_upload_unit = substr( $max_upload, -1 );


		$max_post = $max_post*$units[$max_post_unit];
		$max_upload = $max_upload*$units[$max_upload_unit];

		return min( $max_upload, $max_post );
	}


	/**
	 *
	 * @return int
	 */
	public static function getMaxFileUploads()
	{
		$units = [ '' => 1, 'K' => 1024, 'M' => 1024*1024, 'G' => 1024*1024*1024 ];

		$max_file_uploads = (int)ini_get( 'max_file_uploads' );
		$max_upload = ini_get( 'upload_max_filesize' );
		$max_post = ini_get( 'post_max_size' );


		$max_post_unit = substr( $max_post, -1 );
		$max_upload_unit = substr( $max_upload, -1 );


		$max_post = $max_post*$units[$max_post_unit];
		$max_upload = $max_upload*$units[$max_upload_unit];

		if($max_upload>$max_post) {
			$max_upload = $max_post;
		}

		if( $max_upload*$max_file_uploads>$max_post ) {
			$max_file_uploads = floor($max_post/$max_upload);
		}

		return (int)$max_file_uploads;
	}


	/**
	 *
	 * @param string $file_path
	 * @param string|null $file_name (optional, custom file name header value; default: autodetect)
	 * @param string|null $file_mime (optional, mime type header value; default: autodetect )
	 * @param int|null    $file_size (optional, file size header value; default: autodetect )
	 * @param bool   $force_download (optional, force download header, default: false)
	 *
	 * @throws IO_File_Exception
	 */
	public static function send( $file_path, $file_name = null, $file_mime = null, $file_size = null, $force_download = false )
	{

		if( !static::isReadable( $file_path ) ) {
			throw new IO_File_Exception(
				'File \''.$file_path.'\' is not readable', IO_File_Exception::CODE_READ_FAILED
			);

		}

		if( !$file_name ) {
			$file_name = basename( $file_path );
		}

		if( !$file_size ) {
			$file_size = static::getSize( $file_path );
		}

		if( !$file_mime ) {
			$file_mime = static::getMimeType( $file_path );
		}

		Http_Headers::sendDownloadFileHeaders(
			$file_name, $file_mime, $file_size, $force_download
		);

		$fp = fopen( $file_path, 'r' );
		fpassthru( $fp );
		fclose( $fp );

	}


	/**
	 * @return array
	 */
	protected static function _getLastError()
	{
		if( class_exists( __NAMESPACE__.'\Debug_ErrorHandler', false ) ) {
			$e = Debug_ErrorHandler::getLastError();
			if(!$e) {
				return null;
			}

			return [
				'type'    => $e->getCode(),
				'message' => $e->getMessage(),
				'file'    => $e->getFile(),
				'line'    => $e->getLine(),
			];
		} else {
			return error_get_last();
		}
	}

}