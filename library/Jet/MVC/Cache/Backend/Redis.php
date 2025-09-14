<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

require_once SysConf_Path::getLibrary() . 'Jet/Cache.php';
require_once SysConf_Path::getLibrary() . 'Jet/Cache/Record/HTMLSnippet.php';
require_once SysConf_Path::getLibrary() . 'Jet/Cache/Record/Data.php';
require_once SysConf_Path::getLibrary() . 'Jet/Cache/Redis.php';
require_once SysConf_Path::getLibrary() . 'Jet/MVC/Cache.php';
require_once SysConf_Path::getLibrary() . 'Jet/MVC/Cache/Backend.php';

/**
 *
 */
class MVC_Cache_Backend_Redis extends Cache_Redis implements MVC_Cache_Backend
{
	public const KEY_PREFIX = 'mvc_';
	public const OUTPUT_KEY_PREFIX = 'mvc_output_';
	
	
	/**
	 * @return bool
	 */
	public function isActive(): bool
	{
		return SysConf_Jet_MVC::getCacheEnabled() && $this->is_active;
	}

	/**
	 *
	 */
	public function reset(): void
	{
		$this->deleteItems( static::KEY_PREFIX );
	}
	
	/**
	 *
	 */
	public function resetOutputCache(): void
	{
		$this->deleteItems( static::OUTPUT_KEY_PREFIX );
	}
	

	/**
	 * @return array<string,array<string,string|string[]>>|null
	 */
	public function loadBaseMaps(): array|null
	{
		return $this->readData( static::KEY_PREFIX.'base_maps' )?->getData();
	}

	/**
	 * @param array<string,array<string,string|string[]>> $map
	 */
	public function saveBaseMaps( array $map ): void
	{
		$this->writeData( static::KEY_PREFIX.'base_maps', $map );
	}

	/**
	 * @param MVC_Base_Interface $base
	 * @param Locale $locale
	 *
	 * @return array<string,array<string,string|string[]>>|null
	 */
	public function loadPageMaps( MVC_Base_Interface $base, Locale $locale ): array|null
	{
		return $this->readData( static::KEY_PREFIX.'pages_map_' . $base->getId() . '_' . $locale )?->getData();
	}

	/**
	 * @param MVC_Base_Interface $base
	 * @param Locale $locale
	 *
	 * @param array<string,array<string,string|string[]>> $map
	 */
	public function savePageMaps( MVC_Base_Interface $base, Locale $locale, array $map ): void
	{
		$this->writeData( static::KEY_PREFIX.'pages_map_' . $base->getId() . '_' . $locale, $map );
	}

	/**
	 * @param MVC_Page_Content_Interface $content
	 *
	 * @return string
	 */
	protected function getContentKey( MVC_Page_Content_Interface $content ): string
	{
		if(!$this->isActive()) {
			return '';
		}
		
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

		return static::OUTPUT_KEY_PREFIX.$base_id . '_' . $locale . '_' . $page_id . '_' . md5( $module . $controller . $action . $position . $position_order ).$cache_context;
	}

	/**
	 * @param MVC_Page_Content_Interface $content
	 *
	 * @return ?Cache_Record_HTMLSnippet
	 */
	public function loadContentOutput( MVC_Page_Content_Interface $content ): ?Cache_Record_HTMLSnippet
	{
		return $this->readHtml( $this->getContentKey( $content ) );
	}

	/**
	 * @param MVC_Page_Content_Interface $content
	 * @param string $output
	 *
	 */
	public function saveContentOutput( MVC_Page_Content_Interface $content, string $output ): void
	{
		$this->writeHtml( $this->getContentKey( $content ), $output );
	}
	
	public function loadCustomOutput( string $key ): ?Cache_Record_HTMLSnippet
	{
		return $this->readHtml( $key );
	}
	
	public function saveCustomOutput( string $key, string $output ): void
	{
		$this->writeHtml( $key, $output );
	}
}
