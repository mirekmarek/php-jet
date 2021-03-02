<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

require_once SysConf_Path::getLibrary() . 'Jet/Cache.php';
require_once SysConf_Path::getLibrary() . 'Jet/Cache/Files.php';
require_once SysConf_Path::getLibrary() . 'Jet/Mvc/Cache.php';
require_once SysConf_Path::getLibrary() . 'Jet/Mvc/Cache/Backend.php';

/**
 *
 */
class Mvc_Cache_Backend_Files extends Cache_Files implements Mvc_Cache_Backend
{

	const KEY_PREFIX = 'mvc_';

	/**
	 * @return bool
	 */
	public function isActive(): bool
	{
		return SysConf_Jet::isCacheMvcEnabled();
	}

	/**
	 *
	 */
	public function reset(): void
	{
		$this->resetHtmlFiles( static::KEY_PREFIX );
		$this->resetDataFiles( static::KEY_PREFIX );
	}

	/**
	 * @return array|null
	 */
	public function loadSiteMaps(): array|null
	{
		return $this->readData( static::KEY_PREFIX . 'site_maps' );
	}

	/**
	 * @param array $map
	 */
	public function saveSiteMaps( array $map ): void
	{
		$this->writeData( static::KEY_PREFIX . 'site_maps', $map );
	}


	/**
	 * @return array|null
	 */
	public function loadSitesFilesMap(): array|null
	{
		return $this->readData( static::KEY_PREFIX . 'sites_files_map' );
	}

	/**
	 * @param array $map
	 */
	public function saveSitesFilesMap( array $map ): void
	{
		$this->writeData( static::KEY_PREFIX . 'sites_files_map', $map );
	}

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 *
	 * @return array|null
	 */
	public function loadPageMaps( Mvc_Site_Interface $site, Locale $locale ): array|null
	{
		return $this->readData( static::KEY_PREFIX . 'pages_map_' . $site->getId() . '_' . $locale );
	}

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 *
	 * @param array $map
	 */
	public function savePageMaps( Mvc_Site_Interface $site, Locale $locale, array $map ): void
	{
		$this->writeData( static::KEY_PREFIX . 'pages_map_' . $site->getId() . '_' . $locale, $map );
	}

	/**
	 * @param Mvc_Page_Content_Interface $content
	 *
	 * @return string
	 */
	protected function getContentKey( Mvc_Page_Content_Interface $content ): string
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

		return static::KEY_PREFIX . $site_id . '_' . $locale . '_' . $page_id . '_' . md5( $module . $controller . $action . $position . $position_order );
	}

	/**
	 * @param Mvc_Page_Content_Interface $content
	 *
	 * @return string|null
	 */
	public function loadContentOutput( Mvc_Page_Content_Interface $content ): string|null
	{
		$key = $this->getContentKey( $content );

		return $this->readHtml( $key );
	}

	/**
	 * @param Mvc_Page_Content_Interface $content
	 * @param string $output
	 *
	 */
	public function saveContentOutput( Mvc_Page_Content_Interface $content, string $output ): void
	{
		$key = $this->getContentKey( $content );

		$this->writeHtml( $key, $output );
	}

}
