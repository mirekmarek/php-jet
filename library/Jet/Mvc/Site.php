<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

require_once 'Site/Interface.php';
require_once 'Site/LocalizedData.php';

/**
 *
 */
class Mvc_Site extends BaseObject implements Mvc_Site_Interface, BaseObject_Cacheable_Interface
{

	use BaseObject_Cacheable_Trait;

	/**
	 * @var string
	 */
	protected static $site_data_file_name = 'site_data.php';

	/**
	 * @var string
	 */
	protected static $pages_dir = 'pages';

	/**
	 * @var string
	 */
	protected static $layouts_dir = 'layouts';

	/**
	 * @var array|Mvc_Site[]
	 */
	protected static $sites;

	/**
	 * @var Mvc_Site_LocalizedData_Interface[]
	 */
	protected static $URL_map;

	/**
	 *
	 *
	 * @var string
	 */
	protected $_site_id = '';
	/**
	 *
	 * @var string
	 */
	protected $name = '';
	/**
	 *
	 * @var bool
	 */
	protected $is_default = false;
	/**
	 *
	 * @var bool
	 */
	protected $is_active = false;

	/**
	 *
	 * @var Locale
	 */
	protected $default_locale;

	/**
	 * @var bool
	 */
	protected $SSL_required = false;

	/**
	 *
	 * @var Mvc_Site_LocalizedData[]
	 */
	protected $localized_data;


	/**
	 *
	 * @param bool $get_as_string (optional; if TRUE, string values of locales are returned; default: false)
	 *
	 * @return Locale[]|string[]
	 */
	public static function getAllLocalesList( $get_as_string = true )
	{
		$sites = static::loadSites();
		$locales = [];

		if( $get_as_string ) {

			foreach( $sites as $site ) {
				foreach( $site->getLocales( false ) as $locale ) {
					$locales[(string)$locale] = $locale->getName();
				}
			}

			asort( $locales );

		} else {
			foreach( $sites as $site ) {
				/**
				 * @var Mvc_Site_Interface $site
				 */
				foreach( $site->getLocales( false ) as $locale ) {
					$locales[(string)$locale] = $locale;
				}
			}
		}

		return $locales;
	}

	/**
	 * @return array
	 */
	public static function loadSitesData() {

		Debug_Profiler::blockStart('Load sites data');
		$sites = [];

		$dirs = IO_Dir::getSubdirectoriesList( JET_PATH_SITES );

		foreach( $dirs as $id ) {

			$data_file_path = static::getSiteDataFilePath( $id );

			if( !IO_File::exists( $data_file_path ) ) {
				continue;
			}


			/** @noinspection PhpIncludeInspection */
			$site_data = require $data_file_path;
			$site_data['id'] = $id;
			$sites[$id] = $site_data;
		}
		Debug_Profiler::blockEnd('Load sites data');

		return $sites;
	}


	/**
	 * @return Mvc_Site[]
	 *
	 * @throws Mvc_Page_Exception
	 */
	public static function loadSites()
	{

		if(static::$sites===null) {
			if( static::getCacheLoadEnabled() ) {

				$loader = static::$cache_loader;
				$sites = $loader();
				if($sites) {
					Debug_Profiler::message('cache hit');

					static::$sites = $sites;
					return $sites;
				}
			}


			$sites_data = static::loadSitesData();

			Debug_Profiler::blockStart('Create site instances');
			static::$sites = [];

			foreach( $sites_data as $data ) {
				$site = static::createSiteByData( $data );

				if(isset(static::$sites[$site->getId()])) {
					throw new Mvc_Page_Exception(
						'Duplicate site: \''.$site->getId().'\' ',
						Mvc_Page_Exception::CODE_DUPLICATES_PAGE_ID
					);

				}

				static::$sites[$site->getId()] = $site;
			}

			if(
				static::$sites &&
				static::getCacheSaveEnabled()
			) {

				$saver = static::$cache_saver;
				$saver( static::$sites );
			}

			Debug_Profiler::blockEnd('Create site instances');
		}

		return static::$sites;
	}

