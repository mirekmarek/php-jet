<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
trait Mvc_Page_Trait_Initialization
{
	/**
	 * @var string
	 */
	protected static $page_data_file_name = 'page_data.php';

	/**
	 * @var string
	 */
	protected $data_file_path = '';

	/**
	 * @var Mvc_Page_Interface[][][]
	 */
	protected static $pages = [];

	/**
	 * @var Mvc_Page_Interface[]
	 */
	protected static $path_map = [];

	/**
	 * @return string
	 */
	public static function getPageDataFileName()
	{
		return static::$page_data_file_name;
	}

	/**
	 * @param string $page_data_file_name
	 */
	public static function setPageDataFileName( $page_data_file_name )
	{
		static::$page_data_file_name = $page_data_file_name;
	}


	/**
	 * @param Mvc_Page_Interface|Mvc_Page $page
	 *
	 * @throws Mvc_Page_Exception
	 */
	public static function appendPage( Mvc_Page_Interface $page )
	{

		$site_id = $page->getSite()->getId();
		$locale = (string)$page->getLocale();
		$page_id = $page->getId();

		if( isset( static::$pages[$site_id][$locale][$page_id] ) ) {
			throw new Mvc_Page_Exception(
				'Duplicate page: \''.$page->getKey().'\' ',
				Mvc_Page_Exception::CODE_DUPLICATES_PAGE_ID
			);
		}

		static::$pages[$site_id][$locale][$page_id] = $page;

		static::$path_map[$site_id][$locale][$page->getRelativePath()] = $page;


	}

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale             $locale
	 *
	 * @return array
	 */
	public static function loadPagesData( Mvc_Site_Interface $site, Locale $locale )
	{
		$site_id = $site->getId();
		$locale_str = $locale->toString();

		Debug_Profiler::blockStart('Load pages data');
		Debug_Profiler::message('site: '.$site_id.' locale: '.$locale_str);

		$pages = [];
		static::_loadPagesData_readDir( $pages, $site_id, $locale_str, $site->getPagesDataPath( $locale ) );

		Debug_Profiler::blockEnd('Load pages data');

		return $pages;

	}


	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale             $locale
	 *
	 * @return Mvc_Page_Interface[]
	 */
	public static function loadPages( Mvc_Site_Interface $site, Locale $locale )
	{
		$site_id = $site->getId();
		$locale_str = $locale->toString();

		if(
			!array_key_exists( $site_id, static::$pages ) ||
			!array_key_exists( $locale_str, static::$pages[$site_id] )
		) {

			if( static::getCacheLoadEnabled() ) {

				$loader = static::$cache_loader;
				/**
				 * @var Mvc_Page_Interface[] $pages
				 */
				$pages = $loader($site_id, $locale_str);
				if($pages) {
					Debug_Profiler::message('cache hit');
					static::$pages[$site_id][$locale_str] = $pages;

					static::$path_map[$site_id][$locale_str] = [];

					foreach( $pages as $page ) {
						static::$path_map[$site_id][$locale_str][$page->getRelativePath()] = $page;
					}

					return static::$path_map[$site_id][$locale_str];
				}
			}


			$data = static::loadPagesData( $site, $locale );

			Debug_Profiler::blockStart('Create page instances');
			Debug_Profiler::message('site: '.$site_id.' locale: '.$locale_str);

			foreach( $data as $id=>$page_data ) {
				$page = static::createByData( $site, $locale, $page_data );
				static::appendPage( $page );
			}

			static::loadModulePages( $site, $locale );


			if( static::getCacheSaveEnabled() ) {

				$saver = static::$cache_saver;
				$saver( $site_id, $locale_str, static::$pages[$site_id][$locale_str] );
			}
			
			Debug_Profiler::blockEnd('Create page instances');

		}



		return static::$pages[$site_id][$locale_str];
	}

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 */
	public static function loadModulePages( Mvc_Site_Interface $site, Locale $locale )
	{
		$site_id = $site->getId();
		$locale_str = $locale->toString();

		Debug_Profiler::blockStart('Loading module pages');
		Debug_Profiler::message('site: '.$site_id.' locale: '.$locale_str);

		foreach( Application_Modules::activatedModulesList() as $manifest ) {

			$pages = $manifest->getPages(
				$site,
				$locale
			);

			foreach( $pages as $page ) {
				$page->setParent( static::$pages[$site_id][$locale_str][static::HOMEPAGE_ID] );

				static::appendPage( $page );
			}

		}
		Debug_Profiler::blockEnd('Loading module pages');

	}

