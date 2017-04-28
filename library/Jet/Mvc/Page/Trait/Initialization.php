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
	 * @var array
	 */
	protected static $do_not_inherit_properties = [
		'breadcrumb_title',
		'menu_title',
		'order',
		'is_direct_output',
		'direct_output_file_name',
		'output'
	];

	/**
	 * @var bool
	 */
	protected static $site_pages_loaded_flag = [];

	/**
	 * @var Mvc_Page_Interface[]
	 */
	protected static $loaded_pages = [];

	/**
	 * @var string[]
	 */
	protected static $relative_URIs_map = [];

	/**
	 * @var string
	 */
	protected $data_file_path = '';

	/**
	 * @param string $data_file_path
	 */
	public function setDataFilePath($data_file_path)
	{
		$this->data_file_path = $data_file_path;
	}

	/**
	 * @return string
	 */
	public function getDataFilePath()
	{
		return $this->data_file_path;
	}

	/**
	 * @return string
	 */
	public function getDataDirPath() {
		return dirname($this->getDataFilePath()).'/';
	}


	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 * @param string $root_dir
	 * @param array $parent_page_data (optional)
	 * @param Mvc_Page $parent_page
	 *
	 */
	protected static function _loadPages_readDir( Mvc_Site_Interface $site, Locale $locale, $root_dir, array $parent_page_data=null, Mvc_Page $parent_page=null ) {
		$list = IO_Dir::getList( $root_dir, '*', true, false );

		if(!$parent_page_data) {
			$page_data_file_path = $root_dir.self::PAGE_DATA_FILE_NAME;
			$URL_fragment = '';

			$page_data = static::_loadPages_readPageDataFile( $locale, $page_data_file_path, $URL_fragment );
			$page = static::_loadPages_createPage( $site, $page_data );


			$parent_page_data = $page_data;
			$parent_page = $page;

		}


		$data_file_name = self::PAGE_DATA_FILE_NAME;

		foreach( $list as $path=>$file ) {

			$URL_fragment = $file;

			$page_data = static::_loadPages_readPageDataFile( $locale, $path.$data_file_name, $URL_fragment, $parent_page_data );
			$page = static::_loadPages_createPage( $site, $page_data, $parent_page);

			static::_loadPages_readDir($site, $locale, $path, $page_data, $page);
		}

	}

	/**
	 * @param Locale $locale
	 * @param string $data_file_path
	 *
	 * @param $URL_fragment
	 * @param array $parent_page_data
	 *
	 * @throws Mvc_Page_Exception
	 * @return array
	 */
	public static function _loadPages_readPageDataFile( Locale $locale, $data_file_path, $URL_fragment, array $parent_page_data=null ) {

		if(!IO_File::isReadable($data_file_path)) {
			throw new Mvc_Page_Exception( 'Page data file is not readable: '.$data_file_path, Mvc_Page_Exception::CODE_UNABLE_TO_READ_PAGE_DATA );
		}

		/** @noinspection PhpIncludeInspection */
		$current_page_data = require $data_file_path;

		$current_page_data['URL_fragment'] = rawurlencode($URL_fragment);
		$current_page_data['data_file_path'] = $data_file_path;
		$current_page_data['locale'] = $locale;

		if($parent_page_data) {
			$current_page_data['relative_URI'] = $parent_page_data['relative_URI'].$current_page_data['URL_fragment'].'/';

			if(isset($parent_page_data['contents'])) {
				unset($parent_page_data['contents']);
			}
			unset($parent_page_data['id']);

			foreach( $parent_page_data as $k=>$v ) {
				if( in_array($k, static::$do_not_inherit_properties) ) {
					continue;
				}

				if(!array_key_exists($k, $current_page_data)) {
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
	 * @param array $data
	 * @param Mvc_Site_Interface $site
	 * @param Mvc_Page $parent_page
	 *
	 * @throws Mvc_Page_Exception
	 *
	 * @return Mvc_Page
	 */
	public static function _loadPages_createPage( Mvc_Site_Interface $site, array $data, Mvc_Page $parent_page=null ) {

		$page = new static();
		/**
		 * @var Mvc_Page $page
		 */

		$page->setSite( $site );
		$page->setLocale( $data['locale'] );
		$page->setId( $data['id'] );
		if($parent_page) {
			$page->setParent($parent_page);
		}

		unset( $data['id'] );

		$page->data_file_path = $data['data_file_path'];

		if(!isset($data['breadcrumb_title'])) {
			$data['breadcrumb_title'] = $data['title'];
		}
		if(!isset($data['menu_title'])) {
			$data['menu_title'] = $data['title'];
		}

		if(empty($data['contents'])) {
			$data['contents'] = [];
		}
		if(empty($data['meta_tags'])) {
			$data['meta_tags'] = [];
		}

		$meta_tags = [];

		foreach( $data['meta_tags']  as $i=>$m_dat) {
			$mtg = Mvc_Factory::getPageMetaTagInstance();

			$mtg->setData( $m_dat );

			$meta_tags[] = $mtg;
		}

		unset( $data['meta_tags'] );
		$page->setMetaTags($meta_tags);



		$contents = [];

		foreach( $data['contents']  as $i=>$c_dat) {

			$cnt = Mvc_Factory::getPageContentInstance();
			$cnt->setData($c_dat);

			$contents[] = $cnt;
		}

		unset( $data['contents'] );
		$page->setContent($contents);

		foreach( $data as $key=>$var ) {
			$page->{$key} = $var;
		}

		/** @noinspection PhpParamsInspection */
		static::appendPage( $page );

		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return $page;
	}

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 *
	 */
	public static function loadPages( Mvc_Site_Interface $site, Locale $locale ) {

		$key = $site->getId().':'.$locale;

		if(isset(static::$site_pages_loaded_flag[$key])) {
			return;
		}

		static::_loadPages_readDir( $site, $locale,  $site->getPagesDataPath( $locale ));

		static::$site_pages_loaded_flag[$key] = true;

		return;
	}

	/**
	 *
	 *
	 * @param string $site_id
	 * @param Locale $locale
	 * @param string $page_id
	 *
	 * @return Mvc_Page_Interface|null
	 */
	public static function _load($site_id, $locale, $page_id) {

		$site_class_name = JET_MVC_SITE_CLASS;

		/**
		 * @var Mvc_Site_Interface $site_class_name
		 */
		$site =  $site_class_name::get( $site_id );

		static::loadPages($site, $locale);

		$key = $site_id.':'.$locale.':'.$page_id;

		if(!isset(static::$loaded_pages[$key])) {
			return null;
		}

		return static::$loaded_pages[$key];

	}

	/**
	 * @param Mvc_Page_Interface|Mvc_Page $page
	 * @throws Mvc_Page_Exception
	 */
	public static function appendPage( Mvc_Page_Interface $page ) {

		$page_key = $page->getKey();

		if(isset(static::$loaded_pages[$page_key])) {
			throw new Mvc_Page_Exception( 'Duplicates page key: \''.$page_key.'\' ', Mvc_Page_Exception::CODE_DUPLICATES_PAGE_ID  );
		}

		static::$loaded_pages[$page_key] = $page;
		static::$site_pages_loaded_flag[$page_key] = true;

		$page->setUrlFragment( rawurldecode($page->getUrlFragment()) );

		static::$relative_URIs_map[$page->getSite()->getId()][(string)$page->getLocale()][$page->getRelativeUrl()] = $page_key;

	}

}