	/**
	 * @param array  $data
	 *
	 * @return Mvc_Site_Interface
	 */
	public static function createSiteByData( array $data )
	{

		/**
		 * @var Mvc_Site $site
		 */
		$site = new static();


		$site->_site_id = $data['id'];
		$site->name = $data['name'];
		$site->is_active = $data['is_active'];

		foreach( $data['localized_data'] as $locale_str => $localized_data ) {

			$l_data = $site->addLocale( new Locale( $locale_str ) );

			$l_data->setSite( $site );
			$l_data->setSSLRequired( !empty($localized_data['SSL_required']) );
			$l_data->setTitle( $localized_data['title'] );
			$l_data->setIsActive( $localized_data['is_active'] );
			$l_data->setURLs( $localized_data['URLs'] );

			$meta_tags = [];

			foreach( $localized_data['default_meta_tags'] as $m_data ) {
				$meta_tags[] = Mvc_Factory::getSiteLocalizedMetaTagInstance(
					$m_data['content'],
					$m_data['attribute'],
					$m_data['attribute_value']
				);
			}

			$l_data->setDefaultMetaTags( $meta_tags );
		}

		return $site;
	}

	/**
	 * @return Mvc_Site_LocalizedData_Interface[]
	 */
	public static function getUrlMap()
	{
		if(static::$URL_map!==null) {
			return static::$URL_map;
		}

		static::loadSites();
		static::$URL_map = [];

		foreach( static::$sites as $site ) {
			foreach( $site->getLocales() as $locale ) {
				$l_data = $site->getLocalizedData($locale);

				foreach($l_data->getURLs() as $URL) {
					static::$URL_map[$URL] = $l_data;
				}
			}
		}


		return static::$URL_map;
	}

	/**
	 *
	 * @param string $id
	 *
	 * @return Mvc_Site|null
	 */
	public static function get( $id )
	{
		static::loadSites();

		if( !isset( static::$sites[$id] ) ) {
			return null;
		}

		return static::$sites[$id];
	}

	/**
	 * @param string $id
	 *
	 * @return string
	 */
	protected static function getSiteDataFilePath( $id )
	{
		return JET_PATH_SITES.$id.'/'.static::$site_data_file_name;
	}


	/**
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_Interface
	 */
	public function addLocale( Locale $locale )
	{
		if( isset( $this->localized_data[(string)$locale] ) ) {
			return $this->localized_data[(string)$locale];
		}

		$new_ld = Mvc_Factory::getSiteLocalizedInstance( $locale );

		$this->localized_data[(string)$locale] = $new_ld;

		if(
			!$this->default_locale||
			!$this->default_locale->toString()
		) {
			$this->setDefaultLocale( $locale );
		}

		return $new_ld;
	}

