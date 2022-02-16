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
abstract class PackageCreator extends BaseObject
{

	/**
	 * @var array
	 */
	protected array $URIs = [];

	/**
	 * @var string|null
	 */
	protected string|null $key = null;

	/**
	 * @return string
	 */
	abstract public function getPackagePath(): string;

	/**
	 * @return string
	 */
	abstract public function getPackageURI(): string;


	/**
	 *
	 */
	public function generate(): void
	{
		$package_path = $this->getPackagePath();

		if( !IO_File::exists( $package_path ) ) {

			IO_File::write(
				$package_path,
				$this->createPackage()
			);
		}
	}

	/**
	 * @return string
	 */
	abstract protected function createPackage(): string;


	/**
	 * @param string $URI
	 *
	 * @return string|null
	 */
	protected function getFileContent( string $URI ): string|null
	{

		$_URI = $this->normalizePath( $URI );

		return IO_File::read( $_URI );
	}

	/**
	 * @param string $URI
	 *
	 * @return string
	 */
	protected function normalizePath( string $URI ): string
	{

		$o_URI = $URI;

		if( str_contains( $URI, '?' ) ) {
			$URI = strstr( $URI, '?', true );
		}

		$public_uri_str_len = strlen( SysConf_URI::getCss() );
		if( substr( $URI, 0, $public_uri_str_len ) == SysConf_URI::getCss() ) {
			return SysConf_Path::getCss() . substr( $URI, $public_uri_str_len );
		}

		$public_uri_str_len = strlen( SysConf_URI::getJs() );
		if( substr( $URI, 0, $public_uri_str_len ) == SysConf_URI::getJs() ) {
			return SysConf_Path::getJs() . substr( $URI, $public_uri_str_len );
		}


		if( str_starts_with( $o_URI, '//' ) ) {
			return 'http:' . $o_URI;
		}

		return $o_URI;
	}


	/**
	 * @param string $URI
	 *
	 * @return string
	 */
	protected function normalizeURI( string $URI ): string
	{
		return $URI;
	}

	/**
	 *
	 * @return string
	 */
	protected function getKey(): string
	{
		if( !$this->key ) {
			$this->key = md5( implode( '', $this->URIs ) );
		}

		return $this->key;
	}
}