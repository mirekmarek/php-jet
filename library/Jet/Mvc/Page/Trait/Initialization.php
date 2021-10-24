<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
trait Mvc_Page_Trait_Initialization
{
	/**
	 * @var Mvc_Page[]
	 */
	protected static array $pages = [];

	/**
	 * @var array
	 */
	protected static array $maps = [];

	/**
	 * @param Mvc_Base_Interface $base
	 * @param Locale $locale
	 *
	 * @return array
	 */
	public static function getRelativePathMap( Mvc_Base_Interface $base, Locale $locale ): array
	{
		$base_id = $base->getId();

		$key = $base_id . ':' . $locale;

		static::loadMaps( $base, $locale );

		return static::$maps[$key]['relative_path_map'];
	}

	/**
	 * @param Mvc_Base_Interface $base
	 * @param Locale $locale
	 *
	 * @return array
	 */
	public static function loadMaps( Mvc_Base_Interface $base, Locale $locale ): array
	{
		$base_id = $base->getId();

		$key = $base_id . ':' . $locale;

		if( !isset( static::$maps[$key] ) ) {
			$map = Mvc_Cache::loadPageMaps( $base, $locale );
			if( $map ) {
				static::$maps[$key] = $map;
			} else {
				static::$maps[$key] = [
					'pages_files_map'   => [],
					'relative_path_map' => [],
					'children_map'      => [],
					'parent_map'        => [],
					'parents_map'       => [],
				];

				static::loadMaps_readDir( $key, $base->getPagesDataPath( $locale ) );
				static::loadMaps_modules( $base, $locale );

				Mvc_Cache::savePageMaps( $base, $locale, static::$maps[$key] );
			}
		}

		return static::$maps[$key];
	}

	/**
	 * @param Mvc_Base_Interface $base
	 * @param Locale $locale
	 */
	protected static function loadMaps_modules( Mvc_Base_Interface $base, Locale $locale ): void
	{
		if( !SysConf_Jet_Mvc::getUseModulePages() ) {
			return;
		}

		$base_id = $base->getId();
		$locale_str = $locale->toString();
		$key = $base_id . ':' . $locale_str;

		Debug_Profiler::blockStart( 'Loading module pages' );
		Debug_Profiler::message( 'base: ' . $base_id . ' locale: ' . $locale_str );

		foreach( Application_Modules::activatedModulesList() as $manifest ) {

			$pages = $manifest->getPages( $base, $locale );

			foreach( $pages as $page ) {
				$page_id = $page->getId();

				static::$maps[$key]['relative_path_map'][$page->getRelativePath() . '/'] = $page_id;
				static::$maps[$key]['children_map'][$page_id] = [];
				static::$maps[$key]['pages_files_map'][$page_id] = '@' . $manifest->getName();
				static::$maps[$key]['parent_map'][$page_id] = Mvc_Page::HOMEPAGE_ID;
				static::$maps[$key]['parents_map'][$page_id] = [Mvc_Page::HOMEPAGE_ID];

			}
		}
		Debug_Profiler::blockEnd( 'Loading module pages' );

	}


