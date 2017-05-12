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
class Mvc_Site extends BaseObject implements Mvc_Site_Interface
{
	const SITE_DATA_FILE_NAME = 'site_data.php';
	const URL_MAP_FILE_NAME = 'urls_map.php';
	const PAGES_DIR = 'pages';
	const LAYOUTS_DIR = 'layouts';
	/**
	 * @var array|Mvc_Site_LocalizedData_URL[]
	 */
	protected static $URL_map;
	/**
	 * @var array|Mvc_Site[]
	 */
	protected static $_loaded = [];
	/**
	 *
	 *
	 * @var string
	 */
	protected $site_id = '';
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
	 *
	 * @var Mvc_Site_LocalizedData[]
	 */
	protected $localized_data;

	/**
	 * Returns a list of all locales for all sites
	 *
	 * @param bool $get_as_string (optional; if TRUE, string values of locales are returned; default: false)
	 *
	 * @return Locale[]|string[]
	 */
	public static function getAllLocalesList( $get_as_string = true )
	{
		$sites = static::getList();
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
	 * Returns a list of all sites
	 *
	 * @return Mvc_Site[]
	 */
	public static function getList()
	{

		$dirs = IO_Dir::getSubdirectoriesList( JET_SITES_PATH );

		$sites = [];

		foreach( $dirs as $id ) {
			$site = Mvc_Site::get( $id );

			$sites[$id] = $site;
		}

		uasort(
			$sites, function( Mvc_Site_Interface $site_a, Mvc_Site_Interface $site_b ) {
			return strcmp( $site_a->getName(), $site_b->getName() );
		}
		);

		return $sites;
	}

	/**
	 *
	 * @param string $id
	 *
	 * @return Mvc_Site|bool
	 */
	public static function get( $id )
	{

		$id_s = (string)$id;

		if( !isset( static::$_loaded[$id_s] ) ) {

			static::_load( $id );

		}

		if( !isset( static::$_loaded[$id_s] ) ) {
			return false;
		}

		return static::$_loaded[$id_s];
	}

	/**
	 * @param string $id
	 *
	 * @return Mvc_Site|Mvc_Site_Interface|null
	 */
	public static function _load( $id )
	{

		if( isset( static::$_loaded[$id] ) ) {
			return static::$_loaded[$id];
		}

		$data_file_path = static::getSiteDataFilePath( $id );

		if( !IO_File::exists( $data_file_path ) ) {
			return null;
		}

		/** @noinspection PhpIncludeInspection */
		$data = require $data_file_path;

		$site = new self();

		$URL_map = $site->getUrlsMap();

		$site->site_id = $id;
		$site->name = $data['name'];
		$site->is_active = $data['is_active'];

		foreach( $data['localized_data'] as $locale_str => $localized_data ) {
			$locale = new Locale( $locale_str );

			$site->addLocale( $locale );

			$l_data = $site->localized_data[$locale_str];

			$l_data->setIsActive( $localized_data['is_active'] );
			$l_data->setDefaultHeadersSuffix( $localized_data['default_headers_suffix'] );
			$l_data->setDefaultBodyPrefix( $localized_data['default_body_prefix'] );
			$l_data->setDefaultBodySuffix( $localized_data['default_body_suffix'] );

			$meta_tags = [];

			foreach( $localized_data['default_meta_tags'] as $m_data ) {
				$meta_tags[] = Mvc_Factory::getSiteLocalizedMetaTagInstance(
					$m_data['content'],
					$m_data['attribute'],
					$m_data['attribute_value']
				);
			}

			$l_data->setDefaultMetaTags( $meta_tags );

			$URLs = [];

			foreach( $URL_map as $URL_data ) {
				if( $URL_data->getSiteId()!=(string)$id||$URL_data->getLocale()!=$locale_str ) {
					continue;
				}

				$URLs[] = $URL_data;
			}


			$l_data->setURLs( $URLs );
		}

		static::$_loaded[$id] = $site;

		return $site;
	}

	/**
	 * @param string $id
	 *
	 * @return string
	 */
	protected static function getSiteDataFilePath( $id )
	{
		return JET_SITES_PATH.$id.'/'.static::SITE_DATA_FILE_NAME;
	}

	/**
	 * @return array|Mvc_Site_LocalizedData_URL_Interface[]
	 *
	 * @throws Mvc_Router_Exception
	 */
	public function getUrlsMap()
	{

		if( static::$URL_map ) {
			return static::$URL_map;
		}

		$sites_URL_map_file_path = static::getUrlMapFilePath();


		/** @noinspection PhpIncludeInspection */
		$site_URL_map_data = require $sites_URL_map_file_path;


		$URL_map = [];

		foreach( $site_URL_map_data as $key => $URLs ) {
			list( $site_id, $locale_str ) = explode( '/', $key );

			$site_default_SSL_URL = null;
			$site_default_non_SSL_URL = null;

			foreach( $URLs as $URL ) {
				$URL_i = Mvc_Factory::getSiteLocalizedURLInstance( $URL );
				$URL_i->setSiteId( $site_id );
				$URL_i->setLocale( new Locale( $locale_str ) );

				if( $URL_i->getIsSSL() ) {
					if( !$site_default_SSL_URL ) {
						$site_default_SSL_URL = $URL;
						$URL_i->setIsDefault( true );
					}
				} else {
					if( !$site_default_non_SSL_URL ) {
						$site_default_non_SSL_URL = $URL;
						$URL_i->setIsDefault( true );
					}
				}

				$URL = $URL_i->toString();

				if( isset( $URL_map[$URL] ) ) {
					throw new Mvc_Router_Exception( 'Duplicated site URL: \''.$URL.'\' ' );
				}

				$URL_map[$URL] = $URL_i;
			}
		}

		static::$URL_map = $URL_map;

		return $URL_map;
	}

	/**
	 * @return string
	 */
	protected static function getUrlMapFilePath()
	{
		return JET_SITES_PATH.static::URL_MAP_FILE_NAME;
	}

	/**
	 * Add locale
	 *
	 * @param Locale $locale
	 */
	public function addLocale( Locale $locale )
	{
		if( isset( $this->localized_data[(string)$locale] ) ) {
			return;
		}

		$new_ld = Mvc_Factory::getLocalizedSiteInstance( $locale );

		$this->localized_data[(string)$locale] = $new_ld;

		if( !$this->default_locale||!$this->default_locale->toString() ) {
			$this->setDefaultLocale( $locale );
		}
	}

	/**
	 * Returns site locales
	 *
	 * @see Mvc_Site
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
	 * @param string $id
	 *
	 */
	public function setId( $id )
	{
		$this->site_id = $id;
	}

	/**
	 *
	 */
	public function generateId()
	{

		$name = trim( $this->name );

		$id = Data_Text::removeAccents( $name );
		$id = str_replace( ' ', '_', $id );
		$id = preg_replace( '/[^a-z0-9_]/i', '', $id );
		$id = strtolower( $id );
		$id = preg_replace( '~([_]{2,})~', '_', $id );
		$id = substr( $id, 0, 50 );

		$this->site_id = $id;
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
		return $this->getBasePath().static::LAYOUTS_DIR.'/';
	}

	/**
	 * Returns root directory path
	 *
	 * @return string
	 */
	public function getBasePath()
	{
		return JET_SITES_PATH.$this->site_id.'/';
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
	 * Set default locale. Add locale first if is not defined.
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
	 *
	 * @return Mvc_Site_LocalizedData_URL_Interface[]
	 */
	public function getURLs( Locale $locale )
	{
		return $this->localized_data[(string)$locale]->getURLs();
	}

	/**
	 * Add URL
	 *
	 * @param Locale $locale
	 * @param string $URL
	 */
	public function addURL( Locale $locale, $URL )
	{
		$this->localized_data[(string)$locale]->addURL( $URL );
	}

	/**
	 * Remove URL. If the URL was default, then set as the default first possible URL
	 *
	 * @param Locale $locale
	 * @param string $URL
	 */
	public function removeURL( Locale $locale, $URL )
	{
		$this->localized_data[(string)$locale]->addURL( $URL );
	}

	/**
	 * Set default URL. Add URL first if is not defined.
	 *
	 * @param Locale $locale
	 * @param string $URL
	 */
	public function setDefaultURL( Locale $locale, $URL )
	{
		$this->localized_data[(string)$locale]->setDefaultURL( $URL );
	}

	/**
	 * Returns default URL
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_URL_Interface
	 */
	public function getDefaultURL( Locale $locale )
	{

		return $this->localized_data[(string)$locale]->getDefaultURL();
	}

	/**
	 * Set default URL. Add URL first if is not defined.
	 *
	 * @param Locale $locale
	 * @param string $URL
	 */
	public function setDefaultSslURL( Locale $locale, $URL )
	{
		$this->localized_data[(string)$locale]->setDefaultSslURL( $URL );
	}

	/**
	 * Returns default URL
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_URL_Interface
	 */
	public function getDefaultSslURL( Locale $locale )
	{
		return $this->localized_data[(string)$locale]->getDefaultSslURL();
	}

	/**
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_MetaTag_Interface[]
	 */
	public function getDefaultMetaTags( Locale $locale )
	{
		return $this->localized_data[(string)$locale]->getDefaultMetaTags();
	}

	/**
	 * @param Locale                                   $locale
	 * @param Mvc_Site_LocalizedData_MetaTag_Interface $meta_tag
	 */
	public function addDefaultMetaTag( Locale $locale, Mvc_Site_LocalizedData_MetaTag_Interface $meta_tag )
	{
		$this->localized_data[(string)$locale]->addDefaultMetaTag( $meta_tag );
	}

	/**
	 * @param Locale $locale
	 * @param int    $index
	 */
	public function removeDefaultMetaTag( Locale $locale, $index )
	{
		$this->localized_data[(string)$locale]->removeDefaultMetaTag( $index );
	}

	/**
	 * @param Locale                                     $locale
	 * @param Mvc_Site_LocalizedData_MetaTag_Interface[] $meta_tags
	 */
	public function setDefaultMetaTags( Locale $locale, $meta_tags )
	{
		$this->localized_data[(string)$locale]->setDefaultMetaTags( $meta_tags );
	}

	/**
	 * Remove locale. If the locale was default, then set as the default first possible locale
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
		$path = $this->getBasePath().static::PAGES_DIR.'/';

		if( !$locale ) {
			return $path;
		}

		return $path.$locale.'/';

	}

	/**
	 * Returns default site data
	 *
	 * @return Mvc_Site_Interface
	 */
	public function getDefault()
	{
		$sites = static::getList();

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
	 * @return string
	 */
	public function getId()
	{
		return $this->site_id;
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
			unset( $data['localized_data'][$locale]['URLs'] );
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

		$data = [
			'id'             => $this->site_id, 'name' => $this->name, 'is_default' => $this->is_default,
			'is_active'      => $this->is_active, 'default_locale' => $this->default_locale->toString(),
			'localized_data' => [],
		];

		foreach( $this->localized_data as $locale_str => $ld ) {
			$data['localized_data'][$locale_str] = $ld->toArray();
		}

		return $data;
	}

	/**
	 *
	 */
	public function saveUrlMapFile()
	{
		$URLs_map_data = [];
		if( static::$URL_map ) {
			foreach( static::$URL_map as $key => $URLs ) {
				$URLs_map_data[$key] = [];

				foreach( $URLs as $URL ) {
					/**
					 * @var Mvc_Site_LocalizedData_URL_Interface $URL
					 */
					if( $URL->getIsSSL() ) {
						$URL = 'SSL:'.$URL;
					}

					$URLs_map_data[$key][] = (string)$URL;
				}
			}
		}

		foreach( $this->localized_data as $ld ) {

			$key = $this->getId().'/'.$ld->getLocale();

			$URLs_map_data[$key] = [];

			foreach( $ld->getURLs() as $URL ) {
				if( !$URL->getIsDefault() ) {
					continue;
				}

				if( $URL->getIsSSL() ) {
					continue;
				}

				$URLs_map_data[$key][] = $URL->toString();
			}

			foreach( $ld->getURLs() as $URL ) {
				if( !$URL->getIsDefault() ) {
					continue;
				}

				if( !$URL->getIsSSL() ) {
					continue;
				}

				$URLs_map_data[$key][] = 'SSL:'.$URL->toString();
			}

			foreach( $ld->getURLs() as $URL ) {
				if( $URL->getIsDefault() ) {
					continue;
				}

				if( $URL->getIsSSL() ) {
					$URLs_map_data[$key][] = 'SSL:'.$URL->toString();
				} else {
					$URLs_map_data[$key][] = $URL->toString();
				}
			}
		}

		$ar = new Data_Array( $URLs_map_data );

		IO_File::write(
			static::getUrlMapFilePath(), '<?php'.JET_EOL.'return '.$ar->export()
		);

	}

}