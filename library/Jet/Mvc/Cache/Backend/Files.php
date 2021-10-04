<?php /** @noinspection PhpIncludeInspection */

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
	public function loadBaseMaps(): array|null
	{
		return $this->readData( static::KEY_PREFIX . 'base_maps' );
	}

	/**
	 * @param array $map
	 */
	public function saveBaseMaps( array $map ): void
	{
		$this->writeData( static::KEY_PREFIX . 'base_maps', $map );
	}


	/**
	 * @return array|null
	 */
	public function loadBasesFilesMap(): array|null
	{
		return $this->readData( static::KEY_PREFIX . 'bases_files_map' );
	}

	/**
	 * @param array $map
	 */
	public function saveBasesFilesMap( array $map ): void
	{
		$this->writeData( static::KEY_PREFIX . 'bases_files_map', $map );
	}

	/**
	 * @param Mvc_Base_Interface $base
	 * @param Locale $locale
	 *
	 * @return array|null
	 */
	public function loadPageMaps( Mvc_Base_Interface $base, Locale $locale ): array|null
	{
		return $this->readData( static::KEY_PREFIX . 'pages_map_' . $base->getId() . '_' . $locale );
	}

	/**
	 * @param Mvc_Base_Interface $base
	 * @param Locale $locale
	 *
	 * @param array $map
	 */
	public function savePageMaps( Mvc_Base_Interface $base, Locale $locale, array $map ): void
	{
		$this->writeData( static::KEY_PREFIX . 'pages_map_' . $base->getId() . '_' . $locale, $map );
	}

	/**
	 * @param Mvc_Page_Content_Interface $content
	 *
	 * @return string
	 */
	protected function getContentKey( Mvc_Page_Content_Interface $content ): string
	{
		$page = $content->getPage();
		$base_id = $page->getBaseId();
		$locale = $page->getLocale();
		$page_id = $page->getId();

		$module = $content->getModuleName();
		$controller = $content->getControllerName();
		$action = $content->getControllerAction();

		$position = $content->getOutputPosition();
		$position_order = $content->getOutputPositionOrder();

		$cache_context = $page->getCacheContext();
		$cache_context = $cache_context? '_'.$cache_context : '';

		return static::KEY_PREFIX . $base_id . '_' . $locale . '_' . $page_id . '_' . md5( $module . $controller . $action . $position . $position_order ).$cache_context;
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
