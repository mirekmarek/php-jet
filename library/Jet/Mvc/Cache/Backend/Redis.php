<?php /** @noinspection PhpIncludeInspection */
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

require_once SysConf_Path::getLibrary() . 'Jet/Cache.php';
require_once SysConf_Path::getLibrary() . 'Jet/Cache/Redis.php';
require_once SysConf_Path::getLibrary() . 'Jet/Mvc/Cache.php';
require_once SysConf_Path::getLibrary() . 'Jet/Mvc/Cache/Backend.php';

/**
 *
 */
class Mvc_Cache_Backend_Redis implements Mvc_Cache_Backend
{

	/**
	 * @var Cache_Redis|null
	 */
	protected ?Cache_Redis $redis = null;

	/**
	 * @param string $host
	 * @param int $port
	 */
	public function __construct( string $host = '127.0.0.1', int $port = 6379 )
	{
		$this->redis = new Cache_Redis( $host, $port );
	}

	/**
	 * @return bool
	 */
	public function isActive(): bool
	{
		return SysConf_Jet_Mvc::getCacheEnabled() && $this->redis->isActive();
	}


	/**
	 * @param string $entity
	 * @return array|null
	 */
	protected function readMap( string $entity ): array|null
	{
		if( !$this->isActive() ) {
			return null;
		}

		return $this->redis->get( 'mvc_' . $entity );
	}

	/**
	 * @param string $entity
	 * @param array $data
	 */
	protected function writeMap( string $entity, array $data ): void
	{
		if( !$this->isActive() ) {
			return;
		}

		$this->redis->set( 'mvc_' . $entity, $data );
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

		return $this->redis->get( 'mvc_' . $key . '.html' );
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

		$this->redis->set( 'mvc_' . $key . '.html', $html );
	}


	/**
	 *
	 */
	public function reset(): void
	{
		$this->redis->deleteItems( 'mvc_' );
	}

	/**
	 * @return array|null
	 */
	public function loadBaseMaps(): array|null
	{
		return $this->readMap( 'base_maps' );
	}

	/**
	 * @param array $map
	 */
	public function saveBaseMaps( array $map ): void
	{
		$this->writeMap( 'base_maps', $map );
	}


	/**
	 * @return array|null
	 */
	public function loadBasesFilesMap(): array|null
	{
		return $this->readMap( 'bases_files_map' );
	}

	/**
	 * @param array $map
	 */
	public function saveBasesFilesMap( array $map ): void
	{
		$this->writeMap( 'bases_files_map', $map );
	}

	/**
	 * @param Mvc_Base_Interface $base
	 * @param Locale $locale
	 *
	 * @return array|null
	 */
	public function loadPageMaps( Mvc_Base_Interface $base, Locale $locale ): array|null
	{
		return $this->readMap( 'pages_map_' . $base->getId() . '_' . $locale );
	}

	/**
	 * @param Mvc_Base_Interface $base
	 * @param Locale $locale
	 *
	 * @param array $map
	 */
	public function savePageMaps( Mvc_Base_Interface $base, Locale $locale, array $map ): void
	{
		$this->writeMap( 'pages_map_' . $base->getId() . '_' . $locale, $map );
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

		return $base_id . '_' . $locale . '_' . $page_id . '_' . md5( $module . $controller . $action . $position . $position_order ).$cache_context;
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
