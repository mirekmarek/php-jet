<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

require_once SysConf_Path::getLibrary().'Jet/Cache.php';
require_once SysConf_Path::getLibrary().'Jet/Mvc/Cache.php';
require_once SysConf_Path::getLibrary().'Jet/Mvc/Cache/Backend.php';

/**
 *
 */
class Mvc_Cache_Backend_Files implements Mvc_Cache_Backend {

	/**
	 * @return bool
	 */
	public function isActive() : bool
	{
		return SysConf_Jet::isCacheMvcEnabled();
	}

	/**
	 * @param string $entity
	 * @return string
	 */
	protected function getMapPath( string $entity ) : string
	{
		return SysConf_Path::getCache().'mvc_'.$entity.'.php';
	}


	/**
	 * @param string $entity
	 * @return array|null
	 */
	protected function readMap( string $entity ) : array|null
	{
		if(!SysConf_Jet::isCacheMvcEnabled()) {
			return null;
		}

		$file_path = $this->getMapPath( $entity );

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
	protected function writeMap( string $entity, array $data ) : void
	{
		if(!SysConf_Jet::isCacheMvcEnabled()) {
			return;
		}

		$file_path = $this->getMapPath($entity);

		file_put_contents(
			$file_path,
			'<?php return '.var_export( $data, true ).';',
			LOCK_EX
		);

		chmod( $file_path, SysConf_Jet::getIOModFile());

		Cache::resetOPCache();
	}



	/**
	 * @param string $key
	 * @return string
	 */
	protected function getHtmlPath( string $key ) : string
	{
		return SysConf_Path::getCache().'mvc_'.$key.'.html';
	}


	/**
	 * @param string $key
	 * @return string|null
	 */
	protected function readHtml( string $key ) : string|null
	{
		if(!SysConf_Jet::isCacheMvcEnabled()) {
			return null;
		}

		$file_path = $this->getHtmlPath( $key );

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
	protected function writeHtml( string $key, string $html ) : void
	{
		if(!SysConf_Jet::isCacheMvcEnabled()) {
			return;
		}

		$file_path = $this->getHtmlPath($key);

		file_put_contents(
			$file_path,
			$html,
			LOCK_EX
		);

		chmod( $file_path, SysConf_Jet::getIOModFile());
	}



	/**
	 *
	 */
	public function reset(): void
	{
		$files = IO_Dir::getFilesList(SysConf_Path::getCache(), 'mvc_*.php');

		foreach($files as $file_path=>$file_name) {
			IO_File::delete($file_path);
		}

		$files = IO_Dir::getFilesList(SysConf_Path::getCache(), 'mvc_*.html');

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
		return $this->readMap('site_maps');
	}

	/**
	 * @param array $map
	 */
	public function saveSiteMaps( array $map ): void
	{
		$this->writeMap( 'site_maps', $map );
	}


	/**
	 * @return array|null
	 */
	public function loadSitesFilesMap(): array|null
	{
		return $this->readMap('sites_files_map');
	}

	/**
	 * @param array $map
	 */
	public function saveSitesFilesMap( array $map ): void
	{
		$this->writeMap( 'sites_files_map', $map );
	}

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 *
	 * @return array|null
	 */
	public function loadPageMaps( Mvc_Site_Interface $site, Locale $locale ): array|null
	{
		return $this->readMap('pages_map_'.$site->getId().'_'.$locale);
	}

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 *
	 * @param array $map
	 */
	public function savePageMaps(  Mvc_Site_Interface $site, Locale $locale, array $map ): void
	{
		$this->writeMap( 'pages_map_'.$site->getId().'_'.$locale, $map );
	}

	/**
	 * @param Mvc_Page_Content_Interface $content
	 *
	 * @return string
	 */
	protected function getContentKey( Mvc_Page_Content_Interface $content ) : string
	{
		$page = $content->getPage();
		$site_id = $page->getSiteId();
		$locale = $page->getLocale();
		$page_id = $page->getId();

		$module = $content->getModuleName();
		$controller = $content->getControllerName();
		$action = $content->getControllerAction();

		$position = $content->getOutputPosition();
		$position_order = $content->getOutputPositionOrder();

		return $site_id.'_'.$locale.'_'.$page_id.'_'.md5($module.$controller.$action.$position.$position_order);
	}

	/**
	 * @param Mvc_Page_Content_Interface $content
	 *
	 * @return string|null
	 */
	public function loadContentOutput( Mvc_Page_Content_Interface $content ) : string|null
	{
		$key = $this->getContentKey( $content );

		return $this->readHtml( $key );
	}

	/**
	 * @param Mvc_Page_Content_Interface $content
	 * @param string $output
	 *
	 */
	public function saveContentOutput( Mvc_Page_Content_Interface $content, string $output ) : void
	{
		$key = $this->getContentKey( $content );

		$this->writeHtml( $key, $output );
	}

}