	/**
	 *
	 * @param bool $get_as_string (optional), default: false
	 *
	 * @return Locale[]
	 */
	public function getLocales( $get_as_string = false )
	{

		$result = [];

		foreach( $this->localized_data as $ld ) {
			$locale = $ld->getLocale();

			$result[] = $get_as_string ? (string)$locale : $locale;
		}


		return $result;
	}


	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->_site_id;
	}

	/**
	 * @param string $id
	 *
	 */
	public function setId( $id )
	{
		$this->_site_id = $id;
	}

	/**
	 * Returns site name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( $name )
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getLayoutsPath()
	{
		return $this->getBasePath().static::$layouts_dir.'/';
	}

	/**
	 * Returns root directory path
	 *
	 * @return string
	 */
	public function getBasePath()
	{
		return JET_PATH_SITES.$this->_site_id.'/';
	}



	/**
	 * @return bool
	 */
	public function getSSLRequired()
	{
		return $this->SSL_required;
	}

	/**
	 * @param bool $SSL_required
	 */
	public function setSSLRequired( $SSL_required )
	{
		$this->SSL_required = $SSL_required;
	}


	/**
	 * Returns default locale
	 *
	 * @return Locale
	 */
	public function getDefaultLocale()
	{
		return $this->default_locale;
	}

	/**
	 *
	 * @param Locale $locale
	 */
	public function setDefaultLocale( Locale $locale )
	{
		$this->addLocale( $locale );

		$this->default_locale = $locale;
	}

	/**
	 * @param Locale $locale
	 *
	 * @return bool
	 */
	public function getHasLocale( Locale $locale )
	{
		return isset( $this->localized_data[$locale->toString()] );
	}

	/**
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_Interface
	 */
	public function getLocalizedData( Locale $locale )
	{
		return $this->localized_data[$locale->toString()];
	}

	/**
	 *
	 * @param Locale $locale
	 */
	public function removeLocale( Locale $locale )
	{
		if( !isset( $this->localized_data[(string)$locale] ) ) {
			return;
		}

		foreach( $this->localized_data as $ld ) {
			$o_locale = $ld->getLocale();

			if( (string)$o_locale==(string)$locale ) {
				unset( $this->localized_data[(string)$locale] );
				continue;
			}

			if( (string)$locale==(string)$this->default_locale ) {
				$this->default_locale = $o_locale;
			}

		}

		if( !count( $this->localized_data ) ) {
			$this->default_locale = null;
		}
	}

	/**
	 * @return bool
	 */
	public function getIsActive()
	{
		return $this->is_active;
	}

	/**
	 * @param bool $is_active
	 */
	public function setIsActive( $is_active )
	{
		$this->is_active = (bool)$is_active;
	}

	/**
	 * @param Locale $locale (optional)
	 *
	 * @return string
	 */
	public function getPagesDataPath( Locale $locale = null )
	{
		$path = $this->getBasePath().static::$pages_dir.'/';

		if( !$locale ) {
			return $path;
		}

		return $path.$locale.'/';

	}

	/**
	 *
	 * @return Mvc_Site_Interface
	 */
	public static function getDefaultSite()
	{
		$sites = static::loadSites();

		foreach( $sites as $site ) {
			if( $site->getIsDefault() ) {
				return $site;
			}
		}

		return null;
	}

	/**
	 * @return bool
	 */
	public function getIsDefault()
	{
		return $this->is_default;
	}

	/**
	 * @param bool $is_default
	 */
	public function setIsDefault( $is_default )
	{
		$this->is_default = (bool)$is_default;
	}

	/**
	 * @param Locale $locale
	 *
	 * @return Mvc_Page_Interface
	 */
	public function getHomepage( Locale $locale )
	{
		return Mvc_Page::get( Mvc_Page::HOMEPAGE_ID, $locale, $this->getId() );
	}

	/**
	 *
	 */
	public function saveDataFile()
	{
		$data = $this->toArray();

		foreach( $this->getLocales( true ) as $locale ) {
			/**
			 * @var string $locale
			 */
			unset( $data['localized_data'][$locale]['site_id'] );
			unset( $data['localized_data'][$locale]['locale'] );
		}


		$ar = new Data_Array( $data );

		$data = '<?php'.JET_EOL.'return '.$ar->export();

		$data_file_path = static::getSiteDataFilePath( $this->getId() );


		IO_File::write( $data_file_path, $data );
	}

	/**
	 * @return array
	 */
	public function toArray()
	{

		$data = get_object_vars( $this );

		foreach( $data as $k => $v ) {
			if( $k[0]=='_' ) {
				unset( $data[$k] );
			}
		}
		$data['localized_data'] = [];
		$data['default_locale'] = $this->default_locale->toString();


		foreach( $this->localized_data as $locale_str => $ld ) {
			$data['localized_data'][$locale_str] = $ld->toArray();
		}

		return $data;
	}

}