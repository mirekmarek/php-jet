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
 * @subpackage IO_Dir
 */
namespace Jet;

class IO_Dir {
	
	/**
	 * Chmod mask for new directory
	 * 
	 * @var int
	 */
	protected static $default_chmod_mask = null;

	/**
	 * @return array
	 */
	protected static function _getLastError() {
		if(class_exists(__NAMESPACE__."\\Debug_ErrorHandler", false)) {
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
	 * Gets default chmod mask for directories
	 * @return int
	 */
	public static function getDefaultChmodMask() {
		if( static::$default_chmod_mask===null ) {
			try {
				self::$default_chmod_mask = Application::getConfig()->getIODirsChmod();
			} catch(Exception $e){
				self::$default_chmod_mask = 0777;
			}
		}
		return self::$default_chmod_mask;
	}


	/**
	 * Returns true if path exists and is directory
	 * @param string $dir_path
	 * @return bool
	 */
	public static function exists($dir_path){
		return is_dir($dir_path);
	}

	/**
	 * Is given path readable directory?
	 *
	 * @static
	 *
	 * @param string $dir_path
	 *
	 * @return bool
	 */
	public static function isReadable($dir_path){
		return is_dir($dir_path) && is_readable($dir_path);
	}

	/**
	 * Is given path writable directory?
	 *
	 * @static
	 *
	 * @param string $dir_path
	 *
	 * @return bool
	 */
	public static function isWritable($dir_path){
		return is_dir($dir_path) && is_writable($dir_path);
	}

	
	/**
	 * Creates directory.
	 *
	 * @param string $dir_path
	 * @param bool $overwrite_if_exists (optional; default: false)
	 * @throws IO_Dir_Exception
	 */
	public static function create($dir_path, $overwrite_if_exists=false ){
		if(static::exists($dir_path)) {
			if($overwrite_if_exists) {
				static::remove($dir_path);
			} else {
				return;
			}
		}

		if( !mkdir($dir_path, self::getDefaultChmodMask(), true) ){
			$error = static::_getLastError();

			throw new IO_Dir_Exception(
				"Failed to create directory '{$dir_path}'. Error message: {$error["message"]}",
				IO_Dir_Exception::CODE_CREATE_FAILED
			);
		}

		chmod($dir_path, self::getDefaultChmodMask());
	}

	/**
	 * Remove directory recursively
	 *
	 * @param string $dir_path
	 *
	 * @throws IO_Dir_Exception
	 */
	public static function remove($dir_path){

		$e = null;
		$dh = opendir( $dir_path );

		if($dh === false){
			$error= static::_getLastError();
			throw new IO_Dir_Exception(
				"Failed to open directory '{$dir_path}'. Error message: {$error["message"]}",
				IO_Dir_Exception::CODE_REMOVE_FAILED
			);
		}

		while (($file = readdir($dh)) !== false) {
                        if($file == "." || $file == ".."){
            	                continue;
                        }

                        $fp = "{$dir_path}/{$file}";
			if(is_dir($fp)){
	                        self::remove($fp);
                        } elseif(is_file($fp)) {
	                        IO_File::delete($fp);
                        }
		}
                closedir($dh);

		if(!rmdir($dir_path)){
			$error= static::_getLastError();
			throw new IO_Dir_Exception(
				"Failed to remove directory '{$dir_path}'. Error message: {$error["message"]}",
				IO_Dir_Exception::CODE_REMOVE_FAILED
			);
		}
	}


	/**
	 * Copies directory from $source_path to $target_path
	 * 
	 * @param string $source_path
	 * @param string $target_path
	 * @param bool $overwrite_if_exists (optional, default: true)
	 * @throws IO_Dir_Exception
	 */
	public static function copy($source_path, $target_path, $overwrite_if_exists=true){
		
		if(!self::exists($source_path)){
			throw new IO_Dir_Exception(
				"Directory '{$source_path}' not found",
				IO_Dir_Exception::CODE_COPY_FAILED
			);
		}

		if(static::exists($target_path) && !$overwrite_if_exists ) {
			throw new IO_Dir_Exception(
				"Target directory '{$source_path}' already exists",
				IO_Dir_Exception::CODE_COPY_FAILED
			);
		}

		static::create( $target_path, $overwrite_if_exists );

		$dh = opendir($source_path);
		if($dh === false){
			$error = static::_getLastError();
			throw new IO_Dir_Exception(
				"Failed to open source directory '{$source_path}'. Error message: {$error["message"]}",
				IO_Dir_Exception::CODE_OPEN_FAILED
			);
		}

		while (($file = readdir($dh)) !== false) {
			if($file == "." || $file == ".."){
				continue;
			}
			
			$source = "{$source_path}/{$file}";
			$target = "{$target_path}/{$file}";

			if(is_file($source)){
				IO_File::copy($source, $target, $overwrite_if_exists);
			} else {
				static::copy($source, $target, $overwrite_if_exists);
			}
		}
		closedir($dh);
	}
	
	/**
	 * Moves/renames directory from $source_path to $target_path
	 * 
	 * @param string $source_path
	 * @param string $target_path
	 * @param bool $overwrite_if_exists (optional, default: true)
	 *
	 * @throws IO_Dir_Exception
	 */
	public static function rename($source_path, $target_path, $overwrite_if_exists=true){
		static::copy($source_path, $target_path, $overwrite_if_exists);
		static::remove($source_path);
	}

	/**
	 * Alias of IO_Dir::rename()
	 *
	 * @param string $source_path
	 * @param string $target_path
	 * @param bool $overwrite_if_exists (optional, default: true)
	 *
	 * @throws IO_Dir_Exception
	 */
	public static function move($source_path, $target_path, $overwrite_if_exists=true ){
		self::rename($source_path, $target_path, $overwrite_if_exists );
	}


	/**
	 * Gets files list from directory (not recursively)
	 *
	 * Returns:
	 * array(
	 *    "full path" => "file name"
	 * )
	 *
	 * @param string $dir_path
	 * @param string $mask (optional, default: "*", @see glob)
	 *
	 * @throws IO_Dir_Exception
	 * @return array
	 */
	public static function getFilesList($dir_path, $mask="*" ){
		return static::getList($dir_path, $mask, false, true );
	}

	/**
	 * Gets directories list from directory (not recursively)
	 *
	 * Returns:
	 * array(
	 *    "full path" => "dir name"
	 * )
	 *
	 * @param string $dir_path
	 * @param string $mask (optional, default: "*", @see glob)
	 *
	 * @throws IO_Dir_Exception
	 * @return array
	 */
	public static function getSubdirectoriesList($dir_path, $mask = "*" ){
		return static::getList($dir_path, $mask, true, false );
	}


	/**
	 * Gets directories and files  list from directory (not recursively)
	 *
	 * Returns:
	 * array(
	 *    "full path" => "file or dir name"
	 * )
	 *
	 * @param string $dir_path
	 * @param string $mask (optional, default: "*", @see glob)
	 * @param bool $get_dirs (optional, default: true)
	 * @param bool $get_files (optional, default: true)
	 *
	 * @throws IO_Dir_Exception
	 * @return array
	 */
	public static function getList($dir_path, $mask="*", $get_dirs=true, $get_files=true ){
		$last_char = substr($dir_path, -1);

		if($last_char!="/" && $last_char!="\\") {
			$dir_path .= DIRECTORY_SEPARATOR;
		}

		$pattern = $dir_path.$mask;

		if( $get_dirs && !$get_files ) {
			$options = GLOB_ERR|GLOB_ONLYDIR;
		} else {
			$options = GLOB_ERR;
		}

		$files = glob($pattern, $options );

		if($files === false){
			$error = static::_getLastError();
			throw new IO_Dir_Exception(
				"Failed to open source directory '{$dir_path}'. Error message: {$error["message"]}",
				IO_Dir_Exception::CODE_OPEN_FAILED
			);
		}


		$result = array();
		foreach( $files as $file_path ) {
			$file_name = basename($file_path);

			if($file_name === "." || $file_name === ".."){
				continue;
			}

			if(
				is_file($file_path) &&
				!$get_files
			){
				continue;
			}
			if(
				is_dir($file_path)
			){
				if(!$get_dirs) {
					continue;
				}

				$file_path .= DIRECTORY_SEPARATOR;
			}

			$result[$file_path] = $file_name;
		}

		asort($result);

		return $result;
	}

}