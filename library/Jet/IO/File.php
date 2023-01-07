<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	 * @var ?array
	 */
	protected static ?array $http_response_header = null;

	/**
	 *
	 * @param string $file_path
	 *
	 * @return bool
	 */
	public static function exists( string $file_path ): bool
	{
		return is_file( $file_path );
	}

	/**
	 *
	 * @param string $file_path
	 *
	 * @return bool
	 */
	public static function isWritable( string $file_path ): bool
	{
		return (is_file( $file_path ) && is_writable( $file_path ));
	}

	/**
	 *
	 * @param string $file_path
	 *
	 * @return bool
	 */
	public static function isReadable( string $file_path ): bool
	{
		return (is_file( $file_path ) && is_readable( $file_path ));
	}

	/**
	 * Gets file size
	 *
	 * @param string $file_path
	 *
	 * @return int
	 *
	 * @throws IO_File_Exception
	 *
	 */
	public static function getSize( string $file_path ): int
	{
		static::_resetLastError();

		$size = filesize( $file_path );

		if( $size === false ) {
			$error = static::_getLastError();
			throw new IO_File_Exception(
				'Unable to get size of file \'' . $file_path . '\'. Error message: ' . $error['message'],
				IO_File_Exception::CODE_GET_FILE_SIZE_FAILED
			);
		}

		return $size;
	}

	/**
	 *
	 * @param string $file_path
	 * @param bool $without_charset (optional)
	 *
	 * @return string
	 */
	public static function getMimeType( string $file_path, bool $without_charset = true ): string
	{

		$mime_type = null;

		$extensions_mimes_map = SysConf_Jet_IO::getExtensionsMimesMap();

		if( $extensions_mimes_map ) {
			$extension = strtolower( pathinfo( $file_path, PATHINFO_EXTENSION ) );

			if( isset( $extensions_mimes_map[$extension] ) ) {
				$mime_type = $extensions_mimes_map[$extension];
			}
		}

		if( !$mime_type ) {
			$file_info = new finfo( FILEINFO_MIME );
			$mime_type = $file_info->file( $file_path );
			unset( $file_info );
		}

		if(
			$without_charset &&
			($pos = strpos( $mime_type, ';' )) !== false
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
	public static function write( string $file_path, string $data ): void
	{
		static::_write( $file_path, $data, false );
	}

	/**
	 * @param string $path
	 * @param array $data
	 * @param bool $reset_cache
	 *
	 * @throws IO_File_Exception
	 */
	public static function writeDataAsPhp( string $path, array $data, bool $reset_cache=true ) : void
	{
		$content = '<?php' . PHP_EOL . 'return ' . (new Data_Array( $data ))->export();

		IO_File::write( $path, $content );
		Cache::resetOPCache();

	}

	/**
	 * Writes data to file including file locking
	 *
	 * @param string $file_path
	 * @param mixed $data
	 * @param bool $append
	 *
	 * @throws IO_File_Exception
	 */
	protected static function _write( string $file_path, mixed $data, bool $append ): void
	{
		static::_resetLastError();

		$is_new = false;
		if( !file_exists( $file_path ) ) {
			$target_dir = dirname( $file_path );
			if( !IO_Dir::exists( $target_dir ) ) {
				IO_Dir::create( $target_dir );
			}
			$is_new = true;
		}

		$flags = $append ? FILE_APPEND|LOCK_EX : LOCK_EX;

		if( !file_put_contents( $file_path, $data, $flags ) ) {
			$error = static::_getLastError();
			if(
				$data &&
				$error
			) {
				throw new IO_File_Exception(
					'Unable to write file \'' . $file_path . '\'. Error message: ' . $error['message'],
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
	 * @param int|null $chmod_mask (optional, default: by application configuration)
	 *
	 * @throws IO_File_Exception
	 */
	public static function chmod( string $file_path, ?int $chmod_mask = null ): void
	{
		static::_resetLastError();

		$chmod_mask = ($chmod_mask === null) ? SysConf_Jet_IO::getFileMod() : $chmod_mask;

		if( !chmod( $file_path, $chmod_mask ) ) {
			$error = static::_getLastError();

			throw new IO_File_Exception(
				'Unable to chmod \'' . $file_path . '\'. Error message: ' . $error['message'],
				IO_File_Exception::CODE_CHMOD_FAILED
			);
		}

	}


	/**
	 *
	 * @param string $file_path
	 * @param mixed $data
	 *
	 * @throws IO_File_Exception
	 *
	 */
	public static function append( string $file_path, mixed $data ): void
	{
		static::_write( $file_path, $data, true );
	}

	/**
	 * Reads file data
	 *
	 * @param string $file_path
	 *
	 * @return string
	 * @throws IO_File_Exception
	 *
	 */
	public static function read( string $file_path ): string
	{
		static::_resetLastError();

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

		if( $data === false ) {
			$error = static::_getLastError();
			throw new IO_File_Exception(
				'Unable to read file \'' . $file_path . '\'. Error message: ' . $error['message'],
				IO_File_Exception::CODE_READ_FAILED
			);
		}

		return $data;
	}

	/**
	 * @return array
	 */
	public static function getHttpResponseHeader(): array
	{
		return static::$http_response_header;
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
	public static function moveUploadedFile( string $source_path,
	                                         string $target_path,
	                                         bool $overwrite_if_exists = true ): void
	{

		if( !is_uploaded_file( $source_path ) ) {
			throw new IO_File_Exception(
				'File \'' . $source_path . '\' is not uploaded file', IO_File_Exception::CODE_IS_NOT_UPLOADED_FILE
			);
		}

		static::move( $source_path, $target_path, $overwrite_if_exists );
	}

	/**
	 *
	 * @param string $source_path
	 * @param string $target_path
	 * @param bool $overwrite_if_exists (optional, default: true)
	 *
	 * @throws IO_File_Exception
	 *
	 */
	public static function move( string $source_path, string $target_path, bool $overwrite_if_exists = true ): void
	{
		static::rename( $source_path, $target_path, $overwrite_if_exists );
	}

	/**
	 *
	 * @param string $source_path
	 * @param string $target_path
	 * @param bool $overwrite_if_exists (optional, default: true)
	 *
	 * @throws IO_File_Exception
	 *
	 */
	public static function rename( string $source_path, string $target_path, bool $overwrite_if_exists = true ): void
	{
		static::copy( $source_path, $target_path, $overwrite_if_exists );
		static::delete( $source_path );
	}

	/**
	 *
	 * @param string $source_path
	 * @param string $target_path
	 * @param bool $overwrite_if_exists (optional, default: true)
	 *
	 * @throws IO_File_Exception
	 */
	public static function copy( string $source_path, string $target_path, bool $overwrite_if_exists = true ): void
	{
		static::_resetLastError();

		if( file_exists( $target_path ) ) {
			if( $overwrite_if_exists ) {
				static::delete( $target_path );
			} else {
				throw new IO_File_Exception(
					'Unable to copy file \'' . $source_path . '\' -> \'' . $target_path . '\'. Target already exists.',
					IO_File_Exception::CODE_COPY_FAILED
				);
			}
		}

		if( !copy( $source_path, $target_path ) ) {
			$error = static::_getLastError();
			throw new IO_File_Exception(
				'Unable to copy file \'' . $source_path . '\' -> \'' . $target_path . '\'. Error message: ' . $error['message'],
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
	public static function delete( string $file_path ): void
	{
		static::_resetLastError();

		if( !unlink( $file_path ) ) {
			$error = static::_getLastError();
			throw new IO_File_Exception(
				'Unable to delete file \'' . $file_path . '\'. Error message: ' . $error['message'],
				IO_File_Exception::CODE_DELETE_FAILED
			);
		}
	}

	/**
	 *
	 * @return int
	 */
	public static function getMaxUploadSize(): int
	{

		$max_upload = ini_get( 'upload_max_filesize' );
		$max_post = ini_get( 'post_max_size' );

		$units = [''  => 1,
		          'K' => 1024,
		          'M' => 1024 * 1024,
		          'G' => 1024 * 1024 * 1024
		];

		$max_post_unit = substr( $max_post, -1 );
		$max_upload_unit = substr( $max_upload, -1 );


		$max_post = $max_post * $units[$max_post_unit];
		$max_upload = $max_upload * $units[$max_upload_unit];

		return min( $max_upload, $max_post );
	}


	/**
	 *
	 * @return int
	 */
	public static function getMaxFileUploads(): int
	{
		$units = [''  => 1,
		          'K' => 1024,
		          'M' => 1024 * 1024,
		          'G' => 1024 * 1024 * 1024
		];

		$max_file_uploads = (int)ini_get( 'max_file_uploads' );
		$max_upload = ini_get( 'upload_max_filesize' );
		$max_post = ini_get( 'post_max_size' );


		$max_post_unit = substr( $max_post, -1 );
		$max_upload_unit = substr( $max_upload, -1 );


		$max_post = $max_post * $units[$max_post_unit];
		$max_upload = $max_upload * $units[$max_upload_unit];

		if( $max_upload > $max_post ) {
			$max_upload = $max_post;
		}

		if( $max_upload * $max_file_uploads > $max_post ) {
			$max_file_uploads = floor( $max_post / $max_upload );
		}

		return (int)$max_file_uploads;
	}


	/**
	 *
	 * @param string $file_path
	 * @param string|null $file_name (optional, custom file name header value; default: autodetect)
	 * @param string|null $file_mime (optional, mime type header value; default: autodetect )
	 * @param int|null $file_size (optional, file size header value; default: autodetect )
	 * @param bool $force_download (optional, force download header, default: false)
	 *
	 * @throws IO_File_Exception
	 */
	public static function send( string $file_path,
	                             ?string $file_name = null,
	                             ?string $file_mime = null,
	                             ?int $file_size = null,
	                             bool $force_download = false ): void
	{
		static::_resetLastError();

		if( !static::isReadable( $file_path ) ) {
			throw new IO_File_Exception(
				'File \'' . $file_path . '\' is not readable', IO_File_Exception::CODE_READ_FAILED
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
	 *
	 */
	protected static function _resetLastError() : void
	{
		if( class_exists( Debug_ErrorHandler::class, false ) ) {
			Debug_ErrorHandler::resetLastError();
		}
	}

	/**
	 * @return array|null
	 */
	protected static function _getLastError(): array|null
	{
		if( class_exists( Debug_ErrorHandler::class, false ) ) {
			$e = Debug_ErrorHandler::getLastError();
			if( !$e ) {
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