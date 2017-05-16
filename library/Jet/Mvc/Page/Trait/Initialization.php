<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
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
	 * @var array
	 */
	protected static $do_not_inherit_properties = [
		'breadcrumb_title',
		'menu_title',
		'order',
		'is_direct_output',
		'direct_output_file_name',
		'output',
	];


	/**
	 * @var string
	 */
	protected $data_file_path = '';

	/**
	 * @var Mvc_Page_Interface[]
	 */
	protected static $pages = [];

	/**
	 * @var Mvc_Page_Interface[]
	 */
	protected static $relative_URIs_map = [];

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
	 * @return array
	 */
	public static function getDoNotInheritProperties()
	{
		return static::$do_not_inherit_properties;
	}

	/**
	 * @param array $do_not_inherit_properties
	 */
	public static function setDoNotInheritProperties( $do_not_inherit_properties )
	{
		static::$do_not_inherit_properties = $do_not_inherit_properties;
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
				'Duplicates page: \''.$page->getKey().'\' ', Mvc_Page_Exception::CODE_DUPLICATES_PAGE_ID
			);
		}

		static::$pages[$site_id][$locale][$page_id] = $page;

		static::$relative_URIs_map[$site_id][$locale][$page->getRelativeUrl()] = $page;

	}

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale             $locale
	 *
	 */
	public static function loadPages( Mvc_Site_Interface $site, Locale $locale )
	{

		$site_id = $site->getId();
		$locale_str = $locale->toString();

		if(
			array_key_exists( $site_id, static::$pages ) &&
			array_key_exists( $locale_str, static::$pages[$site_id] )
		) {
			return;
		}

		static::_loadPages_readDir( $site, $locale, $site->getPagesDataPath( $locale ) );

	}

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale             $locale
	 * @param string             $source_dir_path
	 * @param array              $parent_page_data (optional)
	 * @param Mvc_Page           $parent_page
	 *
	 * @throws Mvc_Page_Exception
	 */
	protected static function _loadPages_readDir( Mvc_Site_Interface $site, Locale $locale, $source_dir_path, array $parent_page_data = null, Mvc_Page $parent_page = null )
	{
		/**
		 * @var Mvc_Page|Mvc_Page_Trait_Auth $this
		 * @var Mvc_Page static
		 */

		$list = IO_Dir::getList( $source_dir_path, '*', true, false );

		if( !$parent_page_data ) {
			$page_data_file_path = $source_dir_path.static::$page_data_file_name;

			$page_data = static::_loadPages_readPageDataFile( $page_data_file_path );
			if(!$page_data) {
				throw new Mvc_Page_Exception(
					'Page data file is not readable: '.$page_data_file_path,
					Mvc_Page_Exception::CODE_UNABLE_TO_READ_PAGE_DATA
				);

			}
			$page_data['URL_fragment'] = '';

			$page = static::createPageByData( $site, $locale, $page_data );
			static::appendPage( $page );


			$parent_page_data = $page_data;
			$parent_page = $page;

		}


		foreach( $list as $dir_path => $dir_name ) {

			$page_data = static::_loadPages_readPageDataFile( $dir_path.static::$page_data_file_name, $parent_page_data );
			if(!$page_data) {
				continue;
			}

			$page_data['URL_fragment'] = $dir_name;

			$page = static::createPageByData( $site, $locale, $page_data, $parent_page );
			static::appendPage( $page );

			static::_loadPages_readDir( $site, $locale, $dir_path, $page_data, $page );
		}

	}

	/**
	 * @param string $data_file_path
	 *
	 * @param array  $parent_page_data
	 *
	 * @return array|null
	 */
	public static function _loadPages_readPageDataFile( $data_file_path, array $parent_page_data = null )
	{

		if( !IO_File::isReadable( $data_file_path ) ) {
			return null;
		}

		/** @noinspection PhpIncludeInspection */
		$current_page_data = require $data_file_path;


		$current_page_data['data_file_path'] = $data_file_path;

		if( $parent_page_data ) {

			if( isset( $parent_page_data['contents'] ) ) {
				unset( $parent_page_data['contents'] );
			}
			unset( $parent_page_data['id'] );

			foreach( $parent_page_data as $k => $v ) {
				if( in_array( $k, static::$do_not_inherit_properties ) ) {
					continue;
				}

				if( !array_key_exists( $k, $current_page_data ) ) {
					$current_page_data[$k] = $v;
				}
			}


		} else {

			$current_page_data['relative_URI'] = '/';
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
	public static function createPageByData( Mvc_Site_Interface $site, Locale $locale, array $data, Mvc_Page_Interface $parent_page = null )
	{
		$page = Mvc_Factory::getPageInstance();


		$page->setSite( $site );
		$page->setLocale( $locale );
		$page->setId( $data['id'] );

		if( $parent_page ) {
			$page->setParent( $parent_page );
		}

		unset( $data['id'] );


		if( !isset( $data['breadcrumb_title'] ) ) {
			$data['breadcrumb_title'] = $data['title'];
		}
		if( !isset( $data['menu_title'] ) ) {
			$data['menu_title'] = $data['title'];
		}

		if( isset( $data['meta_tags'] ) ) {
			$meta_tags = [];
			foreach( $data['meta_tags'] as $i => $m_dat ) {
				$mtg = Mvc_Factory::getPageMetaTagInstance();

				$mtg->setData( $m_dat );

				$meta_tags[] = $mtg;
			}
			unset( $data['meta_tags'] );
			$page->setMetaTags( $meta_tags );
		}

		if( isset( $data['contents'] ) ) {
			$contents = [];

			foreach( $data['contents'] as $i => $c_dat ) {

				$cnt = Mvc_Factory::getPageContentInstance();
				$cnt->setData( $c_dat );

				$contents[] = $cnt;
			}

			unset( $data['contents'] );
			$page->setContent( $contents );
		}


		foreach( $data as $key => $var ) {
			$page->{$key} = $var;
		}

		$page->setUrlFragment( $data['URL_fragment'] );

		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return $page;
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