	/**
	 * @param array  $pages
	 * @param string $site_id
	 * @param string $locale_str
	 * @param string $source_dir_path
	 * @param array|null  $parent_page_data (optional)
	 *
	 * @throws Mvc_Page_Exception
	 */
	protected static function _loadPagesData_readDir( array &$pages, $site_id, $locale_str, $source_dir_path, array $parent_page_data = null )
	{
		/**
		 * @var Mvc_Page|Mvc_Page_Trait_Auth $this
		 * @var Mvc_Page static
		 */

		$list = IO_Dir::getList( $source_dir_path, '*', true, false );

		if( !$parent_page_data ) {
			$page_data_file_path = $source_dir_path.static::$page_data_file_name;

			$page_data = static::_loadPagesData_readPageDataFile( $page_data_file_path );
			if(!$page_data) {
				throw new Mvc_Page_Exception(
					'Page data file is not readable: '.$page_data_file_path,
					Mvc_Page_Exception::CODE_UNABLE_TO_READ_PAGE_DATA
				);

			}
			$page_id = $page_data['id'];


			if( isset( $pages[$page_id] ) ) {
				throw new Mvc_Page_Exception(
					'Duplicate page: \''.$site_id.':'.$locale_str.':'.$page_id.'\', page data file: '.$page_data_file_path,
					Mvc_Page_Exception::CODE_DUPLICATES_PAGE_ID
				);
			}


			$pages[$page_id] = $page_data;

			$parent_page_data = $page_data;

		}


		foreach( $list as $dir_path => $dir_name ) {
			$page_data_file_path = $dir_path.static::$page_data_file_name;

			$page_data = static::_loadPagesData_readPageDataFile( $page_data_file_path, $parent_page_data, $dir_name );
			if(!$page_data) {
				continue;
			}

			$page_id = $page_data['id'];

			if( isset( $pages[$page_id] ) ) {
				throw new Mvc_Page_Exception(
					'Duplicate page: \''.$site_id.':'.$locale_str.':'.$page_id.'\', page data file: '.$page_data_file_path,
					Mvc_Page_Exception::CODE_DUPLICATES_PAGE_ID
				);
			}

			$pages[$page_id] = $page_data;

			$pages[$page_data['parent_id']]['children'][] = $page_id;

			static::_loadPagesData_readDir( $pages, $site_id, $locale_str, $dir_path, $page_data );
		}

	}

	/**
	 * @param string $data_file_path
	 *
	 * @param array|null  $parent_page_data
	 *
	 * @param string $dir_name
	 *
	 * @return array|null
	 */
	public static function _loadPagesData_readPageDataFile( $data_file_path, array $parent_page_data = null, $dir_name='' )
	{

		if( !IO_File::isReadable( $data_file_path ) ) {
			return null;
		}

		/** @noinspection PhpIncludeInspection */
		$current_page_data = require $data_file_path;


		$current_page_data['children'] = [];
		$current_page_data['data_file_path'] = $data_file_path;

		$current_page_data['relative_path_fragment'] = rawurlencode($dir_name);

		if( $parent_page_data ) {

			$current_page_data['parent_id'] = $parent_page_data['id'];

			if($parent_page_data['relative_path']) {
				$current_page_data['relative_path'] = $parent_page_data['relative_path'].'/'.$current_page_data['relative_path_fragment'];
			} else {
				$current_page_data['relative_path'] = $current_page_data['relative_path_fragment'];
			}


		} else {
			$current_page_data['parent_id'] = '';
			$current_page_data['relative_path'] = '';
			$current_page_data['id'] = Mvc_Page::HOMEPAGE_ID;
		}


		return $current_page_data;
	}

	/**
	 * @param Mvc_Site_Interface      $site
	 * @param Locale                  $locale
	 * @param array                   $data
	 * @param Mvc_Page_Interface|null $parent_page
	 *
	 * @return Mvc_Page_Interface
	 */
	public static function createByData( Mvc_Site_Interface $site, Locale $locale, array $data, Mvc_Page_Interface $parent_page = null )
	{
		/**
		 * @var Mvc_Page $page
		 */
		$page = Mvc_Factory::getPageInstance();


		$page->setSite( $site );
		$page->setLocale( $locale );
		$page->setId( $data['id'] );

		if( $parent_page ) {
			$page->setParent( $parent_page );
		}

		unset( $data['id'] );

		$page->setData( $data );


		return $page;
	}

	/**
	 * @param array $data
	 */
	protected function setData( array $data )
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

		if(!isset($data['relative_path'])) {
			/**
			 * @var Mvc_Page $parent
			 */
			$parent = $this->getParent();
			$parent_path = $parent ? $parent->getRelativePath() : '';

			if(!$parent_path) {
				$data['relative_path'] = $data['relative_path_fragment'];
			} else {
				$data['relative_path'] = $parent_path.'/'.$data['relative_path_fragment'];
			}
		}


		foreach( $data as $key => $var ) {
			$this->{$key} = $var;
		}

		if(!$this->name) {
			$this->name = $this->title;
		}

		if(!$this->menu_title) {
			$this->menu_title = $this->title;
		}

		if(!$this->breadcrumb_title) {
			$this->breadcrumb_title = $this->title;
		}

	}


	/**
	 * @return string
	 */
	public function getDataDirPath()
	{
		return dirname( $this->getDataFilePath() ).'/';
	}

	/**
	 * @return string
	 */
	public function getDataFilePath()
	{
		return $this->data_file_path;
	}

	/**
	 * @param string $data_file_path
	 */
	public function setDataFilePath( $data_file_path )
	{
		$this->data_file_path = $data_file_path;
	}


}