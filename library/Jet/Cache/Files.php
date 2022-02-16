<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
abstract class Cache_Files
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
	 * @param string $entity
	 * @return array|null
	 */
	protected function readData( string $entity ): array|null
	{
		if( !$this->isActive() ) {
			return null;
		}

		$file_path = $this->getDataFilePath( $entity );

		if(
			!is_file( $file_path ) ||
			!is_readable( $file_path )
		) {
			return null;
		}

		return require $file_path;
	}

	/**
	 * @param string $entity
	 * @param array $data
	 */
	protected function writeData( string $entity, array $data ): void
	{
		if( !$this->isActive() ) {
			return;
		}

		$file_path = $this->getDataFilePath( $entity );

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
	 * @return string|null
	 */
	protected function readHtml( string $key ): string|null
	{
		if( !$this->isActive() ) {
			return null;
		}

		$file_path = $this->getHtmlFilePath( $key );

		if(
			!is_file( $file_path ) ||
			!is_readable( $file_path )
		) {
			return null;
		}

		return file_get_contents( $file_path );
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

		file_put_contents(
			$file_path,
			$html,
			LOCK_EX
		);

		chmod( $file_path, SysConf_Jet_IO::getFileMod() );
	}

	/**
	 * @param string $prefix
	 */
	public function resetDataFiles( string $prefix ): void
	{
		$files = IO_Dir::getFilesList( SysConf_Path::getCache(), $prefix . '*.php' );

		foreach( $files as $file_path => $file_name ) {
			IO_File::delete( $file_path );
		}

		Cache::resetOPCache();
	}

	/**
	 * @param string $key
	 */
	public function resetDataFile( string $key ): void
	{
		$file_path = $this->getDataFilePath( $key );

		if( file_exists( $file_path ) ) {
			unlink( $file_path );
		}

		Cache::resetOPCache();
	}


	/**
	 * @param string $prefix
	 */
	public function resetHtmlFiles( string $prefix ): void
	{
		$files = IO_Dir::getFilesList( SysConf_Path::getCache(), $prefix . '*.html' );

		foreach( $files as $file_path => $file_name ) {
			IO_File::delete( $file_path );
		}
	}

	/**
	 * @param string $key
	 */
	public function resetHtmlFile( string $key ): void
	{
		$file_path = $this->getHtmlFilePath( $key );

		if( file_exists( $file_path ) ) {
			unlink( $file_path );
		}

	}
}
