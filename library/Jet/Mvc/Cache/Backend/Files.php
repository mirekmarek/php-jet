<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

require_once SysConf_PATH::LIBRARY().'Jet/Cache.php';
require_once SysConf_PATH::LIBRARY().'Jet/Mvc/Cache.php';
require_once SysConf_PATH::LIBRARY().'Jet/Mvc/Cache/Backend.php';

/**
 *
 */
class Mvc_Cache_Backend_Files implements Mvc_Cache_Backend {

	/**
	 * @param string $entity
	 * @return string
	 */
	protected function getPath( string $entity ) : string
	{
		return SysConf_PATH::CACHE().'mvc_'.$entity.'.php';
	}


	/**
	 * @return bool
	 */
	public function isActive() : bool
	{
		return SysConf_Cache::isMvcEnabled();
	}

	/**
	 * @param string $entity
	 * @return array|null
	 */
	protected function read( string $entity ) : array|null
	{
		if(!SysConf_Cache::isMvcEnabled()) {
			return null;
		}

		$file_path = $this->getPath( $entity );

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
	protected function write( string $entity, array $data ) : void
	{
		if(!SysConf_Cache::isMvcEnabled()) {
			return;
		}

		$file_path = $this->getPath($entity);

		file_put_contents(
			$file_path,
			'<?php return '.var_export( $data, true ).';'
		);

		chmod( $file_path, SysConf_Jet::IO_CHMOD_MASK_FILE());

		Cache::resetOPCache();
	}


	/**
	 *
	 */
	public function reset(): void
	{
		$files = IO_Dir::getFilesList(SysConf_PATH::CACHE(), 'mvc_*.php');

		foreach($files as $file_path=>$file_name) {
			IO_File::delete($file_path);
		}

		Cache::resetOPCache();
	}

	/**
	 * @return array|null
	 */
	public function loadSiteMaps(): array|null
	{
		return $this->read('site_maps');
	}

	/**
	 * @param array $map
	 */
	public function saveSiteMaps( array $map ): void
	{
		$this->write( 'site_maps', $map );
	}


	/**
	 * @return array|null
	 */
	public function loadSitesFilesMap(): array|null
	{
		return $this->read('sites_files_map');
	}

	/**
	 * @param array $map
	 */
	public function saveSitesFilesMap( array $map ): void
	{
		$this->write( 'sites_files_map', $map );
	}

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 *
	 * @return array|null
	 */
	public function loadPageMaps( Mvc_Site_Interface $site, Locale $locale ): array|null
	{
		return $this->read('pages_map_'.$site->getId().'_'.$locale);
	}

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 *
	 * @param array $map
	 */
	public function savePageMaps(  Mvc_Site_Interface $site, Locale $locale, array $map ): void
	{
		$this->write( 'pages_map_'.$site->getId().'_'.$locale, $map );
	}


}

