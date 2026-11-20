<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

require_once 'Abstract.php';

/**
 *
 */
abstract class Cache_Files extends Cache_Abstract
{
	
	/**
	 * @return bool
	 */
	abstract public function isActive(): bool;
	
	
	/**
	 * @param string $entity
	 * @return string
	 */
	protected function getDataFilePath( string $entity ): string
	{
		return SysConf_Path::getCache() . $entity . '.php';
	}
	
	
	/**
	 * @param string $key
	 * @return ?Cache_Record_Data
	 */
	protected function readData( string $key ): ?Cache_Record_Data
	{
		if( !$this->isActive() ) {
			return null;
		}
		
		$file_path = $this->getDataFilePath( $key );
		
		set_error_handler( static function( int $errno, string $errstr ): bool {
			return str_contains( $errstr, 'No such file or directory' )
				|| str_contains( $errstr, 'Failed to open stream' )
				|| str_contains( $errstr, 'stat failed' );
		}, E_WARNING );
		
		try {
			$data = require $file_path;
			
			$timestamp = filemtime( $file_path );
			if( $timestamp === false ) {
				return null;
			}
		} catch( \Error ) {
			return null;
		} finally {
			restore_error_handler();
		}
		
		return new Cache_Record_Data( $key, $data, $timestamp );
	}
	
	/**
	 * @param string $key
	 * @param mixed $data
	 */
	protected function writeData( string $key, mixed $data ): void
	{
		if( !$this->isActive() ) {
			return;
		}
		
		$file_path = $this->getDataFilePath( $key );
		
		IO_File::writeDataAsPhp(
			$file_path,
			$data
		);
	}
	
	
	/**
	 * @param string $key
	 * @return string
	 */
	protected function getHtmlFilePath( string $key ): string
	{
		return SysConf_Path::getCache() . $key . '.html';
	}
	
	
	/**
	 * @param string $key
	 * @return ?Cache_Record_HTMLSnippet
	 */
	protected function readHtml( string $key ): ?Cache_Record_HTMLSnippet
	{
		if( !$this->isActive() ) {
			return null;
		}
		
		$file_path = $this->getHtmlFilePath( $key );
		
		set_error_handler( static function( int $errno, string $errstr ): bool {
			return str_contains( $errstr, 'No such file or directory' )
				|| str_contains( $errstr, 'stat failed' );
		}, E_WARNING );
		
		try {
			$html = file_get_contents( $file_path );
			if( $html === false ) {
				return null;
			}
			
			$timestamp = filemtime( $file_path );
			if( $timestamp === false ) {
				return null;
			}
		} finally {
			restore_error_handler();
		}
		
		return new Cache_Record_HTMLSnippet(
			key: $key,
			html: $html,
			timestamp: $timestamp
		);
	}
	
	/**
	 * @param string $key
	 * @param string $html
	 */
	protected function writeHtml( string $key, string $html ): void
	{
		if( !$this->isActive() ) {
			return;
		}
		
		$file_path = $this->getHtmlFilePath( $key );
		$tmp_path = $file_path . '.' . getmypid() . '_' . hrtime( true ) . '.tmp';
		
		if( file_put_contents( $tmp_path, $html ) !== false ) {
			chmod( $tmp_path, SysConf_Jet_IO::getFileMod() );
			rename( $tmp_path, $file_path );
		}
	}
	
	/**
	 * @param string $prefix
	 */
	public function resetDataFiles( string $prefix ): void
	{
		$files = IO_Dir::getFilesList( SysConf_Path::getCache(), $prefix . '*.php' );
		
		$this->safeDelete( array_keys($files) );
		
		Cache::resetOPCache();
	}
	
	/**
	 * @param string $key
	 */
	public function resetDataFile( string $key ): void
	{
		$file_path = $this->getDataFilePath( $key );
		
		$this->safeDelete( $file_path );
		
		Cache::resetOPCache();
	}
	
	
	/**
	 * @param string $prefix
	 */
	public function resetHtmlFiles( string $prefix ): void
	{
		$files = IO_Dir::getFilesList( SysConf_Path::getCache(), $prefix . '*.html' );
		
		$this->safeDelete( array_keys($files) );
	}
	
	/**
	 * @param string $key
	 */
	public function resetHtmlFile( string $key ): void
	{
		$file_path = $this->getHtmlFilePath( $key );
		
		$this->safeDelete( $file_path );
	}
	
	/**
	 * @param string|array<string> $files
	 * @return void
	 */
	protected function safeDelete( string|array $files ) : void
	{
		set_error_handler( static function( int $errno, string $errstr ): bool {
			return str_contains( $errstr, 'No such file or directory' );
		}, E_WARNING );
		
		try {
			if(is_array($files)) {
				foreach( $files as $file_path ) {
					unlink( $file_path );
				}
			} else {
				unlink( $files );
			}
		} finally {
			restore_error_handler();
		}
		
	}
}