	/**
	 * @param string $key
	 * @param string $source_dir_path
	 * @param string $parent_page_id
	 * @param string $parent_path
	 * @param array $parents
	 *
	 * @throws Mvc_Page_Exception
	 */
	protected static function loadMaps_readDir( string $key, string $source_dir_path, string $parent_page_id = '', string $parent_path = '', array $parents = [] ): void
	{

		$page_data_file_path = $source_dir_path . SysConf_Jet_Mvc::getPageDataFileName();


		$page_id = static::loadMaps_getPageId( $page_data_file_path );
		if( !$parent_page_id ) {
			$page_id = Mvc_Page::HOMEPAGE_ID;
		}


		if( isset( static::$maps[$key]['pages_files_map'][$page_id] ) ) {
			throw new Mvc_Page_Exception(
				'Duplicate page: \'' . $key . ':' . $page_id . '\', page data file: ' . $page_data_file_path,
				Mvc_Page_Exception::CODE_DUPLICATES_PAGE_ID
			);
		}

		static::$maps[$key]['pages_files_map'][$page_id] = $page_data_file_path;

		if( $parent_page_id ) {
			$relative_path = $parent_path . rawurlencode( basename( $source_dir_path ) ) . '/';
			static::$maps[$key]['children_map'][$parent_page_id][] = $page_id;
		} else {
			$relative_path = '';
		}

		static::$maps[$key]['relative_path_map'][$relative_path] = $page_id;
		static::$maps[$key]['children_map'][$page_id] = [];
		static::$maps[$key]['parent_map'][$page_id] = $parent_page_id;
		static::$maps[$key]['parents_map'][$page_id] = $parents;


		$parents[] = $page_id;
		$list = IO_Dir::getList( $source_dir_path, '*', true, false );
		foreach( $list as $dir_path => $dir_name ) {
			static::loadMaps_readDir( $key, $dir_path, $page_id, $relative_path, $parents );
		}

	}

	/**
	 * @param string $data_file_path
	 *
	 * @param array|null $parent_page_data
	 *
	 * @param string $dir_name
	 *
	 * @return string
	 */
	protected static function loadMaps_getPageId( string $data_file_path, array $parent_page_data = null, string $dir_name = '' ): string
	{

		if( !IO_File::isReadable( $data_file_path ) ) {
			throw new Mvc_Page_Exception(
				'Page data file is not readable: ' . $data_file_path,
				Mvc_Page_Exception::CODE_UNABLE_TO_READ_PAGE_DATA
			);
		}

		$data = require $data_file_path;

		return $data['id'] ?? '';
	}



	/**
	 * @param Mvc_Base_Interface $base
	 * @param Locale $locale
	 * @param array $data
	 *
	 * @return static
	 */
	public static function createByData( Mvc_Base_Interface $base, Locale $locale, array $data ): static
	{
		/**
		 * @var Mvc_Page $page
		 */
		$page = Mvc_Factory::getPageInstance();

		$page->setBase( $base );
		$page->setLocale( $locale );
		$page->setId( $data['id'] );

		unset( $data['id'] );

		$page->setData( $data );

		$page->original_relative_path_fragment = $page->relative_path_fragment;

		return $page;
	}

	/**
	 * @param array $data
	 */
	protected function setData( array $data ): void
	{
		/**
		 * @var Mvc_Page $this
		 */

		if( isset( $data['meta_tags'] ) ) {

			foreach( $data['meta_tags'] as $i => $m_dat ) {
				$this->meta_tags[] = Mvc_Page_MetaTag::createByData( $this, $m_dat );
			}

			unset( $data['meta_tags'] );
		}

		if( isset( $data['contents'] ) ) {

			foreach( $data['contents'] as $i => $c_dat ) {
				$this->content[] = Mvc_Page_Content::createByData( $this, $c_dat );
			}

			unset( $data['contents'] );
		}

		if( !isset( $data['relative_path'] ) ) {
			/**
			 * @var Mvc_Page $parent
			 */
			$parent = $this->getParent();
			$parent_path = $parent ? $parent->getRelativePath() : '';

			if( !$parent_path ) {
				$data['relative_path'] = $data['relative_path_fragment'];
			} else {
				$data['relative_path'] = $parent_path . '/' . $data['relative_path_fragment'];
			}
		}


		foreach( $data as $key => $var ) {
			$this->{$key} = $var;
		}

		if( !$this->name ) {
			$this->name = $this->title;
		}

		if( !$this->menu_title ) {
			$this->menu_title = $this->title;
		}

		if( !$this->breadcrumb_title ) {
			$this->breadcrumb_title = $this->title;
		}

	}

	/**
	 *
	 */
	public function __wakeup()
	{
		foreach( $this->content as $cnt ) {
			$cnt->setPage( $this );
		}

		foreach( $this->meta_tags as $mt ) {
			$mt->setPage( $this );
		}
	}

}